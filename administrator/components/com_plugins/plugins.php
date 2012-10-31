<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

$user = & JFactory::getUser();
if (!$user->authorize( 'com_plugins', 'manage' )) {
		$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

require_once( JPATH_COMPONENT.DS.'controller.php' );

$controller	= new PluginsController( );

$controller->execute( JRequest::getCmd( 'task' ) );
$controller->redirect();