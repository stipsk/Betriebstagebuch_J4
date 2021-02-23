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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Helper\UserGroupsHelper;

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
	 * @param   integer  $id        The route of the content item.
	 **
	 * @return  integer  The last report id.
	 *
	 * @since   1.5
	 */

	public function getLastId($id = null)
	{
		$user = Factory::getUser();
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
	 * Datum des letzten Eintrages holen
	 *
	 * @return DATETIME
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
		}
		$result_array->last_date	= $this->_rows[$last]->datum;
		if ($result_array->last_date)
		{
			$date = Factory::getDate($result_array->last_date);
			$result_array->last_date = $date->format('l, d.m.Y');
		}
		$result_array->next_date	= $next ? $this->_rows[$next]->datum : null;
		if ($result_array->next_date)
		{
			$date = Factory::getDate($result_array->next_date);
			$result_array->next_date = $date->format('l, d.m.Y');
		}
		$result_array->back_date	= $result_array->back_id ? $this->_rows[$back]->datum : null;
		if ($result_array->back_date)
		{
			$date = Factory::getDate($result_array->back_date);
			$result_array->back_date = $date->format('l, d.m.Y');
		}

		return ($result_array);
	}

	/**
	 * Rückgabe eines Arrays mit den ID´s der Benutzergruppen
	 *
	 * @return stdclass _TagebuchGroups
	 * @since version 4.0
	 */
	public function getTagebuchGroups()
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
				$this->_TagebuchGroups->GroupVEF = $item->id;
			}
		}
		return $this->_TagebuchGroups;
	}
}