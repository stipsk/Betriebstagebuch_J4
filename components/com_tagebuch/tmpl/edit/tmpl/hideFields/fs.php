<?php

/*
 * @package     Joomla.Site
 * @subpackage  com_tagebuch
 * 
 * @copyright   Copyright (C) 2015 Stphan Knauer. All rights reserved.
 * @license     GNU General Public License version 2 or later
 * 
 * @file: frontend - ./com_tagebuch/views/new/tmpl/hideFields/fs.php
 */

defined('_JEXEC') or die;
//Blende alle Felder für Frühschicht aus
$this->form->setFieldAttribute('text_fs','type','hidden');
$this->form->setFieldAttribute('text_fs','class','hidden');
$this->form->setFieldAttribute('sff','type','hidden');
$this->form->setFieldAttribute('sf_erstellt','type','hidden');
$this->form->setFieldAttribute('sf_erstellt_von','type','hidden');
$this->form->setFieldAttribute('sf_laenderung','type','hidden');
$this->form->setFieldAttribute('sf_laenderung_von','type','hidden');
?>