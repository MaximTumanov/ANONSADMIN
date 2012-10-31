<?php

define( '_JEXEC', 1 );
define('JPATH_BASE', dirname(__FILE__) );
define('DS', DIRECTORY_SEPARATOR);

require_once( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once( JPATH_BASE .DS.'includes'.DS.'helper.php' );
require_once( JPATH_BASE .DS.'includes'.DS.'toolbar.php' );

define('PUBLISHED', 1);
define('UNPUBLISHED', 0);

define('FIREPHPFRONT',              0);
define('MEMCACHE_ACTIV', 			0);
define('FIREPHPFRONT_SAVE_IN_FILE', 0);

define('LOCALHOST', '127.0.0.1');


JDEBUG ? $_PROFILER->mark( 'afterLoad' ) : null;

$mainframe =& JFactory::getApplication('administrator');
$mainframe->initialise(array(
	'language' => $mainframe->getUserState( "application.lang", 'lang' )
));

JPluginHelper::importPlugin('system');
JDEBUG ? $_PROFILER->mark('afterInitialise') : null;
$mainframe->triggerEvent('onAfterInitialise');
$mainframe->route();

JDEBUG ? $_PROFILER->mark('afterRoute') : null;
$mainframe->triggerEvent('onAfterRoute');

$option = JAdministratorHelper::findOption();
$mainframe->dispatch($option);

JDEBUG ? $_PROFILER->mark('afterDispatch') : null;
$mainframe->triggerEvent('onAfterDispatch');

$mainframe->render();

JDEBUG ? $_PROFILER->mark( 'afterRender' ) : null;
$mainframe->triggerEvent( 'onAfterRender' );

echo JResponse::toString($mainframe->getCfg('gzip'));
?>