<?php

/*
 * @package     Joomla.Site
 * @subpackage  com_tagebuch
 * 
 * @copyright   Copyright (C) 2015 Stphan Knauer. All rights reserved.
 * @license     GNU General Public License version 2 or later
 * 
 * @file: frontend - ./com_tagebuch/views/new/tmpl/hideFields/bl.php
 */

defined('_JEXEC') or die;
//Blende alle Felder für Z1 aus
$this->form->setFieldAttribute('text_bl','type','hidden');
$this->form->setFieldAttribute('text_bl','class','hidden');
$this->form->setFieldAttribute('bl_erstellt','type','hidden');
$this->form->setFieldAttribute('bl_erstellt_von','type','hidden');
$this->form->setFieldAttribute('bl_laenderung','type','hidden');
$this->form->setFieldAttribute('bl_laenderung_von','type','hidden');
$this->form->setFieldAttribute('gesehen','type','hidden');
$this->form->setFieldAttribute('abluft_ok','type','hidden');
?>