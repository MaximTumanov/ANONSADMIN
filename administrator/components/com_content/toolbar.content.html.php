<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class TOOLBAR_content
{
	function _DEFAULT()
	{
		$view = JRequest::getVar('view');
		if($view):
			$text = ContentHelper::getMenuName($view);
			JToolBarHelper::title( JText::_( 'Менеджер материалов' ).': <small>[ '.$text.' ]</small>', 'article.png' );
		else:
			JToolBarHelper::title( JText::_( 'Менеджер материалов' ), 'article.png' );
		endif;
	}
}