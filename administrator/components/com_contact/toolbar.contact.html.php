<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class TOOLBAR_contact
{
	function _DEFAULT()
	{
		JToolBarHelper::title( JText::_( 'Контакты' ).': <small>[ редактирование ]</small>', 'contacts.png' );
	}
}