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
use Joomla\CMS\Helper\UserGroupsHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Table\Table;
use SK\Component\Tagebuch\Administrator\Extension\TagebuchComponent;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;
use Joomla\Utilities\IpHelper;
use SK\Component\Tagebuch\Administrator\Helper\TagebuchHelper;
use SK\Component\Tagebuch\Site\Helper\RouteHelper as RouteHelper;

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
	 * @since 1.0
	 */
	protected $_context = 'com_tagebuch.report';

	/**
	 * Navigation Items
	 *
	 * @var \stdClass
	 * @since 1.0
	 */
	protected $_navigationItems = null;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return void
	 * @throws \Exception
	 * @since   1.6
	 *
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

		$user = $app->getIdentity();

		// If $pk is set then authorise on complete asset, else on component only
		$asset = empty($pk) ? 'com_tagebuch' : 'com_tagebuch.report.' . $pk;

		$this->setState('filter.language', Multilanguage::isEnabled());
	}

	/**
	 * Method to get report data.
	 *
	 * @param   integer  $pk  The id of the report.
	 *
	 * @return  object|boolean  Menu item data object on success, boolean false
	 * @since 1.0
	 */
	public function getItem($pk = null)
	{
		$app = Factory::getApplication();
		$user = $app->getIdentity();


		$pk = (int) ($pk ?: $this->getState('report.id'));
		$skhelper = new TagebuchHelper;
		$this->state->set('DatumNotFound', false);

		$datumssuche = $app->input->getString('datum');
		if (($pk === 0) && ( $datumssuche != null) ){
			$pk = (int) $skhelper->getIdFromDateString($datumssuche);
			if ($pk === 0)
			{
				$this->state->set('DatumNotFound', $datumssuche);
			}
		}

		$pk = $pk === 0 ? $skhelper->getLastId($pk) : $pk;

		$userAccess = $skhelper->getTagebuchAccess();

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
							$db->quoteName('a.alias'),
							$db->quoteName('a.sff'),
							$db->quoteName('a.sfs'),
							$db->quoteName('a.sfan'),
							$db->quoteName('a.text_fs'),
							$db->quoteName('a.text_ss'),
							$db->quoteName('a.text_z1'),
							$db->quoteName('a.text_z2'),
							$db->quoteName('a.text_an'),
							$db->quoteName('a.text_bl'),
							$db->quoteName('a.datum'),
							$db->quoteName('a.fs_erstellt'),
							$db->quoteName('a.fs_erstellt_von'),
							$db->quoteName('a.fs_laenderung'),
							$db->quoteName('a.fs_laenderung_von'),
							$db->quoteName('a.ss_erstellt'),
							$db->quoteName('a.ss_erstellt_von'),
							$db->quoteName('a.ss_laenderung'),
							$db->quoteName('a.ss_laenderung_von'),
							$db->quoteName('a.z1_erstellt'),
							$db->quoteName('a.z1_erstellt_von'),
							$db->quoteName('a.z1_laenderung'),
							$db->quoteName('a.z1_laenderung_von'),
							$db->quoteName('a.z2_erstellt'),
							$db->quoteName('a.z2_erstellt_von'),
							$db->quoteName('a.z2_laenderung'),
							$db->quoteName('a.z2_laenderung_von'),
							$db->quoteName('a.an_erstellt'),
							$db->quoteName('a.an_erstellt_von'),
							$db->quoteName('a.an_laenderung'),
							$db->quoteName('a.an_laenderung_von'),
							$db->quoteName('a.bl_erstellt'),
							$db->quoteName('a.bl_erstellt_von'),
							$db->quoteName('a.bl_laenderung'),
							$db->quoteName('a.bl_laenderung_von'),
							$db->quoteName('a.fs_erstellt'),
							$db->quoteName('a.fs_erstellt_von'),
							$db->quoteName('a.fs_laenderung'),
							$db->quoteName('a.bereich_fahrzeug1'),
							$db->quoteName('a.bereich_fahrzeug2'),
							$db->quoteName('a.bereich_fahrzeug3'),
							$db->quoteName('a.bereich_fahrzeug4'),
							$db->quoteName('a.state'),
							$db->quoteName('a.checked_out'),
							$db->quoteName('a.checked_out_time'),
							$db->quoteName('a.access'),
							$db->quoteName('a.gesehen'),
							$db->quoteName('a.abluft_ok'),
							$db->quoteName('a.metakey'),
							$db->quoteName('a.metadesc'),
							$db->quoteName('a.metadata'),
							$db->quoteName('a.publish_up'),
							$db->quoteName('a.publish_down'),
							$db->quoteName('a.attribs'),
							$db->quoteName('a.version'),
							$db->quoteName('a.note'),
						]
					)
				)
					->select(
						[
							$db->quoteName('sff.name', 'sff_name'),
							$db->quoteName('sfs.name', 'sfs_name'),
							$db->quoteName('sfan.name', 'sfan_name'),
							$db->quoteName('fs.name', 'fs_erstellt_von_name'),
							$db->quoteName('fs2.name', 'fs_laenderung_von_name'),
							$db->quoteName('ss.name', 'ss_erstellt_von_name'),
							$db->quoteName('ss2.name', 'ss_laenderung_von_name'),
							$db->quoteName('z1.name', 'z1_erstellt_von_name'),
							$db->quoteName('z12.name', 'z1_laenderung_von_name'),
							$db->quoteName('z2.name', 'z2_erstellt_von_name'),
							$db->quoteName('z22.name', 'z2_laenderung_von_name'),
							$db->quoteName('an.name', 'an_erstellt_von_name'),
							$db->quoteName('an.name', 'an_laenderung_von_name'),
							$db->quoteName('bl.name', 'bl_erstellt_von_name'),
							$db->quoteName('bl2.name', 'bl_laenderung_von_name'),
						]
					)
					->from($db->quoteName('#__tagebuch', 'a'))
					->join('LEFT', $db->quoteName('#__users', 'sff'), $db->quoteName('sff.id') . ' = ' . $db->quoteName('a.sff'))
					->join('LEFT', $db->quoteName('#__users', 'sfs'), $db->quoteName('sfs.id') . ' = ' . $db->quoteName('a.sfs'))
					->join('LEFT', $db->quoteName('#__users', 'sfan'), $db->quoteName('sfan.id') . ' = ' . $db->quoteName('a.sfan'))
					->join('LEFT', $db->quoteName('#__users', 'fs'), $db->quoteName('fs.id') . ' = ' . $db->quoteName('a.fs_erstellt_von'))
					->join('LEFT', $db->quoteName('#__users', 'fs2'), $db->quoteName('fs2.id') . ' = ' . $db->quoteName('a.fs_laenderung_von'))
					->join('LEFT', $db->quoteName('#__users', 'ss'), $db->quoteName('ss.id') . ' = ' . $db->quoteName('a.ss_erstellt_von'))
					->join('LEFT', $db->quoteName('#__users', 'ss2'), $db->quoteName('ss2.id') . ' = ' . $db->quoteName('a.ss_laenderung_von'))
					->join('LEFT', $db->quoteName('#__users', 'z1'), $db->quoteName('z1.id') . ' = ' . $db->quoteName('a.z1_erstellt_von'))
					->join('LEFT', $db->quoteName('#__users', 'z12'), $db->quoteName('z12.id') . ' = ' . $db->quoteName('a.z1_laenderung_von'))
					->join('LEFT', $db->quoteName('#__users', 'z2'), $db->quoteName('z2.id') . ' = ' . $db->quoteName('a.z2_erstellt_von'))
					->join('LEFT', $db->quoteName('#__users', 'z22'), $db->quoteName('z22.id') . ' = ' . $db->quoteName('a.z2_laenderung_von'))
					->join('LEFT', $db->quoteName('#__users', 'an'), $db->quoteName('an.id') . ' = ' . $db->quoteName('a.an_erstellt_von'))
					->join('LEFT', $db->quoteName('#__users', 'an2'), $db->quoteName('an2.id') . ' = ' . $db->quoteName('a.an_laenderung_von'))
					->join('LEFT', $db->quoteName('#__users', 'bl'), $db->quoteName('bl.id') . ' = ' . $db->quoteName('a.bl_erstellt_von'))
					->join('LEFT', $db->quoteName('#__users', 'bl2'), $db->quoteName('bl2.id') . ' = ' . $db->quoteName('a.bl_laenderung_von'))

					->where(
						[
							$db->quoteName('a.id') . ' = :pk',
						]
					)
					->bind(':pk', $pk, ParameterType::INTEGER);


				$db->setQuery($query);

				$data = $db->loadObject();

				if (empty($data))
				{
					throw new \Exception(Text::_('COM_TAGEBUCH_ERROR_REPORT_NOT_FOUND'), 404);
				}

				// Convert parameter fields to objects.
				$registry = new Registry($data->attribs);

				$data->params = clone $this->getState('params');
				$data->params->merge($registry);

				// Set default all access-edit vars to false
				$editStandardFalse = new Registry(
					'{
						"access-edit":false,
						"access-edit-fs":false,
						"access-edit-ss":false,
						"access-edit-an":false,
						"access-edit-bl":false,
						"access-edit-z1":false,
						"access-edit-z2":false,
						"access-edit-luft":false,
						"access-edit-vefb":false
					}'
				);
				$data->params->merge($editStandardFalse);

				$data->metadata = new Registry($data->metadata);

				// Nur angemeldete User dürfen Ändern/Neu Anlegen
				if (!$user->get('guest'))
				{
					$userId = $user->get('id');
					$asset = 'com_tagebuch.report.' . $data->id;


					// Check general edit permission first.
					if ($user->authorise('core.edit', $asset))
					{
						$data->params->set('access-edit', true);
						$data->params->set('access-add-fs', true);
						$data->params->set('access-add-ss', true);
						$data->params->set('access-add-an', true);
						$data->params->set('access-add-z2', true);
					}

					// Now check if edit.own is available.
					if (!empty($userId) && $user->authorise('core.edit.own', $asset))
					{
						// Check for a valid user and that they are the owner.
						if (($userId == $data->sff) || ($userId == $data->fs_erstellt_von) || ($userId == $data->fs_laenderung_von) || ($data->fs_erstellt_von == null) )
						{
							$data->params->set('access-edit-fs', true);
						}
						if (($userId == $data->sfs) || ($userId == $data->ss_erstellt_von) || ($userId == $data->ss_laenderung_von) || ($data->ss_erstellt_von == null) )
						{
							$data->params->set('access-edit-ss', true);
						}
						if (($userId == $data->sfan) || ($userId == $data->an_erstellt_von) || ($userId == $data->an_laenderung_von) || ($data->an_erstellt_von == null) )
						{
							$data->params->set('access-edit-an', true);
						}
						if ( ($userId == $data->z1_erstellt_von) || ($userId == $data->z1_laenderung_von) )
						{
							$data->params->set('access-edit-z1', true);
						}
						if ( ($userId == $data->z2_erstellt_von) || ($userId == $data->z2_laenderung_von)  || ($data->z2_erstellt_von == null) )
						{
							$data->params->set('access-edit-z2', true);
						}
					}

					//Access Special when Verantwortlicher LUFT
					if ($userAccess->isLuft){
						$data->params->set('access-edit-z1', true);
						$data->params->set('access-add-z1', true);
						$data->params->set('access-edit_lu', true);
					}

					//Access Special when Verantwortlicher für EfB
					if ($userAccess->isVEfB){
						$data->params->set('access-edit-z1', true);
						$data->params->set('access-add-z1', true);
						$data->params->set('access-edit_lu', true);
						$data->params->set('access-edit_vefb', true);
					}

					//Access Special when Betriebsleitung
					if ($userAccess->isBL){
						$data->params->set('access-edit-fs', true);
						$data->params->set('access-edit-ss', true);
						$data->params->set('access-edit-an', true);
						$data->params->set('access-edit-z2', true);
						$data->params->set('access-edit-z1', true);
						$data->params->set('access-add-z1', true);
						$data->params->set('access-edit_lu', true);
						$data->params->set('access-edit_vefb', true);
						$data->params->set('access-edit-bl', true);
						$data->params->set('access-add-bl', true);
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
					$groups = $user->getAuthorisedViewLevels();

					$data->params->set('access-view', in_array($data->access, $groups));

				}

				$jdate = new Date($data->datum);

				$data->params->set('editlink', RouteHelper::getEditRoute($data->id,$jdate->format('Y-m-d')));

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

		$this->getNavigationItems($this->_item[$pk]->id);

		return $this->_item[$pk];
	}

	/**
	 * Method to get the Navigation Items.
	 *
	 * @param   integer  $pk  The id of the report.
	 *
	 * @return  boolean  Menu item data object loaded.
	 * @since 1.0
	 */
	protected function getNavigationItems($pk)
	{

		$Navigation = null;
		$skhelper = new TagebuchHelper;
		$this->_navigationItems = $skhelper->getNextPreview($pk);

		return true;
	}

	/**
	 * Method to get the NavigationItems.
	 *
	 * @return \stdClass
	 * @since 1.0
	 */
	public function getNavigationClass()
	{

		return $this->_navigationItems;
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
