<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

$user = & JFactory::getUser();
if (!$user->authorize( 'com_users', 'manage' )) {
	$mainframe->redirect( 'index.php', JText::_('ALERTNOTAUTH') );
}

require_once (JPATH_COMPONENT.DS.'controller.php');
$controller	= new UsersController( );
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();