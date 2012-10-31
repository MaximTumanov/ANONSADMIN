<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class TOOLBAR_films
{
	function _DEFAULT()
	{
		$view = JRequest::getVar('view');
		$task = JRequest::getVar('task');
		if($view):
			$text = FilmsHelper::getMenuName($view);
			if ($task) {
				$text .= " : " . FilmsHelper::getCinemaTitle($view); 
			}
			JToolBarHelper::title( JText::_( 'Фильмы' ).': <small>[ '. $text .' ]</small>', 'browser.png' );
		else:
			JToolBarHelper::title( JText::_( 'Фильмы' ), 'browser.png' );
		endif;
	}
}