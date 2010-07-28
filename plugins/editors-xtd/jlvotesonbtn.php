<?php
/**
 * JLVotes
 *
 * @version 1.3
 * @package com_jlvotes
 * @author Anton Voynov (anton@joomline.ru)
 * @copyright (C) 2010 by Anton Voynov(http://www.joomline.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

class plgButtonJlvotesonbtn extends JPlugin
{
       function plgButtonjlbutton(& $subject, $config)
       {
			parent::__construct($subject, $config);
       }

       function onDisplay($name)
       {
			global $mainframe;
			
			$js = "
				function insertJLVotesOn(editor) {
					var content = tinyMCE.get('text').getContent();
					if (content.match(/{jlvotes}/)) {
						return false;
					} else {
						jInsertEditorText('{jlvotes}', editor);
					}
				}
			";
			
			$doc 		=& JFactory::getDocument();
			$doc->addScriptDeclaration($js);

			$button = new JObject();
			$button->set('modal', false);
			$button->set('onclick', 'insertJLVotesOn(\''.$name.'\');return false;');
			$button->set('text', "JLVotes ON");
			$button->set('name', 'jlbuttonon');
			$button->set('link', '#');

			return $button;
       }
}?>
