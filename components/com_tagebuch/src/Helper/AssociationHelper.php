<?php
/**
 * @package     Tagebuch.Site
 * @subpackage  com_tagebuch
 *
 * @copyright   Copyright (C) 2021 Stephan Knauer. All rights reserved.
 * @license     GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace SK\Component\Tagebuch\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\Component\Categories\Administrator\Helper\CategoryAssociationHelper;

/**
 * Tagebuch Component Association Helper
 *
 * @since  3.0
 */
abstract class AssociationHelper extends CategoryAssociationHelper
{
	/**
	 * Method to get the associations for a given item
	 *
	 * @param   integer  $id    Id of the item
	 * @param   string   $view  Name of the view
	 *
	 * @return  array   Array of associations for the item
	 *
	 * @since  3.0
	 */
	public static function getAssociations($id = 0, $view = null)
	{
		$jinput = Factory::getApplication()->input;
		$view   = $view ?? $jinput->get('view');
		$id     = empty($id) ? $jinput->getInt('id') : $id;

		if ($view === 'tagebuch')
		{
			if ($id)
			{
				$associations = Associations::getAssociations('com_tagebuch', '#__tagebuch', 'com_tagebuch.item', $id);

				$return = array();

				foreach ($associations as $tag => $item)
				{
					$return[$tag] = RouteHelper::getTagebuchRoute($item->id, (int) $item->catid, $item->language);
				}

				return $return;
			}
		}

		if ($view === 'category' || $view === 'categories')
		{
			return self::getCategoryAssociations($id, 'com_tagebuch');
		}

		return array();

	}
}
