<?php
/**
 * @package     Tagebuch.Site
 * @subpackage  com_tagebuch
 *
 * @copyright   Copyright (C) 2021 Stephan Knauer. All rights reserved.
 * @license     GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use SK\Component\Tagebuch\Administrator\Extension\TagebuchComponent;
use SK\Component\Tagebuch\Site\Helper\RouteHelper;

HTMLHelper::_('behavior.core');

// Create shortcuts to some parameters.
$params  = $this->item->params;
//$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = Factory::getUser();
$info    = $params->get('info_block_position', 0);
$formatDayTime = 'd.m.Y H:i';
$formatDay = 'd.m.Y';
$TabChecked = '<span class="fas fa-check" style="color: darkgreen;"></span>&nbsp;';
$TabUnChecked = '<span class="fas fa-check" style="color: lightgrey;"></span>&nbsp;';

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->registerAndUseScript('tagebuch.bootstrap_min_js');
$wa->useStyle('tagebuch.standard');



// Check if associations are implemented. If they are, define the parameter.
$assocParam = (Associations::isEnabled() && $params->get('show_associations'));
?>
	<?php echo $this->loadTemplate('Navigation'); ?>

    <div class="page-header">
        <h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
    </div>
    <?php if ($this->state->DatumNotFound):?>
    <div class="alert alert-primary">
        <span class="icon-info-circle">&nbsp;<?php echo Text::sprintf('COM_TAGEBUCH_CELENDARNAV_NOT_FOUND', $this->state->DatumNotFound );?></span>
    </div>
    <?php endif; ?>

	<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'general')); ?>

    <?php $checked = ($this->item->fs_erstellt_von_name != '') ? $TabChecked : $TabUnChecked; ?>

	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', $checked . Text::_('COM_TAGEBUCH_REPORT_TAB1')); ?>

        <?php echo $this->loadTemplate('tabfs'); ?>

	<?php echo HTMLHelper::_('uitab.endTab'); ?>

	<?php $checked = ($this->item->ss_erstellt_von_name != '') ? $TabChecked : $TabUnChecked; ?>
    <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general2', $checked . Text::_('COM_TAGEBUCH_REPORT_TAB2')); ?>
	    <?php echo $this->loadTemplate('tabss'); ?>
	<?php echo HTMLHelper::_('uitab.endTab'); ?>

	<?php $checked = ($this->item->z1_erstellt_von_name != '') ? $TabChecked : $TabUnChecked; ?>
    <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general3', $checked . Text::_('COM_TAGEBUCH_REPORT_TAB3') ); ?>
	    <?php echo $this->loadTemplate('tabz1'); ?>
	<?php echo HTMLHelper::_('uitab.endTab'); ?>

	<?php $checked = ($this->item->an_erstellt_von_name != '') ? $TabChecked : $TabUnChecked; ?>
	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general4', $checked . Text::_('COM_TAGEBUCH_REPORT_TAB4')); ?>
	    <?php echo $this->loadTemplate('taban'); ?>
	<?php echo HTMLHelper::_('uitab.endTab'); ?>

	<?php $checked = ($this->item->bl_erstellt_von_name != '') ? $TabChecked : $TabUnChecked; ?>
	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general5', $checked . Text::_('COM_TAGEBUCH_REPORT_TAB5')); ?>
	    <?php echo $this->loadTemplate('tabbl'); ?>
	<?php echo HTMLHelper::_('uitab.endTab'); ?>

	<?php $checked = ($this->item->z2_erstellt_von_name != '') ? $TabChecked : $TabUnChecked; ?>
	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general6', $checked . Text::_('COM_TAGEBUCH_REPORT_TAB6')); ?>
	    <?php echo $this->loadTemplate('tabz2'); ?>
	<?php echo HTMLHelper::_('uitab.endTab'); ?>


	<?php echo HTMLHelper::_('uitab.endTabSet'); ?>


</div>
