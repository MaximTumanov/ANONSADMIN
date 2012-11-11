<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class TOOLBAR_users
{
	function _DEFAULT()
	{
		$view = JRequest::getVar('view');
		$task = JRequest::getVar('task');
		if($view):
			$text = OurusersHelper::getMenuName($view);
			JToolBarHelper::title( JText::_( 'Пользователь' ).': <small>[ '. $text .' ]</small>', 'browser.png' );
		else:
			JToolBarHelper::title( JText::_( 'Пользователь' ), 'browser.png' );
		endif;
	}
}