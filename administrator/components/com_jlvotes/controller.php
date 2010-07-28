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
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');


class JlvotesController extends JController
{
	function display() {
		parent::display();
	}
	
	function settings() {
		JRequest::setVar( 'view', 'settings' );
		parent::display();
	}
	
	function save() {
		$model = $this->getModel('settings');

		if ($model->store()) {
			$msg = JText::_( 'SETTINGSSAVED' );
		} else {
			$msg = JText::_( 'ERSAVE' );
		}

		$this->setRedirect('index.php?option=com_jlvotes', $msg);
	}
	
	
	
}
