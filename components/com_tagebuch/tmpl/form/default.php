HUHU!!!<p>
<?php

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->document->getWebAssetManager();
$wa->useScript('keepalive')
	->useScript('form.validate');

$this->useCoreUI = true;
$app = Factory::getApplication();
echo $app->getUserStateFromRequest("com_tagebuch.editpart", "editpart");
?>
</p>
<div class="edit item-page">
<form action="<?php echo Route::_('index.php?option=com_content&a_id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical">
    <fieldset>
<div>
	<?php   echo $this->form->renderField('id'); ?>
</div>
<div>
<?php   echo $this->form->renderField('datum'); ?>
</div>
<div>
	<?php   echo $this->form->renderField('alias'); ?>
</div>
<div>
	<?php   echo $this->form->renderField('sff'); ?>
</div>
<div>
	<?php   echo $this->form->renderField('sfan'); ?>
</div>
	<?php   echo $this->form->renderField('tex_fs'); ?>
    </fieldset>
</form>