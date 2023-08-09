<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Factory;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');
	//->useScript('com_content.form-edit');

$this->tab_name = 'com-tagebuch-form';
$this->ignore_fieldsets = ['image-intro', 'image-full', 'jmetadata', 'item_associations'];
$this->useCoreUI = true;

// Create shortcut to parameters.
$params = $this->state->get('params');

// This checks if the editor config options have ever been saved. If they haven't they will fall back to the original settings
if (!$params->exists('show_publishing_options')) {
	$params->set('show_urls_images_frontend', '0');
}
// Select tmpl to edit from editpart
$app = Factory::getApplication();
$subform = $app->getUserStateFromRequest("com_tagebuch.editpart", "editpart");


echo "Editpart = ".$app->getUserStateFromRequest("com_tagebuch.editpart", "editpart");
?>
<div class="edit item-page">
<form action="<?php echo Route::_('index.php?option=com_content&a_id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical">
    <fieldset>
        <?php
            echo $this->loadTemplate($subform);
        ?>
    </fieldset>
</form>
