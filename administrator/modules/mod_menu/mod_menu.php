<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( dirname(__FILE__).DS.'helper.php' );

$hide	= JRequest::getInt('hidemainmenu');

if ( $hide ) {
	modMenuHelper::buildDisabledMenu();
} else {
	modMenuHelper::buildMenu();
}

?>