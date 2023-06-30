<?php
/**
 * @version		$Id: sk_tagebuch.php 
 * @package		Joomla
 * @subpackage	sk_Tagebuch
 * @copyright	Copyright (C) 2009 Stephan Knauer. All rights reserved.
 * @license		No Commercial Use - See at http://joomla.sv-portal.de
 * @name 		views/New/tmpl/default.php
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
defined( 'SKCOMPONENT' ) or die( 'Restricted access' );

?>

<form action="<?php echo JRoute::_('index.php?option=com_tagebuch&view=new&id='.(int) $this->item->id); ?>" method="post" name="adminForm" >
<div class="subcolumns_highlight">
	<div class="c50l">
		<strong>
		<?php 
			echo $this->item->get('id') ? JText::_('Bearbeiten des Eintrages vom: ').@$this->item->datum_formatiert : JText::_('Neuer Eintrag erstellen:') ; 
		?>
		</strong>
	</div>
	<div class="c50r">
		<?php echo $this->auswahl_liste; ?>
	</div>
</div>
    
</form>
