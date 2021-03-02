<?php
/**
 * @package     Tagebuch.Administrator
 * @subpackage  com_tagebuch
 *
 * @copyright   Copyright (C) 2021 Stephan Knauer. All rights reserved.
 * @license     GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace SK\Component\Tagebuch\Administrator\Extension;

\defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Association\AssociationServiceInterface;
use Joomla\CMS\Association\AssociationServiceTrait;
use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Component\Router\RouterServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Factory;
use Joomla\CMS\Fields\FieldsServiceInterface;
use Joomla\CMS\Form\Form;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Tag\TagServiceInterface;
use Joomla\CMS\Tag\TagServiceTrait;
use SK\Component\Tagebuch\Administrator\Service\HTML\AdministratorService;
use SK\Component\Tagebuch\Administrator\Service\HTML\Icon;
use Psr\Container\ContainerInterface;

/**
 * Component class for com_tagebuch
 *
 * @since  4.0.0
 */
class TagebuchComponent extends MVCComponent implements
	BootableExtensionInterface, CategoryServiceInterface, FieldsServiceInterface, AssociationServiceInterface, RouterServiceInterface,
	TagServiceInterface
{
	use AssociationServiceTrait;
	use HTMLRegistryAwareTrait;
	use RouterServiceTrait;
	use CategoryServiceTrait, TagServiceTrait {
		CategoryServiceTrait::getTableNameForSection insteadof TagServiceTrait;
		CategoryServiceTrait::getStateColumnForSection insteadof TagServiceTrait;
	}

	/**
	 * Booting the extension. This is the function to set up the environment of the extension like
	 * registering new class loaders, etc.
	 *
	 * If required, some initial set up can be done from services of the container, eg.
	 * registering HTML services.
	 *
	 * @param   ContainerInterface  $container  The container
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	public function boot(ContainerInterface $container)
	{
		//$this->getRegistry()->register('tagebuchadministrator', new AdministratorService);
		$this->getRegistry()->register('tagebuchicon', new Icon($container->get(SiteApplication::class)));
	}

	/**
	 * Returns a valid section for the given section. If it is not valid then null
	 * is returned.
	 *
	 * @param   string  $section  The section to get the mapping for
	 * @param   object  $item     The item
	 *
	 * @return  string|null  The new section
	 *
	 * @since   4.0.0
	 */
	public function validateSection($section, $item = null)
	{
		if (Factory::getApplication()->isClient('site') && $section == 'tagebuch' && $item instanceof Form)
		{
			// The tagebuch form needs to be the datum section
			$section = 'datum';
		}

		/*if (Factory::getApplication()->isClient('site') && ($section === 'category' || $section === 'form'))
		{
			// The contact form needs to be the mail section
			$section = 'contact';
		}*/

		if ($section !== 'mail' && $section !== 'tagebuch')
		{
			// We don't know other sections
			return null;
		}

		return $section;
	}

	/**
	 * Returns valid contexts
	 *
	 * @return  array
	 *
	 * @since   4.0.0
	 */
	public function getContexts(): array
	{
		Factory::getLanguage()->load('com_tagebuch', JPATH_ADMINISTRATOR);

		$contexts = array(
			'com_tagebuch.tagebuch'    => Text::_('COM_TAGEBUCH_FIELDS_CONTEXT_TAGEBUCH'),
			'com_tagebuch.datum'       => Text::_('COM_TAGEBUCH_FIELDS_CONTEXT_DATUM'),
			'com_tagebuch.categories' => Text::_('JCATEGORY')
		);

		return $contexts;
	}

	/**
	 * Returns the table for the count items functions for the given section.
	 *
	 * @param   string  $section  The section
	 *
	 * @return  string|null
	 *
	 * @since   4.0.0
	 */
	protected function getTableNameForSection(string $section = null)
	{
		return ($section === 'category' ? 'categories' : 'tagebuch');
	}

	/**
	 * Returns the state column for the count items functions for the given section.
	 *
	 * @param   string  $section  The section
	 *
	 * @return  string|null
	 *
	 * @since   4.0.0
	 */
	protected function getStateColumnForSection(string $section = null)
	{
		return 'published';
	}
}
