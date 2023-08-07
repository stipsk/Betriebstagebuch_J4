<?php
/**
 * @version		$Id: sk_tagebuch.php 
 * @package		Joomla
 * @subpackage	sk_Tagebuch
 * @copyright	Copyright (C) 2009 Stephan Knauer. All rights reserved.
 * @license		No Commercial Use - See at http://joomla.sv-portal.de
 * @name 		views/New/tmpl/ss.php
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
//defined( 'SKCOMPONENT' ) or die( 'Restricted access' );
JHtml::_('behavior.keepalive');
//JHtml::_('behavior.formvalidation');
//JHtml::_('formbehavior.chosen', 'select');
//JHtml::_('behavior.modal', 'a.modal_jform_contenthistory');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'tagebuch.cancel' || document.formvalidator.isValid(document.getElementById('adminForm')))
		{
			<?php //echo $this->form->getField('text_ss')->save(); ?>
			Joomla.submitform(task);
		}
	}
</script>

    <div class="edit">
    <?php if ($this->state->get('show_page_heading')) : ?>
    <h1>
            <?php echo $this->escape($this->params->get('page_heading')); ?>
    </h1>
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

        <hr class="hr-condensed" />
        <?php //if ($this->params->get('author_luft',0)) : echo "Authorisiert für Luftängerungen"; endif;?>
        <?php //if ($this->params->get('author_efb',0)) : echo "<br />Verantwortlicher für Entsorgungsfachbetrieb"; endif;?>
        <?php
	$title = JText::_( 'COM_TAGEBUCH_EDIT_NOTW_EINGABEN' );
	echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'ss'));
        echo JHtml::_('bootstrap.addTab', 'myTab', 'ss', $title);
        
            echo $this->form->renderField('datum');
            echo $this->form->renderField('sfs');
            echo $this->form->renderField('state');
            echo $this->form->renderField('text_ss');
        
	$title = JText::_( 'COM_TAGEBUCH_EDIT_ZUS_EINGABEN' );
        echo JHtml::_('bootstrap.endTab'); 
        echo JHtml::_('bootstrap.addTab', 'myTab', 'extra', $title);
        
            echo $this->form->renderField('ss_erstellt_von');
            echo $this->form->renderField('ss_erstellt');
            echo $this->form->renderField('ss_laenderung_von');
            echo $this->form->renderField('ss_laenderung');

        if ($this->params->get('author_efb',false))
        {
            $title = JText::_( 'COM_TAGEBUCH_EDIT_ADMINMSG' );
            echo JHtml::_('bootstrap.endTab'); 
            echo JHtml::_('bootstrap.addTab', 'myTab', 'admin', $title);

                echo $this->msg;
        }   
        echo JHtml::_('bootstrap.endTab');    
        echo JHtml::_('bootstrap.endTabSet');
        ?>
        
        <input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
        <input type="hidden" name="layout" value="edit" />
        <input type="hidden" name="editpart" value="" />
        <input type="hidden" name="referer" value="<?php echo @$_SERVER['HTTP_REFERER']; ?>" />
        <input type="hidden" name="task" value="" />
        <?php echo JHTML::_( 'form.token' ); ?>
    </form>
    </div>