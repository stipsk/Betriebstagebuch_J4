<?php
/**
 * @package     Tagebuch.Administrator
 * @subpackage  com_tagebuch
 *
 * @copyright   Copyright (C) 2021 Stephan Knauer. All rights reserved.
 * @license     GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace SK\Component\Tagebuch\Administrator\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
//use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;

/**
 * Banners component helper.
 *
 * @since  1.6
 */
class FahrzeugeHelper extends ComponentHelper
{

	/**
	 * Get client list in text/value format for a select field
	 *
	 * @return  array
	 */
	public static function getFahrzeugeOptions()
	{
		$options = array();

		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select(
				[
					$db->quoteName('a.id', 'value'),
					$db->quoteName('b.title', 'text'),
				]
			)
			->from($db->quoteName('#__tagebuch_masch', 'a'))
			->join('LEFT', $db->quoteName('#__wartung_maschine') . ' AS b ON (b.id = a.wid)' )
			->order($db->quoteName('text'));

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (\RuntimeException $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		array_unshift($options, HTMLHelper::_('select.option', '0', Text::_('COM_TAGEBUCH_FAHRZEUGE_NO_CLIENT')));

		return $options;
	}

}
