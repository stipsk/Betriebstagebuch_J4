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
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.core');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$states = array (
	'0' => Text::_('JUNPUBLISHED'),
	'1' => Text::_('JPUBLISHED'),
	'2' => Text::_('JARCHIVED'),
	'-2' => Text::_('JTRASHED')
);
$editIcon = '<span class="fa fa-pen-square mr-2" aria-hidden="true"></span>';
$yesIcon = '<span class="fa fa-thumbs-up mr-2" aria-hidden="true" style="color:limegreen;"></span>';
$noIcon = '<span class="fa fa-thumbs-down mr-2" aria-hidden="true" style="color:coral;"></span>';

?>
<h1><?php echo Text::_('COM_TAGEBUCH_LIST_PAGE_HEADING'); ?></h1>
<div class="com-tagebuch-default tagebuch-list">
    <form action="<?php echo Route::_('index.php?option=com_tagebuch'); ?>" method="post" name="adminForm" id="adminForm">
        <div class="row">
            <div class="col-md-12">
                <div id="j-main-container" class="j-main-container">
					<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
					<?php if (empty($this->items)) : ?>
                        <div class="alert alert-info">
                            <span class="fa fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
							<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                        </div>
					<?php else : ?>
                    <table class="table" id="tagebuchList">
                        <caption id="captionTable">
							<?php echo Text::_('COM_TAGEBUCH_LIST_TABLE_CAPTION'); ?>
                        </caption>
                        <thead>
                        <tr>
                            <td style="width:1%" class="text-center">
								<?php echo HTMLHelper::_('grid.checkall'); ?>
                            </td>
                            <th scope="col" style="width:1%; min-width:85px" class="text-center">
								<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="width:20%">
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_TAGEBUCH_TAGEBUCH_LABEL_DATUM', 'a.datum', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="width:20%">
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_TAGEBUCH_TAGEBUCH_LABEL_FS_BOOL', 'a.text_fs_bool', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="width:10%">
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_TAGEBUCH_TAGEBUCH_LABEL_SS_BOOL', 'text_ss_bool', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="width:10%">
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_TAGEBUCH_TAGEBUCH_LABEL_AN_BOOL', 'a.text_an_bool', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="width:10%">
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_TAGEBUCH_TAGEBUCH_LABEL_BL_BOOL', 'a.text_bl_bool', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="width:10%">
		                        <?php echo HTMLHelper::_('searchtools.sort', 'COM_TAGEBUCH_TAGEBUCH_LABEL_Z1_BOOL', 'a.text_z1_bool', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="width:10%">
		                        <?php echo HTMLHelper::_('searchtools.sort', 'COM_TAGEBUCH_TAGEBUCH_LABEL_Z2_BOOL', 'a.text_z2_bool', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="width:5%" class="d-none d-md-table-cell">
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_TAGEBUCH_TAGEBUCH_LABEL_GESEHEN', 'a.gesehen', $listDirn, $listOrder); ?>
                            </th>
                            <th scope="col" style="width:5%" class="d-none d-md-table-cell">
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_TAGEBUCH_TAGEBUCH_LABEL_ABLUFT_OK', 'a.abluft_ok', $listDirn, $listOrder); ?>
                            </th>

                        </tr>
                        </thead>
	<?php
		echo $this->loadTemplate('items');
	?>
                        <tfoot>
                            <tr>
                                <td style="text-align: center">
                                    &nbsp;
                                </td>
                                <td colspan="5">
	                                <?php // load the pagination. ?>
	                                <?php echo $this->pagination->getListFooter(); ?>
                                </td>
                                <td colspan="5" style="text-align: center">
                                    &nbsp;
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <input type="hidden" name="task" value="">
        <input type="hidden" name="boxchecked" value="0">
	    <?php echo HTMLHelper::_('form.token'); ?>
    </form>
</div>