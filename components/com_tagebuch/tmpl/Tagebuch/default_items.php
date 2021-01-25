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
use Joomla\CMS\Router\Route;
use J4xdemos\Component\Mywalks\Site\Helper\RouteHelper as MywalksHelperRoute;

?>
<div class="table-responsive">
  <table class="table table-striped">
  <caption><?php echo Text::_('COM_TAGEBUCH_LIST_TABLE_CAPTION'); ?></caption>
  <thead>
    <tr>
 		<th scope="col"><?php echo Text::_('COM_TAGEBUCH_LIST_DATUM'); ?></th>
		<th scope="col"><?php echo Text::_('COM_TAGEBUCH_LIST_TEXT_FS'); ?></th>
		<th scope="col"><?php echo Text::_('COM_TAGEBUCH_LIST_TEXT_SS'); ?></th>
		<th scope="col"><?php echo Text::_('COM_TAGEBUCH_LIST_TEXT_AN'); ?></th>
		<th scope="col"><?php echo Text::_('COM_TAGEBUCH_LIST_TEXT_BL'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($this->items as $id => $item) :
		$slug = preg_replace('/[^a-z\d]/i', '-', $item->datum);
		$slug = strtolower(str_replace(' ', '-', $slug));
	?>
	<tr>
		<td><a href="<?php echo Route::_(TagebuchHelperRoute::getEditRoute($item->id, $slug)); ?>">
		<?php echo $item->datum; ?></a></td>
		<td><?php echo $item->text_fs; ?></td>
		<td><?php echo $item->text_ss; ?></td>
		<td><?php echo $item->text_an; ?></td>
		<td><?php echo $item->text_bl; ?></td>
	</tr>
	<?php endforeach; ?><?php //endif; ?>
	</tbody>
  </table>
</div>
