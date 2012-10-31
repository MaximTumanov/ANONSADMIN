<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JPATH_COMPONENT.DS.'controller.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'helper.php' );

$controller = new MenusController( array('default_task' => 'viewMenus') );
$controller->registerTask('apply', 'save');
$controller->execute( JRequest::getCmd( 'task' ) );
$controller->redirect();