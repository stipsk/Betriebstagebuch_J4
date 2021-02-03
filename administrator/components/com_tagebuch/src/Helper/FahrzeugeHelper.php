<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace SK\Component\Tagebuch\Administrator\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\ParameterType;

/**
 * Banners component helper.
 *
 * @since  1.6
 */
class FahrzeugeHelper extends ContentHelper
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
			->from($db->quoteName('#__banner_clients', 'a'))
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
