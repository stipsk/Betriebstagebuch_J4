<?php

/*
 * @package     Joomla.Site
 * @subpackage  com_tagebuch
 * 
 * @copyright   Copyright (C) 2015 Stphan Knauer. All rights reserved.
 * @license     GNU General Public License version 2 or later
 * 
 * @file: frontend - ./com_tagebuch/views/new/tmpl/hideFields/ss.php
 */

defined('_JEXEC') or die;
//Blende alle Felder für Spätschicht aus
$this->form->setFieldAttribute('text_ss','type','hidden');
$this->form->setFieldAttribute('text_ss','class','hidden');
$this->form->setFieldAttribute('sfs','type','hidden');
$this->form->setFieldAttribute('ss_erstellt','type','hidden');
$this->form->setFieldAttribute('ss_erstellt_von','type','hidden');
$this->form->setFieldAttribute('ss_laenderung','type','hidden');
$this->form->setFieldAttribute('ss_laenderung_von','type','hidden');
?>