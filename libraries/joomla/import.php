<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

if (! class_exists('JLoader')) {
    require_once( JPATH_LIBRARIES.DS.'loader.php');
}
JLoader::import( 'joomla.base.object' 			);
JLoader::import( 'joomla.environment.request'   );
JRequest::clean();
JLoader::import( 'joomla.environment.response'  );
JLoader::import( 'joomla.factory' 				);
JLoader::import( 'joomla.version' 				);
if (!defined('JVERSION')) {
	$version = new JVersion();
	define('JVERSION', $version->getShortVersion());
}

JLoader::import( 'joomla.error.error' 			);
JLoader::import( 'joomla.error.exception' 		);
JLoader::import( 'joomla.utilities.arrayhelper' );
JLoader::import( 'joomla.filter.filterinput'	);
JLoader::import( 'joomla.filter.filteroutput'	);
JLoader::register('JText' , dirname(__FILE__).DS.'methods.php');
JLoader::register('JRoute', dirname(__FILE__).DS.'methods.php');
