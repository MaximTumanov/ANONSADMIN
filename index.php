<?php
define( '_JEXEC', 1 );
define('JPATH_BASE', dirname(__FILE__) );
define( 'DS', DIRECTORY_SEPARATOR );

define('NUM_CROP_STR',     10);

define('PUBLISHED',      			1);
define('UNPUBLISHED',    			0);

define('MEMCACHE_ACTIV', 			0);
define('FIREPHPFRONT',              0);
define('FIREPHPFRONT_SAVE_IN_FILE', 0);
define('LOCALHOST',                 '127.0.0.1');

define('NO_IMAGE_NEWS',    'no_image.jpg');
define('NO_IMAGE_ARTICLE', 'no_image.jpg');

define('EVENT_IMG_FRONT', '?w=120&h=145&tc&ns');
define('EVENT_IMG_DESC', '?h=230&w=330&tc&ns');
define('EVENT_IMG_RELATED', '?w=220&h=265&tc&ns');

define('PLACE_IMG_ALL', '?w=130&h=100&tc&ns');
define('EVENT_IMG_ALL', '?w=130&h=100&tc&ns');

define('ITEMS_PER_PAGE', 10);

//Itemid
define('ITEMID_EVENTS', 35);
define('ITEMID_PLACES', 34);
define('MY_ITEMID', 36);

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

JDEBUG ? $_PROFILER->mark( 'afterLoad' ) : null;

$mainframe =& JFactory::getApplication('site');

$mainframe->initialise();

JPluginHelper::importPlugin('system');

JDEBUG ? $_PROFILER->mark('afterInitialise') : null;
$mainframe->triggerEvent('onAfterInitialise');

$mainframe->route();

// authorization
$Itemid = JRequest::getInt( 'Itemid' );
$mainframe->authorize($Itemid);

// trigger the onAfterRoute events
JDEBUG ? $_PROFILER->mark('afterRoute') : null;
$mainframe->triggerEvent('onAfterRoute');

$option = JRequest::getCmd('option');
$mainframe->dispatch($option);

// trigger the onAfterDispatch events
JDEBUG ? $_PROFILER->mark('afterDispatch') : null;
$mainframe->triggerEvent('onAfterDispatch');

$mainframe->render();

// trigger the onAfterRender events
JDEBUG ? $_PROFILER->mark('afterRender') : null;
$mainframe->triggerEvent('onAfterRender');

echo JResponse::toString($mainframe->getCfg('gzip'));