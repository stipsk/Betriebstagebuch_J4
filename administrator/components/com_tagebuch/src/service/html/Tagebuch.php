<?php
/**
 * @package     Tagebuch.Administrator
 * @subpackage  com_tagebuch
 *
 * @copyright   Copyright (C) 2021 Stephan Knauer. All rights reserved.
 * @license     GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace SK\Component\Tagebuch\Administrator\Service\Html;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/**
 * Tagebuch HTML class.
 * Call HTML::_('Tagebuch.function' , $foo , $bar ... );
 *
 * @since  2.5
 */
class Tagebuch
{
	/**
	 * COPY from com_banners!
	 * Display a batch widget for the client selector.
	 *
	 * @return  string  The necessary HTML for the widget.
	 *
	 * @since   2.5
	 */
	public function clients()
	{
		// Create the batch selector to change the client on a selection list.
		return implode(
			"\n",
			array(
				'<label id="batch-client-lbl" for="batch-client-id">',
				Text::_('COM_BANNERS_BATCH_CLIENT_LABEL'),
				'</label>',
				'<select class="custom-select" name="batch[client_id]" id="batch-client-id">',
				'<option value="">' . Text::_('COM_BANNERS_BATCH_CLIENT_NOCHANGE') . '</option>',
				'<option value="0">' . Text::_('COM_BANNERS_NO_CLIENT') . '</option>',
				HTMLHelper::_('select.options', static::clientlist(), 'value', 'text'),
				'</select>'
			)
		);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.6
	 */
	public function clientlist()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select(
				[
					$db->quoteName('id', 'value'),
					$db->quoteName('name', 'text'),
				]
			)
			->from($db->quoteName('#__banner_clients'))
			->order($db->quoteName('name'));

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

		return $options;
	}

	/**
	 * Returns a pinned state on a grid
	 *
	 * @param   integer  $value     The state value.
	 * @param   integer  $i         The row index
	 * @param   boolean  $enabled   An optional setting for access control on the action.
	 * @param   string   $checkbox  An optional prefix for checkboxes.
	 *
	 * @return  string   The Html code
	 *
	 * @see     HTMLHelperJGrid::state
	 * @since   2.5.5
	 */
	public function pinned($value, $i, $enabled = true, $checkbox = 'cb')
	{
		$states = array(
			1 => array(
				'sticky_unpublish',
				'COM_BANNERS_BANNERS_PINNED',
				'COM_BANNERS_BANNERS_HTML_PIN_BANNER',
				'COM_BANNERS_BANNERS_PINNED',
				true,
				'publish',
				'publish'
			),
			0 => array(
				'sticky_publish',
				'COM_BANNERS_BANNERS_UNPINNED',
				'COM_BANNERS_BANNERS_HTML_UNPIN_BANNER',
				'COM_BANNERS_BANNERS_UNPINNED',
				true,
				'unpublish',
				'unpublish'
			),
		);

		return HTMLHelper::_('jgrid.state', $states, $value, $i, 'banners.', $enabled, true, $checkbox);
	}
}