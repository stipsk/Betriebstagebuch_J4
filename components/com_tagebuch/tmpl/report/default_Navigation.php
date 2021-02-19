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
?>
<!-- Buttonbar -->
<nav class="navbar navbar-expand-sm">
    <?php if ($params->get('access-edit')) : ?>
        <!--    Neu und bearbeiten!-->
            <a class="navbar" href="<?php echo Route::_($params->get('editlink').'0&return='. $returnPage); ?>">
                <span class="icon-plus"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_NEW')?>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarEdit" aria-controls="navbarEdit" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarEdit">

                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo JText::_('COM_TAGEBUCH_NEW')?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown03">
                            <?php if ($params->get('access-edit-fs')) : ?>

                                <a class="nav-link" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=FS&return='.$returnPage); ?>">
                                    <span class="icon-edit"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_TAB_FS'); ?>
                                </a>

                            <?php endif;
                            if ($params->get('access-edit-ss')) : ?>

                                    <a class="nav-link" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=SS&return='.$returnPage); ?>"><span class="icon-edit"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_TAB_SS'); ?></a>
                            <?php endif;
                            if ($params->get('access-edit-z1')) : ?>

                                    <a class="nav-link" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=Z1&return='.$returnPage); ?>"><span class="icon-edit"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_TAB_Z1'); ?></a>
                            <?php endif;
                            if ($params->get('access-edit-an')) : ?>

                                    <a class="nav-link" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=an&return='.$returnPage); ?>"><span class="icon-edit"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_TAB_AN'); ?></a>
                            <?php endif;
                            if ($params->get('access-edit-bl')) : ?>

                                    <a class="nav-link" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=BL&return='.$returnPage); ?>"><span class="icon-edit"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_TAB_BL'); ?></a>
                            <?php endif;
                            if ($params->get('access-edit-z2')) : ?>

                                    <a class="nav-link" href="<?php echo Route::_($params->get('editlink').$this->item->id.'&layout=Z2&return='.$returnPage); ?>"><span class="icon-edit"></span>&#160;<?php echo JText::_('COM_TAGEBUCH_TAB_Z2'); ?></a>
                            <?php endif; ?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
    <!--    Navigation Vor un Zurück-->
    <div class="btn-group" role="group" aria-label="First group">
        <button type="button" class="btn btn-outline-secondary">1</button>
        <button type="button" class="btn btn-outline-secondary">2</button>
        <button type="button" class="btn btn-outline-secondary">3</button>
        <button type="button" class="btn btn-outline-secondary">4</button>
    </div>

    <!--    Zusatzmenü-->
     <?php //if ($params->get('show_print_icon') || $params->get('show_email_icon')) :
            //echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->row, 'print' => false));
        //endif;?>
</nav>