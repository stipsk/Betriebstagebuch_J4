<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
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

// Create shortcuts to some parameters.
$params  = $this->item->params;
//$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = Factory::getUser();
$info    = $params->get('info_block_position', 0);
$format = 'd.m.Y H:i';

// Check if associations are implemented. If they are, define the parameter.
$assocParam = (Associations::isEnabled() && $params->get('show_associations'));
?>
<div class="com-content-article item-page<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Article">
	<meta itemprop="inLanguage" content="<?php echo ($this->item->language === '*') ? Factory::getApplication()->get('language') : $this->item->language; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif;
	if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative)
	{
		echo $this->item->pagination;
	}
	?>


	<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => 'general')); ?>

	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('Erster Tab')); ?>
    <div class="container-fluid">
        <div class="row no-gutters">
            <div class="col-12">
                <?php echo JText::_('COM_TAGEBUCH_SFFRUEH').':'; ?>
                <strong>
                    <?php echo $this->item->sff_name ? $this->item->sff_name : Text::_( '..' ) ; ?>
                </strong>
            </div>
        </div>
        <div class="row">
            <div class="col-3 col-md-3 text-small">
                <!-- Frühschicht -->
                <?php if ($params->get('show_field_erst_am')){?>
                    <div class="row">
                        <div class="col-4 col-md-4 text-right">
                            <?php echo Text::_('COM_TAGEBUCH_ERSTELLT_AM').':'; ?>
                        </div>
                        <div class="col-8 col-md-8">
                            <?php echo (($this->item->fs_erstellt == '0000-00-00 00:00:00') || ($this->item->fs_erstellt == ''))
                                ? Text::_( '..' ) : HTMLHelper::_('date', $this->item->fs_erstellt, $format); ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="row">
                    <div class="col-4 col-md-4 text-right">
                        <?php echo Text::_('COM_TAGEBUCH_ERSTELLT_VON').':'; ?>
                    </div>
                    <div class="col-8 col-md-8">
                        <?php echo ($this->item->fs_erstellt_von_name != '') ? $this->item->fs_erstellt_von_name : Text::_( '..' ); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 col-md-4 text-right">
                        <?php echo Text::_('COM_TAGEBUCH_AENDER_AM').':'; ?>
                    </div>
                    <div class="col-8 col-md-8">
                        <?php echo (($this->item->fs_laenderung == '0000-00-00 00:00:00') || ($this->item->fs_laenderung == ''))
                            ? Text::_( '..' ) : HTMLHelper::_('date', $this->item->fs_laenderung, $format); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 col-md-4 text-right">
                        <?php echo Text::_('COM_TAGEBUCH_AENDER_VON').':'; ?>
                    </div>
                    <div class="col-8 col-md-8">
                        <?php echo ($this->item->fs_laenderung_von_name != '') ? $this->item->fs_laenderung_von_name : Text::_( '..' ) ; ?>
                    </div>
                </div>
            </div>
            <div class="col-9 col-md-9">
                <?php echo strlen($this->item->text_fs) > 0 ? $this->item->text_fs : HTMLHelper::image('images/blank.png' , Text::_('Empty') , $blank_image_attribs); ?>
            </div>
        </div>
    </div>

	<?php echo HTMLHelper::_('uitab.endTab'); ?>

    <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general2', Text::_('Zweiter Tab')); ?>
    <div>sound</div>
	<?php echo HTMLHelper::_('uitab.endTab'); ?>

    <?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general3', Text::_('Dritter Tab')); ?>
    <div class="hidden">soundso</div>
	<?php echo HTMLHelper::_('uitab.endTab'); ?>

	<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general4', Text::_('Vierter Tab')); ?>
    <div>soundsodele</div>
	<?php echo HTMLHelper::_('uitab.endTab'); ?>

	<?php echo HTMLHelper::_('uitab.endTabSet'); ?>


</div>
