<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace SK\Component\Tagebuch\Site\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Table\Table;
use SK\Component\Tagebuch\Administrator\Extension\TagebuchComponent;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;
use Joomla\Utilities\IpHelper;

/**
 * Tagebuch Component Report Model
 *
 * @since  1.5
 */
class ReportModel extends ItemModel
{
	/**
	 * Model context string.
	 *
	 * @var        string
	 */
	protected $_context = 'com_tagebuch.report';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 *
	 * @return void
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('report.id', $pk);

		$offset = $app->input->getUInt('limitstart');
		$this->setState('list.offset', $offset);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		$user = Factory::getUser();

		// If $pk is set then authorise on complete asset, else on component only
		$asset = empty($pk) ? 'com_tagebuch' : 'com_tagebuch.report.' . $pk;

		if ((!$user->authorise('core.edit.state', $asset)) && (!$user->authorise('core.edit', $asset)))
		{
			$this->setState('filter.published', TagebuchComponent::CONDITION_PUBLISHED);
			$this->setState('filter.archived', TagebuchComponent::CONDITION_ARCHIVED);
		}

		$this->setState('filter.language', Multilanguage::isEnabled());
	}

	/**
	 * Method to get report data.
	 *
	 * @param   integer  $pk  The id of the report.
	 *
	 * @return  object|boolean  Menu item data object on success, boolean false
	 */
	public function getItem($pk = null)
	{
		$user = Factory::getUser();

		$pk = (int) ($pk ?: $this->getState('report.id'));

		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$pk]))
		{
			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true);

				$query->select(
					$this->getState(
						'item.select',
						[
							$db->quoteName('a.id'),
							$db->quoteName('a.asset_id'),
							$db->quoteName('a.title'),
							$db->quoteName('a.alias'),
							$db->quoteName('a.introtext'),
							$db->quoteName('a.fulltext'),
							$db->quoteName('a.state'),
							$db->quoteName('a.catid'),
							$db->quoteName('a.created'),
							$db->quoteName('a.created_by'),
							$db->quoteName('a.created_by_alias'),
							$db->quoteName('a.modified'),
							$db->quoteName('a.modified_by'),
							$db->quoteName('a.checked_out'),
							$db->quoteName('a.checked_out_time'),
							$db->quoteName('a.publish_up'),
							$db->quoteName('a.publish_down'),
							$db->quoteName('a.images'),
							$db->quoteName('a.urls'),
							$db->quoteName('a.attribs'),
							$db->quoteName('a.version'),
							$db->quoteName('a.ordering'),
							$db->quoteName('a.metakey'),
							$db->quoteName('a.metadesc'),
							$db->quoteName('a.access'),
							$db->quoteName('a.hits'),
							$db->quoteName('a.metadata'),
							$db->quoteName('a.featured'),
							$db->quoteName('a.language'),
						]
					)
				)
					->select(
						[
							$db->quoteName('fp.featured_up'),
							$db->quoteName('fp.featured_down'),
							$db->quoteName('c.title', 'category_title'),
							$db->quoteName('c.alias', 'category_alias'),
							$db->quoteName('c.access', 'category_access'),
							$db->quoteName('c.language', 'category_language'),
							$db->quoteName('fp.ordering'),
							$db->quoteName('u.name', 'author'),
							$db->quoteName('parent.title', 'parent_title'),
							$db->quoteName('parent.id', 'parent_id'),
							$db->quoteName('parent.path', 'parent_route'),
							$db->quoteName('parent.alias', 'parent_alias'),
							$db->quoteName('parent.language', 'parent_language'),
							'ROUND(' . $db->quoteName('v.rating_sum') . ' / ' . $db->quoteName('v.rating_count') . ', 0) AS '
								. $db->quoteName('rating'),
							$db->quoteName('v.rating_count', 'rating_count'),
						]
					)
					->from($db->quoteName('#__content', 'a'))
					->join(
						'INNER',
						$db->quoteName('#__categories', 'c'),
						$db->quoteName('c.id') . ' = ' . $db->quoteName('a.catid')
					)
					->join('LEFT', $db->quoteName('#__content_frontpage', 'fp'), $db->quoteName('fp.content_id') . ' = ' . $db->quoteName('a.id'))
					->join('LEFT', $db->quoteName('#__users', 'u'), $db->quoteName('u.id') . ' = ' . $db->quoteName('a.created_by'))
					->join('LEFT', $db->quoteName('#__categories', 'parent'), $db->quoteName('parent.id') . ' = ' . $db->quoteName('c.parent_id'))
					->join('LEFT', $db->quoteName('#__content_rating', 'v'), $db->quoteName('a.id') . ' = ' . $db->quoteName('v.content_id'))
					->where(
						[
							$db->quoteName('a.id') . ' = :pk',
							$db->quoteName('c.published') . ' > 0',
						]
					)
					->bind(':pk', $pk, ParameterType::INTEGER);

				// Filter by language
				if ($this->getState('filter.language'))
				{
					$query->whereIn($db->quoteName('a.language'), [Factory::getLanguage()->getTag(), '*'], ParameterType::STRING);
				}

				if (!$user->authorise('core.edit.state', 'com_content.article.' . $pk)
					&& !$user->authorise('core.edit', 'com_content.article.' . $pk)
				)
				{
					// Filter by start and end dates.
					$nowDate = Factory::getDate()->toSql();

					$query->extendWhere(
						'AND',
						[
							$db->quoteName('a.publish_up') . ' IS NULL',
							$db->quoteName('a.publish_up') . ' <= :publishUp',
						],
						'OR'
					)
						->extendWhere(
							'AND',
							[
								$db->quoteName('a.publish_down') . ' IS NULL',
								$db->quoteName('a.publish_down') . ' >= :publishDown',
							],
							'OR'
						)
						->bind([':publishUp', ':publishDown'], $nowDate);
				}

				// Filter by published state.
				$published = $this->getState('filter.published');
				$archived = $this->getState('filter.archived');

				if (is_numeric($published))
				{
					$query->whereIn($db->quoteName('a.state'), [(int) $published, (int) $archived]);
				}

				$db->setQuery($query);

				$data = $db->loadObject();

				if (empty($data))
				{
					throw new \Exception(Text::_('COM_TAGEBUCH_ERROR_REPORT_NOT_FOUND'), 404);
				}

				// Check for published state if filter set.
				if ((is_numeric($published) || is_numeric($archived)) && ($data->state != $published && $data->state != $archived))
				{
					throw new \Exception(Text::_('COM_CONTENT_ERROR_ARTICLE_NOT_FOUND'), 404);
				}

				// Convert parameter fields to objects.
				$registry = new Registry($data->attribs);

				$data->params = clone $this->getState('params');
				$data->params->merge($registry);

				$data->metadata = new Registry($data->metadata);

				// Technically guest could edit an article, but lets not check that to improve performance a little.
				if (!$user->get('guest'))
				{
					$userId = $user->get('id');
					$asset = 'com_content.article.' . $data->id;

					// Check general edit permission first.
					if ($user->authorise('core.edit', $asset))
					{
						$data->params->set('access-edit', true);
					}

					// Now check if edit.own is available.
					elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
					{
						// Check for a valid user and that they are the owner.
						if ($userId == $data->created_by)
						{
							$data->params->set('access-edit', true);
						}
					}
				}

				// Compute view access permissions.
				if ($access = $this->getState('filter.access'))
				{
					// If the access filter has been set, we already know this user can view.
					$data->params->set('access-view', true);
				}
				else
				{
					// If no access filter is set, the layout takes some responsibility for display of limited information.
					$user = Factory::getUser();
					$groups = $user->getAuthorisedViewLevels();

					if ($data->catid == 0 || $data->category_access === null)
					{
						$data->params->set('access-view', in_array($data->access, $groups));
					}
					else
					{
						$data->params->set('access-view', in_array($data->access, $groups) && in_array($data->category_access, $groups));
					}
				}

				$this->_item[$pk] = $data;
			}
			catch (\Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go through the error handler to allow Redirect to work.
					throw $e;
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

		return $this->_item[$pk];
	}

	/**
	 * Cleans the cache of com_tagebuch and tagebuch modules
	 *
	 * @param   string   $group     The cache group
	 * @param   integer  $clientId  The ID of the client
	 *
	 * @return  void
	 *
	 * @since   3.9.9
	 */
	protected function cleanCache($group = null, $clientId = 0)
	{
		parent::cleanCache('com_tagebuch');
		parent::cleanCache('mod_tagebuch_archive');
		parent::cleanCache('mod_tagebuch_categories');
		parent::cleanCache('mod_tagebuch_category');
		parent::cleanCache('mod_tagebuch_latest');
		parent::cleanCache('mod_tagebuch_news');
		parent::cleanCache('mod_tagebuch_popular');
	}
}
