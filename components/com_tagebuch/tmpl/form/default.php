HUHU!!!
<?php

use Joomla\CMS\Factory;

$app = Factory::getApplication();
echo $app->getUserStateFromRequest("com_tagebuch.editpart", "editpart");
?>