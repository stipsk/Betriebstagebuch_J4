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
$blank_image_attribs = array();
$returnPage = base64_encode(Uri::getInstance());
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-2">
            <?php
            if ($params->get('access-edit-ss')){
                $url = Route::_($params->get('editlink').$this->item->id.'&editpart=SS&return='.$returnPage);
                ?>
                <span class="sk-tip tip hasTooltip" title="<?php echo HTMLHelper::_('tooltipText', 'COM_TAGEBUCH_REPORT_EDIT_SS_DESC'); ?>">
                <a class="btn btn-sm btn-outline-info" href="<?php echo $url;?>">
                    <span class="icon-pencil-2"></span>
                </a>
            </span>
                <?php
            }else{echo '&nbsp';}
            ?>
        </div>
        <div class="col-10">
            <p>
            <?php echo JText::_('COM_TAGEBUCH_REPORT_SFS').': '; ?>
            <strong>
                <?php echo $this->item->sfs_name ? $this->item->sfs_name : Text::_( '..' ) ; ?>
            </strong>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-2 col-md-2 smaller">
            <!-- Frühschicht -->
            <?php if ($params->get('show_field_erst_am')){?>
                <div class="row">
                    <div class="col-5 col-md-5 text-right">
                        <span class="fa fa-info"></span>
                        <span class="sk-tip tip hasTooltip" title="<?php echo HTMLHelper::_('tooltipText', 'COM_TAGEBUCH_ERSTELLT_AM_DESC'); ?>">
                            <?php echo Text::_('COM_TAGEBUCH_ERSTELLT_AM').':'; ?>
                        </span>
                    </div>
                    <div class="col-7 col-md-7">
                        <?php echo (($this->item->ss_erstellt == '0000-00-00 00:00:00') || ($this->item->ss_erstellt == '-') || ($this->item->ss_erstellt == null))
                            ? Text::_( 'n.V.' ) : HTMLHelper::_('date', $this->item->ss_erstellt, $formatDay); ?>
                    </div>
                </div>
            <?php } ?>
            <div class="row">
                <div class="col-5 col-md-5 text-right">
                    <span class="fa fa-info"></span>
                    <span class="sk-tip tip hasTooltip" title="<?php echo HTMLHelper::_('tooltipText', 'COM_TAGEBUCH_ERSTELLT_VON_DESC'); ?>">
                        <?php echo Text::_('COM_TAGEBUCH_ERSTELLT_VON').':'; ?>
                    </span>
                </div>
                <div class="col-7 col-md-7">
                    <?php echo ($this->item->ss_erstellt_von_name != '') ? $this->item->ss_erstellt_von_name : Text::_( '-' ); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 col-md-5 text-right">
                    <span class="fa fa-info"></span>
                    <span class="sk-tip tip hasTooltip" title="<?php echo HTMLHelper::_('tooltipText', 'COM_TAGEBUCH_AENDER_AM_DESC'); ?>">
                        <?php echo Text::_('COM_TAGEBUCH_AENDER_AM').':'; ?>
                    </span>
                </div>
                <div class="col-7 col-md-7">
                    <?php echo (($this->item->ss_laenderung == '0000-00-00 00:00:00') || ($this->item->ss_laenderung == '-') || ($this->item->ss_laenderung == null))
                        ? Text::_( 'n.V.' ) : HTMLHelper::_('date', $this->item->ss_laenderung, $formatDay); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-5 col-md-5 text-right">
                    <span class="fa fa-info"></span>
                    <span class="sk-tip tip hasTooltip" title="<?php echo HTMLHelper::_('tooltipText', 'COM_TAGEBUCH_AENDER_VON_DESC'); ?>">
                        <?php echo Text::_('COM_TAGEBUCH_AENDER_VON').':'; ?>
                    </span>
                </div>
                <div class="col-7 col-md-7">
                    <?php echo ($this->item->ss_laenderung_von_name != '') ? $this->item->ss_laenderung_von_name : Text::_( '-' ) ; ?>
                </div>
            </div>
        </div>
        <div class="col-10 col-md-10">
            <?php echo (!($this->item->text_ss === null || $this->item->text_ss == '')) ? $this->item->text_ss : HTMLHelper::image('media/com_tagebuch/images/blank.gif' , Text::_('Empty') , $blank_image_attribs,true,-1) ?>
        </div>
    </div>
</div>