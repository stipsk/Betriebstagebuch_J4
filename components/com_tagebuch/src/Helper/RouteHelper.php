<?php
/**
 * @package     Tagebuch.Site
 * @subpackage  com_tagebuch
 *
 * @copyright   Copyright (C) 2021 Stephan Knauer. All rights reserved.
 * @license     GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace SK\Component\Tagebuch\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryNode;
use Joomla\CMS\Language\Multilanguage;

/**
 * Tagebuch Component Route Helper.
 *
 * @since  1.5
 */
abstract class RouteHelper
{
	/**
	 * Get the edit route.
	 *
	 * @param   integer  $id        The route of the content item.
	 * @param   integer  $language  The language code.
	 * @param   string   $layout    The layout value.
	 **
	 * @return  string  The report route.
	 *
	 * @since   1.5
	 */
	public static function getReportRoute($id, $slug, $language = 0, $layout = null)
	{
		// Create the link
		$link = 'index.php?option=com_tagebuch&view=report&id=' . $id . '&slug=' . $slug;;


		if ($language && $language !== '*' && Multilanguage::isEnabled())
		{
			$link .= '&lang=' . $language;
		}

		if ($layout)
		{
			$link .= '&layout=' . $layout;
		}

		return $link;
	}


	 /**
	 * @return  string  The report route.
	 *
	 * @since   1.5
	 */
	public static function getEditRoute($id, $slug, $language = 0, $layout = null)
	{
		// Create the link
		$link = 'index.php?option=com_tagebuch&task=report.edit&id=' . $id . '&slug=' . $slug;
		/**
		 * @todo Für Neuer Tag evtl. andere Route notwendig!
		 */
		if ($language && $language !== '*' && Multilanguage::isEnabled())
		{
			$link .= '&lang=' . $language;
		}

		if ($layout)
		{
			$link .= '&layout=' . $layout;
		}

		return $link;
	}


}
