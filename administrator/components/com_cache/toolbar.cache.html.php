<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class TOOLBAR_cache
{

	function _DEFAULT() {

		JToolBarHelper::title( JText::_( 'Cache Manager - Clean Cache Admin' ), 'checkin.png' );
		JToolBarHelper::custom( 'delete', 'delete.png', 'delete_f2.png', 'Delete', true );
	}

	function _PURGEADMIN() {

		JToolBarHelper::title( JText::_( 'Cache Manager - Purge Cache Admin' ), 'checkin.png' );
		JToolBarHelper::custom( 'purge', 'delete.png', 'delete_f2.png', 'Purge expired', false );
	}
}