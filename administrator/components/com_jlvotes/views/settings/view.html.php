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
jimport( 'joomla.application.component.view' );

class JlvotesViewSettings extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'Настройки голосований' ), 'generic.png' );
		JToolBarHelper::save();

		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__jlvotes_settings");
		$jlvotes_settings = $db->loadObjectList('name');
		foreach ($jlvotes_settings as $k=>$v) {
			$settings->$k = $v->value;
		}
		$this->assignRef('settings', $settings);
		parent::display($tpl);
	}
	
}
