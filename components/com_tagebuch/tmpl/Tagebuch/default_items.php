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
use SK\Component\Tagebuch\Site\Helper\RouteHelper as TagebuchHelperRoute;

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

	<tbody>
	<?php foreach ($this->items as $id => $item) :
		$slug = preg_replace('/[^a-z\d]/i', '-', $item->datum);
		$slug = strtolower(str_replace(' ', '-', $slug));
	?>
	<tr>
        <td class="text-center">
			<?php echo HTMLHelper::_('grid.id', $id, $item->id); ?>
        </td>
        <td class="class="article-status"">
		<?php echo $states[$item->state]; ?>
        </td>
        <th scope="row" class="has-context">
            <a class="hasTooltip" href="<?php echo Route::_('index.php?option=com_tagebuch&task=edit.edit&id=' . $item->id); ?>">
				<?php echo $editIcon; ?><?php echo $this->escape($item->datum); ?>
            </a>
        </th>
        <td class="">
			<?php echo $item->text_fs_bool>0 ? $yesIcon : $noIcon; ?>
        </td>
        <td class="">
			<?php echo $item->text_ss_bool>0 ? $yesIcon : $noIcon; ?>
        </td>
        <td class="">
			<?php echo $item->text_an_bool>0 ? $yesIcon : $noIcon; ?>
        </td>
        <td class="">
			<?php echo $item->text_bl_bool>0 ? $yesIcon : $noIcon; ?>
        </td>
        <td class="">
			<?php echo $item->text_z1_bool>0 ? $yesIcon : $noIcon; ?>
        </td>
        <td class="">
			<?php echo $item->text_z2_bool>0 ? $yesIcon : $noIcon; ?>
        </td>
        <td class="">
			<?php echo $item->gesehen>0 ? $yesIcon : $noIcon; ?>
        </td>
        <td class="">
			<?php echo $item->abluft_ok>0 ? $yesIcon : $noIcon; ?>
        </td>
	</tr>
	<?php endforeach; ?><?php //endif; ?>
	</tbody>

