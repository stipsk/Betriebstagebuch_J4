<?php
/**
 * @package     Tagebuch.Site
 * @subpackage  com_tagebuch
 *
 * @copyright   Copyright (C) 2021 Stephan Knauer. All rights reserved.
 * @license     GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;

// Create shortcuts to some parameters.
$params  = $this->item->params;
$formatDayTime = 'd.m.Y H:i';
$formatDay = 'd.m.Y';
$returnPage = base64_encode(Uri::getInstance());
if ($params->get('access-edit'))
{
	$disabledEdit = '';
	$iconEdit = 'icon-pencil-2';
	$iconAdd = 'icon-plus';
}else{
	$disabledEdit = ' disabled';
	$iconEdit = 'icon-lock';
	$iconAdd = 'icon-lock';
}
?>
<!-- Buttonbar -->
<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
    <!--    Neu und Edit-Buttons (Dropdowns) -->
    <div class="btn-group btn-group-sm mr-2" role="group" aria-label="Bearbeiten und Neu">
        <div class="btn-group btn-group-sm bg-dark" role="group">
            <div class="dropdown">
                <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="dropdownMenuAdd" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" <?php echo $disabledEdit; ?> >
                    <span class="<?php echo $iconAdd;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_NEW')?>
                </button>
                <div class="dropdown-menu " aria-labelledby="dropdownMenuAdd">
		            <?php if ($params->get('access-add-fs'))
		            {
			            $disabledEntry = '';
			            $iconEntry = 'icon-plus';
		            }else{
			            $disabledEntry = 'disabled';
			            $iconEntry = 'icon-lock';
		            }?>
                    <a class="dropdown-item bg-success text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=FS&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_FS'); ?>
                    </a>
		            <?php if ($params->get('access-add-ss'))
		            {
			            $disabledEntry = '';
			            $iconEntry = 'icon-plus';
		            }else{
			            $disabledEntry = 'disabled';
			            $iconEntry = 'icon-lock';
		            }?>
                    <a class="dropdown-item bg-success text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=SS&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_SS'); ?>
                    </a>
		            <?php if ($params->get('access-add-z1'))
		            {
			            $disabledEntry = '';
			            $iconEntry = 'icon-plus';
		            }else{
			            $disabledEntry = 'disabled';
			            $iconEntry = 'icon-lock';
		            }?>
                    <a class="dropdown-item bg-success text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=Z1&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_Z1'); ?>
                    </a>
		            <?php if ($params->get('access-add-an'))
		            {
			            $disabledEntry = '';
			            $iconEntry = 'icon-plus';
		            }else{
			            $disabledEntry = 'disabled';
			            $iconEntry = 'icon-lock';
		            }?>
                    <a class="dropdown-item bg-success text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=AN&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_AN'); ?>
                    </a>
		            <?php if ($params->get('access-add-bl'))
		            {
			            $disabledEntry = '';
			            $iconEntry = 'icon-plus';
		            }else{
			            $disabledEntry = 'disabled';
			            $iconEntry = 'icon-lock';
		            }?>
                    <a class="dropdown-item bg-success text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=BL&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_BL'); ?>
                    </a>
		            <?php if ($params->get('access-add-z2'))
		            {
			            $disabledEntry = '';
			            $iconEntry = 'icon-plus';
		            }else{
			            $disabledEntry = 'disabled';
			            $iconEntry = 'icon-lock';
		            }?>
                    <a class="dropdown-item bg-success text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=Z2&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_Z2'); ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="btn-group btn-group-sm" role="group">
            <div class="dropdown">
                <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenuEdit" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" <?php echo $disabledEdit; ?> >
                    <span class="<?php echo $iconEdit;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT')?>
                </button>
                <div class="dropdown-menu " aria-labelledby="dropdownMenuEdit">
	                <?php if ($params->get('access-edit-fs'))
	                {
		                $disabledEntry = '';
		                $iconEntry = 'icon-pencil-2';
	                }else{
		                $disabledEntry = 'disabled';
		                $iconEntry = 'icon-lock';
	                }?>
                    <a class="dropdown-item bg-info text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=FS&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_FS'); ?>
                    </a>
	                <?php if ($params->get('access-edit-ss'))
	                {
		                $disabledEntry = '';
		                $iconEntry = 'icon-pencil-2';
	                }else{
		                $disabledEntry = 'disabled';
		                $iconEntry = 'icon-lock';
	                }?>
                    <a class="dropdown-item bg-info text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=SS&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_SS'); ?>
                    </a>
	                <?php if ($params->get('access-edit-z1'))
	                {
		                $disabledEntry = '';
		                $iconEntry = 'icon-pencil-2';
	                }else{
		                $disabledEntry = 'disabled';
		                $iconEntry = 'icon-lock';
	                }?>
                    <a class="dropdown-item bg-info text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=Z1&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_Z1'); ?>
                    </a>
	                <?php if ($params->get('access-edit-an'))
	                {
		                $disabledEntry = '';
		                $iconEntry = 'icon-pencil-2';
	                }else{
		                $disabledEntry = 'disabled';
		                $iconEntry = 'icon-lock';
	                }?>
                    <a class="dropdown-item bg-info text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=AN&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_AN'); ?>
                    </a>
	                <?php if ($params->get('access-edit-bl'))
	                {
		                $disabledEntry = '';
		                $iconEntry = 'icon-pencil-2';
	                }else{
		                $disabledEntry = 'disabled';
		                $iconEntry = 'icon-lock';
	                }?>
                    <a class="dropdown-item bg-info text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=BL&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_BL'); ?>
                    </a>
	                <?php if ($params->get('access-edit-z2'))
                    {
                        $disabledEntry = '';
                        $iconEntry = 'icon-pencil-2';
                    }else{
                        $disabledEntry = 'disabled';
                        $iconEntry = 'icon-lock';
                    }?>
                    <a class="dropdown-item bg-info text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=Z2&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_Z2'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--    Navigation Vor un Zurück-->
    <div class="btn-group btn-group-sm mr-2" role="group" aria-label="Second group">
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-primary">1</button>
            <button type="button" class="btn btn-primary">2</button>
            <button type="button" class="btn btn-primary">3</button>
            <button type="button" class="btn btn-primary">4</button>
        </div>
    </div>
</div>

