<?php
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.model' );
class ConfigModelComponent extends JModel
{
	function &getParams()
	{
		static $instance;

		if ($instance == null)
		{
			$component	= JRequest::getCmd( 'component' );

			$table =& JTable::getInstance('component');
			$table->loadByOption( $component );

			// work out file path
			if ($path = JRequest::getString( 'path' )) {
				$path = JPath::clean( JPATH_SITE.DS.$path );
				JPath::check( $path );
			} else {
				$option	= preg_replace( '#\W#', '', $table->option );
				$path	= JPATH_ADMINISTRATOR.DS.'components'.DS.$option.DS.'config.xml';
			}

			if (file_exists( $path )) {
				$instance = new JParameter( $table->params, $path );
			} else {
				$instance = new JParameter( $table->params );
			}
		}
		return $instance;
	}
}