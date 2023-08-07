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
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use SK\Component\Tagebuch\Site\Helper\RouteHelper as TagebuchHelper;
use SK\Component\Tagebuch\Administrator\Helper\FahrzeugeHelper as FahrzeugeHelper;


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

$CalendarAttribs = array(
	'class'             => 'form-control',
    'onChange'          =>  'calendarValueChange(this)',
    'onClick'           =>  'calendarBtnClick(this)',
    'onBtnClick'        =>  'calendarBtnClick(this)',
    );

$url = Route::_(TagebuchHelper::getReportRoute(null, null));
?>


<script>
    var url;
    function calendarValueChange(el) {
        // with jQuery

        if (!jQuery(el).val().length) {
            alert("Calendar value is empty!");
        } else {
            var url_datum = jQuery(el).val();
            url = '<?php echo $url . '&amp;datum='?>' + url_datum;
            $(location).attr('href',url);
        }

    }
    function calendarBtnClick(eb) {
        $(location).attr('href',url);
    }
</script>

<!-- Buttonbar -->
<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
    <!--    Neu und Edit-Buttons (Dropdowns) -->
    <div class="btn-group " role="group" aria-label="Bearbeiten und Neu">
        <div class="btn-group bg-dark" role="group">
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" <?php echo $disabledEdit; ?> >
                    <span class="<?php echo $iconAdd;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_NEW')?>
                </button>
                <ul class="dropdown-menu">
		            <?php if ($params->get('access-add-fs'))
		            {
			            $disabledEntry = '';
			            $iconEntry = 'icon-plus';
		            }else{
			            $disabledEntry = 'disabled';
			            $iconEntry = 'icon-lock';
		            }?>
                    <li>
                        <a class="dropdown-item bg-success text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo TagebuchHelper::getReportRoute($params->get('editlink').$this->item->id,'slug',0,'&layout=FS&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_NEW_FS'); ?>
                        </a>
                    </li>
		            <?php if ($params->get('access-add-ss'))
		            {
			            $disabledEntry = '';
			            $iconEntry = 'icon-plus';
		            }else{
			            $disabledEntry = 'disabled';
			            $iconEntry = 'icon-lock';
		            }?>
                    <li>
                        <a class="dropdown-item bg-success text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=SS&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_NEW_SS'); ?>
                        </a>
                    </li>
		            <?php if ($params->get('access-add-z1'))
		            {
			            $disabledEntry = '';
			            $iconEntry = 'icon-plus';
		            }else{
			            $disabledEntry = 'disabled';
			            $iconEntry = 'icon-lock';
		            }?>
                    <li>
                        <a class="dropdown-item bg-success text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=Z1&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_NEW_Z1'); ?>
                        </a>
                    </li>
		            <?php if ($params->get('access-add-an'))
		            {
			            $disabledEntry = '';
			            $iconEntry = 'icon-plus';
		            }else{
			            $disabledEntry = 'disabled';
			            $iconEntry = 'icon-lock';
		            }?>
                    <li><a class="dropdown-item bg-success text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=AN&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_NEW_AN'); ?>
                        </a></li>
		            <?php if ($params->get('access-add-bl'))
		            {
			            $disabledEntry = '';
			            $iconEntry = 'icon-plus';
		            }else{
			            $disabledEntry = 'disabled';
			            $iconEntry = 'icon-lock';
		            }?>
                    <li><a class="dropdown-item bg-success text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=BL&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_NEW_BL'); ?>
                        </a></li>
		            <?php if ($params->get('access-add-z2'))
		            {
			            $disabledEntry = '';
			            $iconEntry = 'icon-plus';
		            }else{
			            $disabledEntry = 'disabled';
			            $iconEntry = 'icon-lock';
		            }?>
                    <li><a class="dropdown-item bg-success text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=Z2&return='.$returnPage); ?>">
                        <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_NEW_Z2'); ?>
                        </a></li>
                </ul>
            </div>
        </div>
        <div class="btn-group" role="group">
            <div class="dropdown">
                <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" <?php echo $disabledEdit; ?> >
                    <span class="<?php echo $iconEdit;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT')?>
                </button>
                <ul class="dropdown-menu ">
	                <?php if ($params->get('access-edit-fs'))
	                {
		                $disabledEntry = '';
		                $iconEntry = 'icon-pencil-2';
	                }else{
		                $disabledEntry = 'disabled';
		                $iconEntry = 'icon-lock';
	                }?>
                    <li>
                        <a class="dropdown-item bg-info text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_(TagebuchHelper::getEditRoute( (int) $this->item->id, null,0,'FS')).'&return='.$returnPage; ?>">

                            <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_FS'); ?>
                        </a>
                    </li>
	                <?php if ($params->get('access-edit-ss'))
	                {
		                $disabledEntry = '';
		                $iconEntry = 'icon-pencil-2';
	                }else{
		                $disabledEntry = 'disabled';
		                $iconEntry = 'icon-lock';
	                }?>
                    <li>
                        <a class="dropdown-item bg-info text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_(TagebuchHelper::getEditRoute( (int) $this->item->id, null,0,'SS')).'&return='.$returnPage; ?>">
                            <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_SS'); ?>
                        </a>
                    </li>
	                <?php if ($params->get('access-edit-z1'))
	                {
		                $disabledEntry = '';
		                $iconEntry = 'icon-pencil-2';
	                }else{
		                $disabledEntry = 'disabled';
		                $iconEntry = 'icon-lock';
	                }?>
                    <li>
                        <a class="dropdown-item bg-info text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_(TagebuchHelper::getEditRoute( (int) $this->item->id, null,0,'Z1')).'&return='.$returnPage; ?>">
                            <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_Z1'); ?>
                        </a>
                    </li>
	                <?php if ($params->get('access-edit-an'))
	                {
		                $disabledEntry = '';
		                $iconEntry = 'icon-pencil-2';
	                }else{
		                $disabledEntry = 'disabled';
		                $iconEntry = 'icon-lock';
	                }?>
                    <li>
                        <a class="dropdown-item bg-info text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_(TagebuchHelper::getEditRoute( (int) $this->item->id, null,0,'AN')).'&return='.$returnPage; ?>">
                            <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_AN'); ?>
                        </a>
                    </li>
	                <?php if ($params->get('access-edit-bl'))
	                {
		                $disabledEntry = '';
		                $iconEntry = 'icon-pencil-2';
	                }else{
		                $disabledEntry = 'disabled';
		                $iconEntry = 'icon-lock';
	                }?>
                    <li>
                        <a class="dropdown-item bg-info text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_(TagebuchHelper::getEditRoute( (int) $this->item->id, null,0,'BL')).'&return='.$returnPage; ?>">
                            <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_BL'); ?>
                        </a>
                    </li>
	                <?php if ($params->get('access-edit-z2'))
                    {
                        $disabledEntry = '';
                        $iconEntry = 'icon-pencil-2';
                    }else{
                        $disabledEntry = 'disabled';
                        $iconEntry = 'icon-lock';
                    }?>
                    <li>
                        <a class="dropdown-item bg-info text-light text-nowrap <?php echo $disabledEntry;?>" href="<?php echo Route::_(TagebuchHelper::getEditRoute( (int) $this->item->id, null,0,'Z2')).'&return='.$returnPage; ?>">
                            <span class="<?php echo $iconEntry;?>"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_REPORT_MENU_EDIT_Z2'); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--    Navigation Vor und Zurück-->
    <div class="btn-group " role="group" aria-label="Second group">
        <div class="btn-group " role="group">
            <a role="button" class="btn btn-primary <?php echo $this->navigationClass->first_disabled;?>"  href="<?php echo Route::_(TagebuchHelper::getReportRoute($this->navigationClass->first_id, $this->navigationClass->first_slug)); ?>">
                <span class="icon-angle-double-left"></span>&#160;
                <?php echo JText::_('COM_TAGEBUCH_REPORT_NAVIGATION_FIRST'); ?>
            </a>
            <a type="button" class="btn btn-primary <?php echo $this->navigationClass->back_disabled;?>" href="<?php echo Route::_(TagebuchHelper::getReportRoute($this->navigationClass->back_id, $this->navigationClass->back_slug)); ?>">
                <span class="icon-angle-left"></span>&#160;
                <?php echo JText::_('COM_TAGEBUCH_REPORT_NAVIGATION_BACK'); ?>
            </a>
            <a type="button" class="btn btn-primary <?php echo $this->navigationClass->next_disabled;?>" href="<?php echo Route::_(TagebuchHelper::getReportRoute($this->navigationClass->next_id, $this->navigationClass->next_slug)); ?>">
                <span class="icon-angle-right"></span>&#160;
                <?php echo JText::_('COM_TAGEBUCH_REPORT_NAVIGATION_NEXT'); ?>
            </a>
            <a type="button" class="btn btn-primary <?php echo $this->navigationClass->last_disabled;?>" href="<?php echo Route::_(TagebuchHelper::getReportRoute($this->navigationClass->last_id, $this->navigationClass->last_slug)); ?>">
                <span class="icon-angle-double-right"></span>&#160;
                <?php echo JText::_('COM_TAGEBUCH_REPORT_NAVIGATION_LAST'); ?>
            </a>
        </div>
    </div>
    <div class="btn-group " role="group" aria-label="Kalendarsearch">


            <?php echo HTMLHelper::_('calendar',$this->item->datum,'datum','datum','%d.%m.%Y',$CalendarAttribs);?>
            <?php // echo HTMLHelper::_('calendarnav.calendar',$this->item->datum,'datum','datum','%d.%m.%Y',$CalendarAttribs);?>

    </div>
</div>

