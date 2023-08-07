<?php
/**
 * @version		$Id: sk_tagebuch.php 
 * @package		Joomla
 * @subpackage	sk_Tagebuch
 * @copyright	Copyright (C) 2009 Stephan Knauer. All rights reserved.
 * @license		No Commercial Use - See at http://joomla.sv-portal.de
 * @name 		views/New/tmpl/fs.php
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
//defined( 'SKCOMPONENT' ) or die( 'Restricted access' );
//JHtml::_('behavior.tabstate');
JHtml::_('behavior.keepalive');
//JHtml::_('behavior.calendar');
//JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select');

JFactory::getDocument()->addScriptDeclaration("
	Joomla.submitbutton = function(task)
	{
		if (document.formvalidator.isValid(document.getElementById('adminForm')))
		{
                
                        if
                        ( 
                            confirm('" . JText::_('Wurden die Fahrzeugzuweisungen überprüft und, wenn notwendig, angepasst?')."')
                        ){
                           " . $this->form->getField('text_fs')->save() . "
                            Joomla.submitform(task);  
                        }else{
                            return false;
                        }
                }
	}
");
?>

<div class="edit item-page<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($this->$params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>
        <form action="<?php echo JRoute::_('index.php?option=com_tagebuch&view=new&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical">
                <div class="btn-toolbar">
                        <div class="btn-group">
                                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('new.save')">
                                        <span class="icon-ok"></span> <?php echo JText::_('JSAVE') ?>
                                </button>
                        </div>
                        <div class="btn-group">
                                <button type="button" class="btn" onclick="Joomla.submitbutton('new.cancel')">
                                        <span class="icon-cancel"></span> <?php echo JText::_('JCANCEL') ?>
                                </button>
                        </div>
                        <?php if ($this->params->get('save_history', 0)) : ?>
                                <div class="btn-group">
                                        <?php echo $this->form->getInput('contenthistory'); ?>
                                </div>
                        <?php endif; ?>
                </div>

                <fieldset>
                        <ul class="nav nav-tabs">
                                <li class="active"><a href="#editor" data-toggle="tab"><?php echo JText::_('COM_TAGEBUCH_EDIT_NOTW_EINGABEN') ?></a></li>
                                <li><a href="#extra" data-toggle="tab"><?php echo JText::_('COM_TAGEBUCH_EDIT_ZUS_EINGABEN') ?></a></li>
                                <?php if ( $this->params->get('author_efb',false) ) : ?>
                                <li><a href="#admin" data-toggle="tab"><?php echo JText::_('COM_TAGEBUCH_EDIT_ADMINMSG') ?></a></li>
                                <?php endif; ?>
                                
                        </ul>

                        <div class="tab-content">
                                <div class="tab-pane active" id="editor">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?php echo $this->form->renderField('datum');?>
                                            <?php echo $this->form->renderField('sff');?>
                                            <?php echo $this->form->renderField('state');?>
                                        </div>
                                        <div class="col-md-4">
                                            <?php echo $this->form->renderField('bereich_fahrzeug1');?>
                                            <?php echo $this->form->renderField('bereich_fahrzeug2');?>
                                        </div>
                                        <div class="col-md-4">
                                            <?php echo $this->form->renderField('bereich_fahrzeug3');?>
                                            <?php echo $this->form->renderField('bereich_fahrzeug4');?>
                                        </div>
                                    </div>
                                    <?php echo $this->form->renderField('text_fs');?>
                                </div>
                                <div class="tab-pane" id="extra">
                                        <?php echo $this->form->renderField('fs_erstellt_von');?>
                                        <?php echo $this->form->renderField('fs_erstellt');?>
                                        <?php echo $this->form->renderField('fs_laenderung_von');?>
                                        <?php echo $this->form->renderField('fs_laenderung');?>
                                </div>
                                <?php if ($this->params->get('author_efb',false)): ?>
                                <div class="tab-pane" id="admin">
                                        <?php echo $this->msg;?>
                                        
                                </div>
                                <?php endif;?>
                        </div>
                        <input type="hidden" name="task" value="" />
                        <input type="hidden" name="layout" value="FS" />
                        <input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
                        <?php echo JHtml::_('form.token'); ?>
                </fieldset>
        </form>
</div>