<?php
/**
 * @package     Tagebuch.Administrator
 * @subpackage  com_tagebuch
 *
 * @copyright   Copyright (C) 2021 Stephan Knauer. All rights reserved.
 * @license     GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace SK\Component\Tagebuch\Administrator\Service\HTML;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use SK\Component\Tagebuch\Site\Helper\RouteHelper;
use Joomla\Registry\Registry;

/**
 * Tagebuch HTML class.
 * Call HTMLHelper::_('Tagebuch.calendarnav' , $foo , $bar ... );
 *
 * @since  4.0
 */
class Calendarnav
{
	/**
	 * The application
	 *
	 * @var    CMSApplication
	 *
	 * @since  4.0.0
	 */
	private $application;

	/**
	 * Service constructor
	 *
	 * @param   CMSApplication  $application  The application
	 *
	 * @since   4.0.0
	 */
	public function __construct(CMSApplication $application)
	{
		$this->application = $application;
	}

	/**
	 * Displays a calendar control field
	 *
	 * @param   string  $value    The date value
	 * @param   string  $name     The name of the text field
	 * @param   string  $id       The id of the text field
	 * @param   string  $format   The date format
	 * @param   mixed   $attribs  Additional HTML attributes
	 *                            The array can have the following keys:
	 *                            readonly      Sets the readonly parameter for the input tag
	 *                            disabled      Sets the disabled parameter for the input tag
	 *                            autofocus     Sets the autofocus parameter for the input tag
	 *                            autocomplete  Sets the autocomplete parameter for the input tag
	 *                            filter        Sets the filter for the input tag
	 *
	 * @return  string  HTML markup for a calendar field
	 *
	 * @since   1.5
	 *
	 */
	public static function calendar($value, $name, $id, $format = '%Y-%m-%d', $attribs = array())
	{
		$tag       = Factory::getLanguage()->getTag();
		$calendar  = Factory::getLanguage()->getCalendar();
		$direction = strtolower(Factory::getApplication()->getDocument()->getDirection());

		// Get the appropriate file for the current language date helper
		$helperPath = 'system/fields/calendar-locales/date/gregorian/date-helper.min.js';

		if (!empty($calendar) && is_dir(JPATH_ROOT . '/media/system/js/fields/calendar-locales/date/' . strtolower($calendar)))
		{
			$helperPath = 'system/fields/calendar-locales/date/' . strtolower($calendar) . '/date-helper.min.js';
		}

		// Get the appropriate locale file for the current language
		$localesPath = 'system/fields/calendar-locales/en.js';

		if (is_file(JPATH_ROOT . '/media/system/js/fields/calendar-locales/' . strtolower($tag) . '.js'))
		{
			$localesPath = 'system/fields/calendar-locales/' . strtolower($tag) . '.js';
		}
		elseif (is_file(JPATH_ROOT . '/media/system/js/fields/calendar-locales/' . $tag . '.js'))
		{
			$localesPath = 'system/fields/calendar-locales/' . $tag . '.js';
		}
		elseif (is_file(JPATH_ROOT . '/media/system/js/fields/calendar-locales/' . strtolower(substr($tag, 0, -3)) . '.js'))
		{
			$localesPath = 'system/fields/calendar-locales/' . strtolower(substr($tag, 0, -3)) . '.js';
		}

		$readonly     = isset($attribs['readonly']) && $attribs['readonly'] === 'readonly';
		$disabled     = isset($attribs['disabled']) && $attribs['disabled'] === 'disabled';
		$autocomplete = isset($attribs['autocomplete']) && $attribs['autocomplete'] === '';
		$autofocus    = isset($attribs['autofocus']) && $attribs['autofocus'] === '';
		$required     = isset($attribs['required']) && $attribs['required'] === '';
		$filter       = isset($attribs['filter']) && $attribs['filter'] === '';
		$todayBtn     = $attribs['todayBtn'] ?? true;
		$weekNumbers  = $attribs['weekNumbers'] ?? true;
		$showTime     = $attribs['showTime'] ?? false;
		$fillTable    = $attribs['fillTable'] ?? true;
		$timeFormat   = $attribs['timeFormat'] ?? 24;
		$singleHeader = $attribs['singleHeader'] ?? false;
		$hint         = $attribs['placeholder'] ?? '';
		$class        = $attribs['class'] ?? '';
		$onchange     = $attribs['onChange'] ?? '';
		$minYear      = $attribs['minYear'] ?? null;
		$maxYear      = $attribs['maxYear'] ?? null;

		$showTime     = ($showTime) ? "1" : "0";
		$todayBtn     = ($todayBtn) ? "1" : "0";
		$weekNumbers  = ($weekNumbers) ? "1" : "0";
		$fillTable    = ($fillTable) ? "1" : "0";
		$singleHeader = ($singleHeader) ? "1" : "0";

		// Format value when not nulldate ('0000-00-00 00:00:00'), otherwise blank it as it would result in 1970-01-01.
		if ($value && $value !== Factory::getDbo()->getNullDate() && strtotime($value) !== false)
		{
			$tz = date_default_timezone_get();
			date_default_timezone_set('UTC');
			$inputvalue = strftime($format, strtotime($value));
			date_default_timezone_set($tz);
		}
		else
		{
			$inputvalue = '';
		}

		$data = array(
			'id'             => $id,
			'name'           => $name,
			'class'          => $class,
			'value'          => $inputvalue,
			'format'         => $format,
			'filter'         => $filter,
			'required'       => $required,
			'readonly'       => $readonly,
			'disabled'       => $disabled,
			'hint'           => $hint,
			'autofocus'      => $autofocus,
			'autocomplete'   => $autocomplete,
			'todaybutton'    => $todayBtn,
			'weeknumbers'    => $weekNumbers,
			'showtime'       => $showTime,
			'filltable'      => $fillTable,
			'timeformat'     => $timeFormat,
			'singleheader'   => $singleHeader,
			'tag'            => $tag,
			'helperPath'     => $helperPath,
			'localesPath'    => $localesPath,
			'direction'      => $direction,
			'onchange'       => $onchange,
			'minYear'        => $minYear,
			'maxYear'        => $maxYear,
			'dataAttribute'  => '',
			'dataAttributes' => '',
		);
		$basePath = JPATH_ADMINISTRATOR .'/components/com_tagebuch/layouts';
		return LayoutHelper::render('skcalendar', $data, $basePath, null);
	}
}
