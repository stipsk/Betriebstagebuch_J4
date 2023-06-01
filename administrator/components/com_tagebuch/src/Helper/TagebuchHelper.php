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

use Joomla\CMS\Date\Date;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Helper\UserGroupsHelper;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * Tagebuch component helper.
 *
 * @since  4.0
 */
class TagebuchHelper extends ComponentHelper
{
	/**
	 * The Last Date of all Reports.
	 *
	 * @var    datetime $lastDate
	 * @since  1.6
	 */
	protected $lastDate;

	/**
	 * Die ID´s Gruppen der Tagebuchkomponente
	 *
	 * @var array $_TagebuchGroups
	 * @since 1.0
	 */
	protected $_TagebuchGroups;

	/**
	 * Die ID´s der User die den Gruppen der Tagebuchkomponente zugeordnet sind
	 *
	 * @var array $_TagebuchAccess
	 * @since 1.0
	 */
	Protected $_TagebuchAccess;

	public function __construct()
	{
		$this->lastDate = null;
		$this->_TagebuchGroups = new \stdClass();
		$this->_TagebuchAccess = new \stdClass();
	}

	public static function getTagebuchTitle($id)
	{
		if (empty($id))
		{
			// throw an error or ...
			return false;
		}
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('datum');
		$query->from('#__tagebuch');
		$query->where('id = ' . $id);
		$db->setQuery($query);
		return $db->loadObject();
	}

	/**
	 * Get the last Report.
	 *
	 * @param   integer  $id
	 **
	 * @return  integer  The last report id.
	 *
	 * @since   1.5
	 */

	public function getLastId($id = null)
	{
		$app = Factory::getApplication();
		$user = $app->getIdentity();
		$db    = Factory::getDbo();
		$config	= ComponentHelper::getParams( 'com_tagebuch' );
		$query = $db->getQuery(true);

		$publish_erlaubt = $user->authorise('core.edit.state', 'com_tagebuch');
		$where = $publish_erlaubt ? $db->quoteName('state') . '>= 0' : $db->quoteName('state') . '= 1' ;
		$limit = 10;
		$limitstart = 0;

		$query->select(	$db->quoteName('id') )
			->select(	$db->quoteName('datum') )
			->from($db->quoteName('#__tagebuch'))
			->where($where)
			->order($db->quoteName('datum') . ' ' . 'DESC')
			->setLimit($limit, $limitstart);

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$this->lastDate = $rows[0]->datum;
		return ($rows[0]->id);
	}

	/**
	 * Suche Eintrag nach Datumsstring
	 *
	 * @param   string  Suchstring für Datum
	 *
	 * @return  int     Rückgabe der Eintragsid - null wenn nicht vorhanden
	 *
	 * @since 1.0
	 */
	public function getIdFromDateString($datestring)
	{
		$app = Factory::getApplication();
		$user = $app->getIdentity();
		$db    = Factory::getDbo();
		$config	= ComponentHelper::getParams( 'com_tagebuch' );
		$query = $db->getQuery(true);

		$date = Date::getInstance($datestring);
		$suchdatum = $date->format('Y-m-d');
		$where[] = $db->quoteName('datum') . ' = :suchdatum';
		$query->bind(':suchdatum', $suchdatum);

		$publish_erlaubt = $user->authorise('core.edit.state', 'com_tagebuch');
		$where[] = $publish_erlaubt ? $db->quoteName('state') . '>= 0' : $db->quoteName('state') . '= 1' ;
		$limit = 10;
		$limitstart = 0;

		$query->select(	$db->quoteName('id') )
			->select(	$db->quoteName('datum') )
			->from($db->quoteName('#__tagebuch'))
			->where(implode(' AND ', $where))
			->order($db->quoteName('datum') . ' ' . 'DESC')
			->setLimit($limit, $limitstart);

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$return = count($rows) > 0 ? $rows[0]->id : null;
		return ($return);
	}

	/**
	 * Datum des letzten Eintrages holen
	 *
	 * @return DATETIME
	 *
	 * @since 1.0
	 */
	public function getLastDate()
	{
		if (!$this->_lastDate)
		{
			$this->getLastEntry();
		}
		return ($this->_lastDate);
	}


	/**
	 * Nächster und Zurückliegender Eintrag einholen
	 *
	 * @param Int $id
	 *
	 * @return Array
	 */
	public function getNextPreview($id)
	{
		$user = Factory::getUser();
		$db = Factory::getDBO();
		$config	= ComponentHelper::getParams( 'com_Tagebuch' );

		$publish_erlaubt = $user->authorise('core.edit.state', 'com_content') || (count($user->getAuthorisedCategories('com_content', 'core.edit.state')));

		$query = "SELECT * FROM #__tagebuch WHERE ";
		$query .= $publish_erlaubt ? '1 ' : "state = '1' ";
		$query .= "ORDER BY datum ASC";
		$db->setQuery($query);

		$this->_rows = $db->loadObjectList();

		$next = null;
		$back = null;
		$first = null;
		$last = null;

		for ($i = 0 ; $i < count($this->_rows) ; $i++)
		{
			if ($this->_rows[$i]->id == $id) {
				//Datensatz gefunden
				$back = $i == 0 ? -1 : $i-1;
				$next = ($i < (count($this->_rows))-1) ? $i+1 : null;
			}
		}
		$first = 0;
		$last = count($this->_rows)-1;

		$result_array = new \stdClass;
		$result_array->first_id		= $this->_rows[$first]->id;
		$result_array->last_id		= $this->_rows[$last]->id;
		$result_array->next_id		= $next ? $this->_rows[$next]->id : null;
		if ( $back != -1 ){
			$result_array->back_id	= $back >= 0 ? $this->_rows[$back]->id : null;
		}else{
			$result_array->back_id 	= null;
		}
		//$result_array->back_id		= $back >= 0 ? $this->_rows[$back]->id : null;
		$result_array->first_date 	= $this->_rows[$first]->datum;
		if ($result_array->first_date)
		{
			$date = Factory::getDate($result_array->first_date);
			$result_array->first_date = $date->format('l, d.m.Y');
			$result_array->first_slug = $this->getSlugFromDate($date);
		}
		$result_array->last_date	= $this->_rows[$last]->datum;
		if ($result_array->last_date)
		{
			$date = Factory::getDate($result_array->last_date);
			$result_array->last_date = $date->format('l, d.m.Y');
			$result_array->last_slug = $this->getSlugFromDate($date);
		}
		$result_array->next_date	= $next ? $this->_rows[$next]->datum : null;
		if ($result_array->next_date)
		{
			$date = Factory::getDate($result_array->next_date);
			$result_array->next_date = $date->format('l, d.m.Y');
			$result_array->next_slug = $this->getSlugFromDate($date);
		}
		$result_array->back_date	= $result_array->back_id ? $this->_rows[$back]->datum : null;
		if ($result_array->back_date)
		{
			$date = Factory::getDate($result_array->back_date);
			$result_array->back_date = $date->format('l, d.m.Y');
			$result_array->back_slug = $this->getSlugFromDate($date);
		}

		if (($result_array->first_id == null) || ($result_array->first_id == $id)){
			$result_array->first_disabled = ' disabled';
		}else{
			$result_array->first_disabled = '';
		}
		if (($result_array->back_id == null) || ($result_array->back_id == $id)){
			$result_array->back_disabled = ' disabled';
		}else{
			$result_array->back_disabled = '';
		}
		if (($result_array->next_id == null) || ($result_array->next_id == $id)){
			$result_array->next_disabled = ' disabled';
		}else{
			$result_array->next_disabled = '';
		}
		if (($result_array->last_id == null) || ($result_array->last_id == $id)){
			$result_array->last_disabled = ' disabled';
		}else{
			$result_array->last_disabled = '';
		}

		return ($result_array);
	}

	/**
	 * Slug mittels des Datums erzeugen
	 *
	 * @param date $datum
	 *
	 * @return string $slug
	 *
	 * @since 1.0
	 */
	private function getSlugFromDate($datum){

		$slug = preg_replace('/[^a-z\d]/i', '-', HTMLHelper::_('date' ,$datum, 'Y-m-d'));
		$slug = strtolower(str_replace(' ', '-', $slug));
		return $slug;
	}

	/**
	 * Erzeugt eine Array mit den ID´s der Benutzergruppen
	 *
	 *
	 * @since version 4.0
	 */
	private function getTagebuchGroups()
	{
		//$user = new User();

		$usergroup  = new UserGroupsHelper();
		$test = $usergroup->loadAll();
		$groups = $test->getAll();

		foreach ($groups as $item)
		{
			if ($item->title == 'Tagebuch-Luft'){
				$this->_TagebuchGroups->GroupLUFT = $item->id;
			}
			if ($item->title == 'Tagebuch-BL'){
				$this->_TagebuchGroups->GroupBL = $item->id;
			}
			if ($item->title == 'Tagebuch-VEF'){
				$this->_TagebuchGroups->GroupVEFB = $item->id;
			}
		}

	}

	/**
	 * @param integer $userid
	 *
	 * @return \stdClass _TagebuchAccess
	 * @since version 1.0
	 */

	public function getTagebuchAccess($userid = null)
	{
		$this->getTagebuchGroups();
		$app = Factory::getApplication();
		$user = $app->getIdentity();
		$groups = $user->getAuthorisedGroups();

		if (in_array($this->_TagebuchGroups->GroupLUFT,$groups)) {
			$this->_TagebuchAccess->isLuft = true;
		}else{
			$this->_TagebuchAccess->isLuft = false;
		}

		if (in_array($this->_TagebuchGroups->GroupBL,$groups)) {
			$this->_TagebuchAccess->isBL = true;
		}else{
			$this->_TagebuchAccess->isBL = false;
		}

		if (in_array($this->_TagebuchGroups->GroupVEFB,$groups)) {
			$this->_TagebuchAccess->isVEfB = true;
		}else{
			$this->_TagebuchAccess->isVEfB = false;
		}


	return $this->_TagebuchAccess;

	}
}