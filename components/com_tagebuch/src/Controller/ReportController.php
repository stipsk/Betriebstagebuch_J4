<?php
/** @noinspection PhpUnused
 * @package     Joomla.Site
 * @subpackage  com_content
 * @copyright   Stephan Knauer Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace SK\Component\Tagebuch\Site\Controller;

\defined('_JEXEC') or die;

use Exception;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Versioning\VersionableControllerTrait;
use Joomla\Utilities\ArrayHelper;
use phpseclib3\File\ASN1\Maps\AnotherName;

/**
 * Content article class.
 *
 * @since  1.6.0
 */
class ReportController extends FormController
{
	use VersionableControllerTrait;

	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $view_item = 'form';

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $view_list = 'categories';

	/**
	 * The URL edit variable.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $urlVar = 'a.id';


	/**
	 * Method to add a new record.
	 *
	 * @return  mixed  True if the record can be added, an error object if not.
	 *
	 * @since   1.6
	 */
	public function add()
	{
		if (!parent::add())
		{
			// Redirect to the return page.
			$this->setRedirect($this->getReturnPage());

			return false;
		}

		// Redirect to the edit screen.
		$this->setRedirect(
			Route::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_item . '&a_id=0'
				. $this->getRedirectToItemAppend(), false
			)
		);

		return true;
	}

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($data = array())
	{
		$user       = Factory::getUser();
		$categoryId = ArrayHelper::getValue($data, 'catid', $this->input->getInt('catid'), 'int');
		$allow      = null;

		if ($categoryId)
		{
			// If the category has been passed in the data or URL check it.
			$allow = $user->authorise('core.create', 'com_content.category.' . $categoryId);
		}

		if ($allow === null)
		{
			// In the absence of better information, revert to the component permissions.
			return parent::allowAdd();
		}
		else
		{
			return $allow;
		}
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key; default is id.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user = Factory::getUser();

		// Zero record (id:0), return component edit permission by calling parent controller method
		if (!$recordId)
		{
			return parent::allowEdit($data, $key);
		}

		// Check edit on the record asset (explicit or inherited)
		if ($user->authorise('core.edit', 'com_content.article.' . $recordId))
		{
			return true;
		}

		// Check edit own on the record asset (explicit or inherited)
		if ($user->authorise('core.edit.own', 'com_content.article.' . $recordId))
		{
			// Existing record already has an owner, get it
			$record = $this->getModel()->getItem($recordId);

			if (empty($record))
			{
				return false;
			}

			// Grant if current user is owner of the record
			return $user->get('id') == $record->created_by;
		}

		return false;
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @throws Exception
	 * @since   1.6
	 */
	public function cancel($key = 'a_id')
	{
		$result = parent::cancel($key);

		/** @var SiteApplication $app */
		$app = Factory::getApplication();

		// Load the parameters.
		$params = $app->getParams();

		$customCancelRedir = (bool) $params->get('custom_cancel_redirect');

		if ($customCancelRedir)
		{
			$cancelMenuitemId = (int) $params->get('cancel_redirect_menuitem');

			if ($cancelMenuitemId > 0)
			{
				$item = $app->getMenu()->getItem($cancelMenuitemId);
				$lang = '';

				if (Multilanguage::isEnabled())
				{
					$lang = !is_null($item) && $item->language != '*' ? '&lang=' . $item->language : '';
				}

				// Redirect to the user specified return page.
				$redirlink = $item->link . $lang . '&Itemid=' . $cancelMenuitemId;
			}
			else
			{
				// Redirect to the same article submission form (clean form).
				$redirlink = $app->getMenu()->getActive()->link . '&Itemid=' . $app->getMenu()->getActive()->id;
			}
		}
		else
		{
			$menuitemId = (int) $params->get('redirect_menuitem');

			if ($menuitemId > 0)
			{
				$lang = '';
				$item = $app->getMenu()->getItem($menuitemId);

				if (Multilanguage::isEnabled())
				{
					$lang = !is_null($item) && $item->language != '*' ? '&lang=' . $item->language : '';
				}

				// Redirect to the general (redirect_menuitem) user specified return page.
				$redirlink = $item->link . $lang . '&Itemid=' . $menuitemId;
			}
			else
			{
				// Redirect to the return page.
				$redirlink = $this->getReturnPage();
			}
		}

		$this->setRedirect(Route::_($redirlink, false));

		return $result;
	}

	/**
	 * Method to edit an existing record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key
	 * (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if access level check and checkout passes, false otherwise.
	 *
	 * @since   1.6
	 */
	public function edit($key = null, $urlVar = 'a_id')
	{
		$result = parent::edit($key, $urlVar);

		if (!$result)
		{
			$this->setRedirect(Route::_($this->getReturnPage(), false));
		}

		return $result;
	}

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.5
	 */
	public function getModel($name = 'Edit', $prefix = 'Site', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string	The arguments to append to the redirect URL.
	 *
	 * @since   1.6
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'a_id')
	{
		// Need to override the parent method completely.
		$tmpl   = $this->input->get('tmpl');

		$append = '';

		// Setup redirect info.
		if ($tmpl)
		{
			$append .= '&tmpl=' . $tmpl;
		}

		// TODO This is a bandaid, not a long term solution.
		/**
		 * if ($layout)
		 * {
		 *	$append .= '&layout=' . $layout;
		 * }
		 */

		$append .= '&layout=edit';

		if ($recordId)
		{
			$append .= '&' . $urlVar . '=' . $recordId;
		}

		$itemId = $this->input->getInt('Itemid');
		$return = $this->getReturnPage();
		$catId  = $this->input->getInt('catid');

		if ($itemId)
		{
			$append .= '&Itemid=' . $itemId;
		}

		if ($catId)
		{
			$append .= '&catid=' . $catId;
		}

		if ($return)
		{
			$append .= '&return=' . base64_encode($return);
		}

		return $append;
	}

	/**
	 * Get the return URL.
	 *
	 * If a "return" variable has been passed in the request
	 *
	 * @return  string	The return URL.
	 *
	 * @since   1.6
	 */
	protected function getReturnPage()
	{
		$return = $this->input->get('return', null, 'base64');

		if (empty($return) || !Uri::isInternal(base64_decode($return)))
		{
			return Uri::base();
		}
		else
		{
			return base64_decode($return);
		}
	}

	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   1.6
	 */
	public function save($key = null, $urlVar = 'a_id')
	{
		// Check for request forgeries.
		$this->checkToken();

		$app     = $this->app;
		$model   = $this->getModel();
		$table   = $model->getTable();
		$data    = $this->input->post->get('jform', [], 'array');
		$editpart = $this->input->post->get('editpart');
		$checkin = $table->hasField('checked_out');
		$context = "$this->option.edit.$this->context";
		$task    = $this->getTask();

		// Determine the name of the primary key for the data.
		if (empty($key)) {
			$key = $table->getKeyName();
		}

		// To avoid data collisions the urlVar may be different from the primary key.
		if (empty($urlVar)) {
			$urlVar = $key;
		}

		$recordId = $this->input->getInt($urlVar);

		// Populate the row id from the session.
		$data[$key] = $recordId;

		//Einlesen "alte" Daten:
		if ($recordId > 0)
		{
			$dataOld = $model->getItem($recordId);
		}

		if ($editpart == '' || $editpart === null){
			//Editpart wurde nicht übergeben, abbruch mit Hinweis!
			$this->setMessage(Text::sprintf('COM_TAGEBUCH__ERROR_EDITPART_FAILED', $model->getError()), 'error');

			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item
					. $this->getRedirectToItemAppend($recordId, $urlVar),
					false
				)
			);

			return false;
		}

		$dataUpdated = $this->matchData(ArrayHelper::fromObject($dataOld),$data,$editpart);

		$result = true;

		 //Wenn ok, redirect to the return page.
			if ($result)
			{
				$this->setRedirect(Route::_($this->getReturnPage(), false));
			}


		return $result;
	}

	/**
	 * Method to reload a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  void
	 *
	 * @since   3.8.0
	 */
	public function reload($key = null, $urlVar = 'a_id')
	{
		/** @noinspection PhpVoidFunctionResultUsedInspection */
		return parent::reload($key, $urlVar);
	}


	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $name     The table name. Optional.
	 * @param   string  $prefix   The class prefix. Optional.
	 * @param   array   $options  Configuration array for model. Optional.
	 *
	 * @return  bool|Table  A Table object
	 *
	 * @since   4.0.0

	 * @throws  Exception
	 */
	public function getTable($name = 'Tagebuch', $prefix = 'Administrator', $options = [])
	{
		return parent::getTable($name, $prefix, $options);
	}

	/**
	 * Methode zum Zusammenführen der beiden Arrays um änderungen zu übernehmen und alte Daten beizubehalten!
	 *
	 * @param   array   $data_old  Die alten Daten die übernommen werden sollen
	 * @param   array   $data_new  Die neuen Daten die überschreiben sollen
	 * @param   string  $editpart  Der Teil der überschrieben werden soll
	 *
	 * @return  array  Rückgabe des aufbereiteten Reports oder false wenn Fehler!
	 *
	 * @since 4.0.0
	 */
	public function matchData(array $data_old , array $data_new, string $editpart): array
	{
		$user = Factory::getApplication()->getIdentity();
		$date = Factory::getDate();
		$groups    = implode(',', $user->getAuthorisedViewLevels());
		$db        = Factory::getDbo();

		$nowDate = $db->quote($date->toSql());

		$newData = $data_old;

		switch ($editpart)
		{
			case 'FS':
				$newData['sff'] = ($data_new['sff'] > 0) ? $data_new['sff'] : $user->id;
				$newData['text_fs'] = $data_new['text_fs'];
				if ($newData['fs_erstellt'] === '0000-00-00 00:00:00' || $newData['fs_erstellt'] == null) {
					$newData['fs_erstellt'] = $nowDate;
					$newData['fs_erstellt_von'] = $user->id;
				} else {
					$newData['fs_laenderung'] = $nowDate;
					$newData['fs_laenderung_von'] = $user->id;
				}
				break;

			case 'SS':
				$newData['sfs'] = ($data_new['sfs'] > 0) ? $data_new['sfs'] : $user->id;
				$newData['text_ss'] = $data_new['text_ss'];
				if ($newData['ss_erstellt'] === '0000-00-00 00:00:00' || $newData['ss_erstellt'] == null) {
					$newData['ss_erstellt'] = $nowDate;
					$newData['ss_erstellt_von'] = $user->id;
				} else {
					$newData['ss_laenderung'] = $nowDate;
					$newData['ss_laenderung_von'] = $user->id;
				}
				break;

			case 'Z1':
				$newData['text_z1'] = $data_new['text_z1'];
				if ($newData['z1_erstellt'] === '0000-00-00 00:00:00' || $newData['z1_erstellt'] == null) {
					$newData['z1_erstellt'] = $nowDate;
					$newData['z1_erstellt_von'] = $user->id;
				} else {
					$newData['z1_laenderung'] = $nowDate;
					$newData['z1_laenderung_von'] = $user->id;
				}
				break;

			case 'Z2':
				$newData['text_z2'] = $data_new['text_z2'];
				if ($newData['z2_erstellt'] === '0000-00-00 00:00:00' || $newData['z2_erstellt'] == null) {
					$newData['z2_erstellt'] = $nowDate;
					$newData['z2_erstellt_von'] = $user->id;
				} else {
					$newData['z2_laenderung'] = $nowDate;
					$newData['z2_laenderung_von'] = $user->id;
				}
				break;

			case 'BL':
				$newData['text_z1'] = $data_new['text_z1'];
				if ($newData['bl_erstellt'] === '0000-00-00 00:00:00' || $newData['bl_erstellt'] == null) {
					$newData['bl_erstellt'] = $nowDate;
					$newData['bl_erstellt_von'] = $user->id;
				} else {
					$newData['bl_laenderung'] = $nowDate;
					$newData['bl_laenderung_von'] = $user->id;
				}
				break;

			case 'AN':
				$newData['text_z1'] = $data_new['text_z1'];
				if ($newData['an_erstellt'] === '0000-00-00 00:00:00' || $newData['an_erstellt'] == null) {
					$newData['an_erstellt'] = $nowDate;
					$newData['an_erstellt_von'] = $user->id;
				} else {
					$newData['an_laenderung'] = $nowDate;
					$newData['an_laenderung_von'] = $user->id;
				}
				break;
		}

		return $newData;
	}
}
