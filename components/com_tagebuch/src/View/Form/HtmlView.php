<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace SK\Component\Tagebuch\Site\View\Form;

\defined('_JEXEC') or die;

use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use SK\Component\Tagebuch\Site\Helper\RouteHelper;
use SK\Component\Tagebuch\Administrator\Helper\TagebuchHelper;


/**
 * HTML Edit View class for the Tagebuch component
 *
 * @since  1.5
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The Form object
	 *
	 * @var  \Joomla\CMS\Form\Form
	 */
	protected $form;

	/**
	 * The item being created
	 *
	 * @var  \stdClass
	 */
	protected $item;

	/**
	 * The page to return to after the article is submitted
	 *
	 * @var  string
	 */
	protected $return_page = '';

	/**
	 * The model state
	 *
	 * @var  \Joomla\CMS\Object\CMSObject
	 */
	protected $state;

	/**
	 * The page parameters
	 *
	 * @var    \Joomla\Registry\Registry|null
	 *
	 * @since  4.0.0
	 */
	protected $params = null;

	/**
	 * The page class suffix
	 *
	 * @var    string
	 *
	 * @since  4.0.0
	 */
	protected $pageclass_sfx = '';

	/**
	 * The user object
	 *
	 * @var \Joomla\CMS\User\User
	 *
	 * @since  4.0.0
	 */
	protected $user = null;

	/**
	 * Should we show a captcha form for the submission of the article?
	 *
	 * @var    boolean
	 *
	 * @since  3.7.0
	 */
	protected $captchaEnabled = false;

	/**
	 * Should we show Save As Copy button?
	 *
	 * @var    boolean
	 * @since  4.1.0
	 */
	protected $showSaveAsCopy = false;


	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 * @throws \Exception
	 * @since 1.0
	 */
	public function display($tpl = null)
	{

		$app        = Factory::getApplication();
		$user       = $app->getIdentity();

		$this->item  = $this->get('Item');
		$this->form  = $this->get('form');
		$this->form->bind($this->item);
		$this->print = $app->input->getBool('print', false);
		$this->state = $this->get('State');
		$this->user  = $user;
		$this->navigationClass = $this->get('NavigationClass');


		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		// Create a shortcut for $item.
		$item            = $this->item;
		$item->tagLayout = new FileLayout('joomla.tagebuch.tags');

		// Add router helpers.
		$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;


		// Merge article params. If this is single-article view, menu params override article params
		// Otherwise, article params override menu item params
		$this->params = $this->state->get('params');
		$active       = $app->getMenu()->getActive();
		$temp         = clone $this->params;

		// Check to see which parameters should take priority
		if ($active)
		{
			$currentLink = $active->link;

			// Load layout from active query (in case it is an alternative menu item)
			if (isset($active->query['layout']))
			{
				$this->setLayout($active->query['layout']);
			}

			// $item->params are the Report params, $temp are the menu item params
			// Merge so that the menu item params take priority
			$item->params->merge($temp);

		}
		else
		{
			// Merge so that Report params take priority
			$temp->merge($item->params);
			$item->params = $temp;

			// Check for alternative layouts (since we are not in a single-Report menu item)
			// Single-Report menu item layout takes priority over alt layout for an article
			if ($layout = $item->params->get('article_layout'))
			{
				$this->setLayout($layout);
			}
		}

		$offset = $this->state->get('list.offset');

		// Check the view access to the article (the model has already computed the values).
		if ($item->params->get('access-edit') == false && ($item->params->get('show_noauth', '0') == '0'))
		{
			$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->setHeader('status', 403, true);

			return;
		}

		/**
		 * Check for no 'access-view',
		 * - Redirect guest users to login
		 * - Deny access to logged users with 403 code
		 * NOTE: we do not recheck for no access-view + show_noauth disabled ... since it was checked above
		 */
		if ($item->params->get('access-view') == false)
		{
			if ($this->user->get('guest'))
			{
				$return = base64_encode(Uri::getInstance());
				$login_url_with_return = Route::_('index.php?option=com_users&view=login&return=' . $return);
				$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'notice');
				$app->redirect($login_url_with_return, 403);
			}
			else
			{
				$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
				$app->setHeader('status', 403, true);

				return;
			}
		}

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->item->params->get('pageclass_sfx', ''), ENT_COMPAT, 'UTF-8');

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document.
	 *
	 * @return  void
	 * @throws \Exception
	 * @since 1.0
	 */
	protected function _prepareDocument()
	{
		$app     = Factory::getApplication();
		$menus   = $app->getMenu();
		$pathway = $app->getPathway();
		$title   = null;

		/**
		 * Because the application sets a default page title,
		 * we need to get it from the menu item itself
		 */
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', Text::_('COM_TAGEBUCH_REPORT_PAGETITLE_DATE').HTMLHelper::_('date' , $this->item->datum,'l,  d.m.Y'));
		}
		else
		{
			$this->params->def('page_heading', Text::_('JGLOBAL_ARTICLES'));
		}

		$title = $this->params->get('page_title', '');

		$id = (int) @$menu->query['id'];

		// If the menu item does not concern this article
		if ($menu && (!isset($menu->query['option']) || $menu->query['option'] !== 'com_tagebuch' || $menu->query['view'] !== 'report'
			|| $id != $this->item->id))
		{
			// If a browser page title is defined, use that, then fall back to the article title if set, then fall back to the page_title option
			$title = $this->item->params->get('article_page_title', HTMLHelper::_('date' , $this->item->datum,'l,  d.m.Y'));

			$path     = array(array('title' => $this->item->datum, 'link' => ''));

			$path = array_reverse($path);

			foreach ($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}
		}

		// Check for empty title and add site name if param is set
		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		if (empty($title))
		{
			$title = $this->item->title;
		}

		$this->document->setTitle($title);

		if ($this->item->metadesc)
		{
			$this->document->setDescription($this->item->metadesc);
		}
		elseif ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetaData('robots', $this->params->get('robots'));
		}

		if (($app->get('MetaAuthor') == '1') && (!empty($this->item->author)))
		{
			$author = $this->item->author;
			$this->document->setMetaData('author', $author);
		}

		if (!empty($this->item->metadata))
		{
			$mdata = $this->item->metadata->toArray();

			foreach ($mdata as $k => $v)
			{
				if ($v)
				{
					$this->document->setMetaData($k, $v);
				}
			}
		}

		// If there is a pagebreak heading or title, add it to the page title
		if (!empty($this->item->page_title))
		{
			$this->item->title = $this->item->datum . ' - ' . $this->item->page_title;
			$this->document->setTitle(
				$this->item->page_title . ' - ' . Text::sprintf('PLG_CONTENT_PAGEBREAK_PAGE_NUM', $this->state->get('list.offset') + 1)
			);
		}

		if ($this->print)
		{
			$this->document->setMetaData('robots', 'noindex, nofollow');
		}
	}
}
