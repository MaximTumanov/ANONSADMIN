<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class TOOLBAR_events
{
	function _DEFAULT()
	{
		$view = JRequest::getVar('view');
		$task = JRequest::getVar('task');
		if($view):
			$text = EventsHelper::getMenuName($view);
			if ($task) {
				$text .= " : " . EventsHelper::getPlaceTitle($view); 
			}
			JToolBarHelper::title( JText::_( 'События' ).': <small>[ '. $text .' ]</small>', 'browser.png' );
		else:
			JToolBarHelper::title( JText::_( 'События' ), 'browser.png' );
		endif;
	}
}