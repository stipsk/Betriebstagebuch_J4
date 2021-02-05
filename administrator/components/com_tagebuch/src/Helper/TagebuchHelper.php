<?php
/**
 * @package     Tagebuch.Administrator
 * @subpackage  com_tagebuch
 *
 * @copyright   Copyright (C) 2021 Stephan Knauer. All rights reserved.
 * @license     GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace SK\Component\Tagebuch\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * Tagebuch component helper.
 *
 * @since  4.0
 */
class TagebuchHelper
{
	public static function getTagebuchTitle($id)
	{
		if (empty($id))
		{
			// throw an error or ...
			return false;
		}
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('title');
		$query->from('#__tagebuch');
		$query->where('id = ' . $id);
		$db->setQuery($query);
		return $db->loadObject();
	}

	/**
	 * Get the last Report.
	 *
	 * @param   integer  $id        The route of the content item.
	 **
	 * @return  integer  The last report id.
	 *
	 * @since   1.5
	 */

	public static function getLastId($id = null) : int
	{

		if (!$id) {$id = 2675;}
		return ($id);
	}
}