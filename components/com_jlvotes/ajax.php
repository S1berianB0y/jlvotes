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

// Set flag that this is a parent file
define( '_JEXEC', 1 );

chdir("../../");
define('JPATH_BASE', getcwd() );
$ss = JPATH_BASE;
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$mainframe =& JFactory::getApplication('site');

// set the language
$mainframe->initialise();

$content_id = JRequest::getInt('cid','');
$vtype 		= JRequest::getInt('vtype','');

$lang = JFactory::getLanguage();
$lang->load('plg_content_jlvotes');

$db = JFactory::getDBO();
$db->setQuery("SELECT * FROM #__jlvotes_settings");
$jlvotes_settings = $db->loadAssocList('name');

$user =& JFactory::getUser();
if ($user->guest) {
	if ($jlvotes_settings['allow_guest']['value'] != 1) {
		$response['text'] = '<span style="color: red; font-weight:bold">'.JText::_('AUTHREQUIRED').'</span>';
		$response['plus'] = '???';
		$response['minus'] = '???';
		echo json_encode($response);
		$mainframe->close();
	}
	$hash = md5($_SERVER['REMOTE_ADDR']);
} else {
	$hash = md5($user->id."|".$user->name);
}
if ($jlvotes_settings['allow_guest']['value'] == 1) {
	$db->setQuery("INSERT INTO `#__jlvotes_data` (`content_id`, `user`, `votetype`) VALUES ($content_id, '$hash', $vtype) ON DUPLICATE KEY UPDATE `votetype` = VALUES(`votetype`)");
} else {
	$db->setQuery("INSERT IGNORE INTO `#__jlvotes_data` (`content_id`, `user`, `votetype`) VALUES ($content_id, '$hash', $vtype)");
}

$db->query();

$db->setQuery("SELECT COUNT(*) as votecount, votetype FROM `#__jlvotes_data` WHERE `content_id` = $content_id GROUP BY votetype");
$votes = $db->loadObjectList('votetype');

//$response['text'] = $hash;
$response['plus'] = intval($votes['0']->votecount);
$response['minus'] = intval($votes['1']->votecount);
$response['text'] = '<span style="color: green; font-weight:bold">'.JText::_('VOICEPASSED').'</span>';

echo json_encode($response);
$mainframe->close();
