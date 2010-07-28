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

// Require the base controller

require_once( JPATH_COMPONENT.DS.'controller.php' );

// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

// Create the controller
$classname	= 'JlvotesController'.$controller;
$controller	= new $classname( );

// Perform the Request task
$task = JRequest::getVar( 'task','settings' );
if ($task == "settings") {
	JRequest::setVar('task', 'settings');  
	JSubMenuHelper::addEntry(JText::_( 'SETTINGS' ), 'index.php?option=com_jlvotes&task=settings',	true );
}


$controller->execute( $task );

// Redirect if set by the controller
$controller->redirect();
?>

