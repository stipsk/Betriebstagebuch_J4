<?php

/*
 * @package     Joomla.Site
 * @subpackage  com_tagebuch
 * 
 * @copyright   Copyright (C) 2015 Stphan Knauer. All rights reserved.
 * @license     GNU General Public License version 2 or later
 * 
 * @file: frontend - ./com_tagebuch/views/new/tmpl/hideFields/z1.php
 */

defined('_JEXEC') or die;
//Blende alle Felder für Z1 aus
$this->form->setFieldAttribute('text_z1','type','hidden');
$this->form->setFieldAttribute('text_z1','class','hidden');
$this->form->setFieldAttribute('z1_erstellt','type','hidden');
$this->form->setFieldAttribute('z1_erstellt_von','type','hidden');
$this->form->setFieldAttribute('z1_laenderung','type','hidden');
$this->form->setFieldAttribute('z1_laenderung_von','type','hidden');
?>