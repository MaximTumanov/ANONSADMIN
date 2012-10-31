<?php

defined('_JEXEC') or die('Restricted access');

$user = & JFactory::getUser();
if (!$user->authorize( 'com_config', 'manage' )) {
	$mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
}

require_once (JPATH_COMPONENT.DS.'controller.php');

if($controller = JRequest::getWord('controller', 'application')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

$classname	= 'ConfigController'.ucfirst($controller);
$controller	= new $classname( );

JResponse::setHeader( 'Expires', 'Mon, 26 Jul 1997 05:00:00 GMT', true );

$controller->execute( JRequest::getCmd( 'task' ) );
$controller->redirect();