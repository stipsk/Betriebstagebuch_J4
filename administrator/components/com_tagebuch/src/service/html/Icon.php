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
use Joomla\Console\Application;
use SK\Component\Tagebuch\Site\Helper\RouteHelper;
use Joomla\Registry\Registry;

/**
 * Tagebuch Component HTML Helper
 *
 * @since  4.0.0
 */
class Icon
{
	/**
	 * The application
	 *
	 * @var    CMSApplication
	 *
	 * @since  4.0.0
	 */
	//private $application;

	/**
	 * Service constructor
	 *
	 * @param   CMSApplication  $application  The application
	 *
	 * @since   4.0.0
	 */
	public function __construct(CMSApplication $application)
	{
		//$this->application = $application;
	}

	/**
	 * Method to generate a link to the print item page for the given report
	 *
	 * @param   object    $category  The category information
	 * @param   Registry  $params    The item parameters
	 * @param   array     $attribs   Optional attributes for the link
	 *
	 * @return  string  The HTML markup for the create item link
	 *
	 * @since  4.0.0
	 */
	/*public static function create($category, $params, $attribs = array())
	{
		$uri = Uri::getInstance();

		$url = 'index.php?option=com_contact&task=contact.add&return=' . base64_encode($uri) . '&id=0&catid=' . $category->id;

		$text = LayoutHelper::render('joomla.content.icons.create', array('params' => $params, 'legacy' => false));

		// Add the button classes to the attribs array
		if (isset($attribs['class']))
		{
			$attribs['class'] .= ' btn btn-primary';
		}
		else
		{
			$attribs['class'] = 'btn btn-primary';
		}

		$button = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		$output = '<span class="hasTooltip" title="' . HTMLHelper::_('tooltipText', 'COM_CONTACT_CREATE_CONTACT') . '">' . $button . '</span>';

		return $output;
	}*/

	/**
	 * Display an edit icon for the Report.
	 *
	 * This icon will not display in a popup window, nor if the contact is trashed.
	 * Edit access checks must be performed in the calling code.
	 *
	 * @param   object    $tagebuch   The tagebuch information
	 * @param   Registry  $params   The item parameters
	 * @param   array     $attribs  Optional attributes for the link
	 * @param   boolean   $legacy   True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string   The HTML for the contact edit icon.
	 *
	 * @since   4.0.0
	 */
	public static function edit($tagebuch, $params, $attribs = array(), $legacy = false)
	{
		$app = Factory::getApplication();
		//$user = $app->getIdentity();//Factory::getUser();
		$uri  = Uri::getInstance();

		// Ignore if in a popup window.
		if ($params && $params->get('popup'))
		{
			return '';
		}

		// Ignore if the state is negative (trashed).
		if ($tagebuch->state < 0)
		{
			return '';
		}

		// Show checked_out icon if the tagebuch is checked out by a different user
		if (property_exists($tagebuch, 'checked_out')
			&& property_exists($tagebuch, 'checked_out_time')
			&& !is_null($tagebuch->checked_out)
			&& $tagebuch->checked_out !== $app->getIdentity())
		{
			$checkoutUser = $app->getIdentity($tagebuch->checked_out);
			$date         = HTMLHelper::_('date', $tagebuch->checked_out_time);
			$tooltip      = Text::sprintf('COM_TAGEBUCH_CHECKED_OUT_BY', $checkoutUser->name)
				. ' <br> ' . $date;

			$text = LayoutHelper::render('joomla.content.icons.edit_lock', array('tagebuch' => $tagebuch, 'tooltip' => $tooltip, 'legacy' => $legacy));

			$attribs['aria-describedby'] = 'edittagebuch-' . (int) $tagebuch->id;
			$output = HTMLHelper::_('link', '#', $text, $attribs);

			return $output;
		}

		$tagebuchUrl = RouteHelper::getReportRoute($tagebuch->slug, null, null);
		$url        = $tagebuchUrl . '&task=report.edit&id=' . $tagebuch->id . '&return=' . base64_encode($uri);

		if ((int) $tagebuch->state === 0)
		{
			$tooltip = Text::_('COM_TAGEBUCH_EDIT_UNPUBLISHED_TAGEBUCH');
		}
		else
		{
			$tooltip = Text::_('COM_TAGEBUCH_EDIT_PUBLISHED_TAGEBUCH');
		}

		$nowDate = strtotime(Factory::getDate());
		$icon    = $tagebuch->state ? 'edit' : 'eye-slash';

		if (($tagebuch->publish_up !== null && strtotime($tagebuch->publish_up) > $nowDate)
			|| ($tagebuch->publish_down !== null && strtotime($tagebuch->publish_down) < $nowDate
			&& $tagebuch->publish_down !== Factory::getDbo()->getNullDate()))
		{
			$icon = 'eye-slash';
		}

		$aria_described = 'edittagebuch-' . (int) $tagebuch->id;

		$text = '<span class="icon-' . $icon . '" aria-hidden="true"></span>';
		$text .= Text::_('JGLOBAL_EDIT');
		$text .= '<div role="tooltip" id="' . $aria_described . '">' . $tooltip . '</div>';

		$attribs['aria-describedby'] = $aria_described;
		$output = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		return $output;
	}
}
