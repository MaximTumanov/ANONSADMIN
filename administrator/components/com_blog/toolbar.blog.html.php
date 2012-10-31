<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class TOOLBAR_blog
{
	function _DEFAULT()
	{
		$view = JRequest::getVar('view');
		$task = JRequest::getVar('task');
		if($view):
			$text = BlogHelper::getMenuName($view);
			if ($task) {
				$text .= " : " . BlogHelper::getPlaceTitle($view); 
			}
			JToolBarHelper::title( JText::_( 'Блог' ).': <small>[ '. $text .' ]</small>', 'browser.png' );
		else:
			JToolBarHelper::title( JText::_( 'Блог' ), 'browser.png' );
		endif;
	}
}