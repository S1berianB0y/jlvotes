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
jimport( 'joomla.application.component.model' );
class JlvotesModelSettings extends JModel {

	var $_settings;
	

	function __construct() {
		parent::__construct();
	}

	function getSettings() {
		if (empty( $this->_settings )) {
			$query = "";
			$this->_settings = "";
		}

		return $this->_settings;
	}
	
	function store() {
		$allow_guest 	= JRequest::getInt('allow_guest');
		$add2all		= JRequest::getInt('add2all');
		$allow_revote	= JRequest::getInt('allow_revote');
		
		$db = JFactory::getDBO();
		$db->setQuery("
			INSERT INTO #__jlvotes_settings 
				(`name`,`value`) 
			VALUES 
				('allow_guest','$allow_guest'),('add2all','$add2all'),('allow_revote',$allow_revote)
			ON DUPLICATE KEY UPDATE
				`value` = VALUES (`value`)
		");
		if ($db->query()) return true; else return false;
		
	}
	
}
