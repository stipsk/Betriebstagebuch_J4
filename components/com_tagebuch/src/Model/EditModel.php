<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_tagebuch
 *
 * @copyright   (C) 2020 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace SK\Component\Tagebuch\Site\Model;

use Exception;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Tagebuch Component Edit Model
 *
 * @since  4.0.0
 */
class EditModel extends \SK\Component\Tagebuch\Administrator\Model\ReportModel
{
    /**
     * Model typeAlias string. Used for version history.
     *
     * @var    string
     *
     * @since  4.0.0
     */
    public $typeAlias = 'com_tagebuch.tagebuch';

    /**
     * Name of the form
     *
     * @var    string
     *
     * @since  4.0.0
     */
    protected $formName = 'tagebuch';

    /**
     * Method to get the row form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  Form|boolean  A Form object on success, false on failure
     *
     * @since   4.0.0
     */
    public function getForm($data = [], $loadData = true)
    {
        $form = parent::getForm($data, $loadData);

        // Prevent messing with article language and category when editing existing report with associations
        if ($id = $this->getState('tagebuch.id') && Associations::isEnabled()) {
            $associations = Associations::getAssociations('com_tagebuch', '#__tagebuch', 'com_tagebuch.item', $id);

            // Make fields read only
            if (!empty($associations)) {
                $form->setFieldAttribute('language', 'readonly', 'true');
                $form->setFieldAttribute('language', 'filter', 'unset');
            }
        }

        return $form;
    }

    /**
     * Method to get report data.
     *
     * @param   integer  $itemId  The id of the report.
     *
     * @return  false|object  Report item data object on success, false on failure.
     *
     * @throws  Exception
     *
     * @since   4.0.0
     */
    public function getItem($pk = null)
    {
        $pk = (int) (!empty($pk)) ? $pk : $this->getState('tagebuch.id');

        // Get a row instance.
        $table = $this->getTable();

        // Attempt to load the row.
        try {
            if (!$table->load($pk)) {
                return false;
            }
        } catch (Exception $e) {
            Factory::getApplication()->enqueueMessage($e->getMessage());

            return false;
        }

        $properties = $table->getProperties();
        $value      = ArrayHelper::toObject($properties, \Joomla\CMS\Object\CMSObject::class);

        // Convert field to Registry.
        $value->params = new Registry($value->params);

        // Convert the metadata field to an array.
        $registry        = new Registry($value->metadata);
        $value->metadata = $registry->toArray();

        if ($pk) {
            $value->tags = new TagsHelper();
            $value->tags->getTagIds($value->id, 'com_tagebuch.tagebuch');
            $value->metadata['tags'] = $value->tags;
        }

        return $value;
    }

    /**
     * Get the return URL.
     *
     * @return  string  The return URL.
     *
     * @since   4.0.0
     */
    public function getReturnPage()
    {
        return base64_encode($this->getState('return', ''));
    }

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success.
     *
     * @since   4.0.0
     *
     * @throws  Exception
     */
    public function save($data)
    {
        // Associations are not edited in frontend ATM so we have to inherit them
        if (
            Associations::isEnabled() && !empty($data['id'])
            && $associations = Associations::getAssociations('com_tagebuch', '#__tagebuch', 'com_tagebuch.item', $data['id'])
        ) {
            foreach ($associations as $tag => $associated) {
                $associations[$tag] = (int) $associated->id;
            }

            $data['associations'] = $associations;
        }

        return parent::save($data);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return  void
     *
     * @since   4.0.0
     *
     * @throws  Exception
     */
    protected function populateState($ordering = 'a.id', $direction = 'asc')
    {
        $app   = Factory::getApplication();
        $input = $app->getInput();

        // Load state from the request.
        $pk = $input->getInt('id');
        $this->setState('tagebuch.id', $pk);

        $this->setState('tagebuch.catid', $input->getInt('catid'));

        $return = $input->get('return', '', 'base64');
        $this->setState('return_page', base64_decode($return));

        // Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);

        $this->setState('layout', $input->getString('layout'));
    }

    /**
     * Allows preprocessing of the JForm object.
     *
     * @param   Form    $form   The form object
     * @param   array   $data   The data to be merged into the form object
     * @param   string  $group  The plugin group to be executed
     *
     * @return  void
     *
     * @since   4.0.0
     */
    protected function preprocessForm(Form $form, $data, $group = 'tagebuch')
    {
        if (!Multilanguage::isEnabled()) {
            $form->setFieldAttribute('language', 'type', 'hidden');
            $form->setFieldAttribute('language', 'default', '*');
        }

        parent::preprocessForm($form, $data, $group);
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
}
