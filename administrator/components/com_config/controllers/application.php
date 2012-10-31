<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once( JPATH_COMPONENT.DS.'views'.DS.'application'.DS.'view.php' );
class ConfigControllerApplication extends ConfigController
{

	function __construct( $default = array() )
	{
		$default['default_task'] = 'showConfig';
		parent::__construct( $default );

		$this->registerTask( 'apply', 'save' );
	}

	function showConfig()
	{
		$db =& JFactory::getDBO();
		$row = new JConfig();

		$langs 		= array ();
		$menuitems 	= array ();
		$lists 		= array ();

		// PRE-PROCESS SOME LIST

		// -- Editors --

		// compile list of the editors
		$query = 'SELECT element AS value, name AS text'
				.' FROM #__plugins'
				.' WHERE folder = "editors"'
				.' AND published = 1'
				.' ORDER BY ordering, name'
				;
		$db->setQuery($query);
		$edits = $db->loadObjectList();

		// -- Show/Hide --

		$show_hide		= array (JHTML::_('select.option', 1, JText::_('Hide')), JHTML::_('select.option', 0, JText::_('Show')),);

		$show_hide_r 	= array (JHTML::_('select.option', 0, JText::_('Hide')), JHTML::_('select.option', 1, JText::_('Show')),);

		// -- menu items --

		$query = 'SELECT id AS value, name AS text FROM #__menu'
				.' WHERE ( type="content_section" OR type="components" OR type="content_typed" )'
				.' AND published = 1'
				.' AND access = 0'
				.' ORDER BY name'
				;
		$db->setQuery($query);
		$menuitems = array_merge($menuitems, $db->loadObjectList());

		// SITE SETTINGS
		$lists['offline'] = JHTML::_('select.booleanlist', 'offline', 'class="inputbox"', $row->offline);
		if (!$row->editor) {
			$row->editor = '';
		}
		// build the html select list
		$lists['editor'] 		= JHTML::_('select.genericlist',  $edits, 'editor', 'class="inputbox" size="1"', 'value', 'text', $row->editor);
		$listLimit 				= array (JHTML::_('select.option', 5, 5), JHTML::_('select.option', 10, 10), JHTML::_('select.option', 15, 15), JHTML::_('select.option', 20, 20), JHTML::_('select.option', 25, 25), JHTML::_('select.option', 30, 30), JHTML::_('select.option', 50, 50), JHTML::_('select.option', 100, 100),);
		$lists['list_limit'] 	= JHTML::_('select.genericlist',  $listLimit, 'list_limit', 'class="inputbox" size="1"', 'value', 'text', ($row->list_limit ? $row->list_limit : 50));

		// DEBUG
		$lists['debug'] 		= JHTML::_('select.booleanlist', 'debug', 'class="inputbox"', $row->debug);
		
		// SEO SETTINGS
		$lists['sef'] 		= JHTML::_('select.booleanlist', 'sef', 'class="inputbox"', $row->sef);
		$lists['sef_rewrite'] 	= JHTML::_('select.booleanlist', 'sef_rewrite', 'class="inputbox"', $row->sef_rewrite);
		$lists['sef_suffix'] 	= JHTML::_('select.booleanlist', 'sef_suffix', 'class="inputbox"', $row->sef_suffix);
		
		// DATABASE SETTINGS

		// SERVER SETTINGS
		$lists['gzip'] 			= JHTML::_('select.booleanlist', 'gzip', 'class="inputbox"', $row->gzip);
		$errors 				= array (JHTML::_('select.option', -1, JText::_('System Default')), JHTML::_('select.option', 0, JText::_('None')), JHTML::_('select.option', E_ERROR | E_WARNING | E_PARSE, JText::_('Simple')), JHTML::_('select.option', E_ALL, JText::_('Maximum')));
		$lists['error_reporting'] = JHTML::_('select.genericlist',  $errors, 'error_reporting', 'class="inputbox" size="1"', 'value', 'text', $row->error_reporting);


		// MAIL SETTINGS
		$mailer = array (
			JHTML::_('select.option', 'mail', JText::_('PHP mail function')),
			);
		$lists['mailer'] = JHTML::_('select.genericlist',  $mailer, 'mailer', 'class="inputbox" size="1"', 'value', 'text', $row->mailer);

		// CACHE SETTINGS
		$lists['caching'] = JHTML::_('select.booleanlist', 'caching', 'class="inputbox"', $row->caching);
		jimport('joomla.cache.cache');
		$stores = JCache::getStores();
		$options = array();
		foreach($stores as $store) {
			$options[] = JHTML::_('select.option', $store, JText::_(ucfirst($store)) );
		}
		$lists['cache_handlers'] = JHTML::_('select.genericlist',  $options, 'cache_handler', 'class="inputbox" size="1"', 'value', 'text', $row->cache_handler);

		// MEMCACHE SETTINGS
		if (!empty($row->memcache_settings) && !is_array($row->memcache_settings)) {
			$row->memcache_settings = unserialize(stripslashes($row->memcache_settings));
		}
		$lists['memcache_persist'] = JHTML::_('select.booleanlist', 'memcache_settings[persistent]', 'class="inputbox"', @$row->memcache_settings['persistent']);
		$lists['memcache_compress'] = JHTML::_('select.booleanlist', 'memcache_settings[compression]', 'class="inputbox"', @$row->memcache_settings['compression']);


		// SESSION SETTINGS
		$stores = JSession::getStores();
		$options = array();
		foreach($stores as $store) {
			$options[] = JHTML::_('select.option', $store, JText::_(ucfirst($store)) );
		}
		$lists['session_handlers'] = JHTML::_('select.genericlist',  $options, 'session_handler', 'class="inputbox" size="1"', 'value', 'text', $row->session_handler);
		
		// SHOW EDIT FORM
		ConfigApplicationView::showConfig($row, $lists);
	}

	/**
	 * Save the configuration
	 */
	function save()
	{
		global $mainframe;

		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );


		//Save user and media manager settings
		$table =& JTable::getInstance('component');

		$userpost['params'] = JRequest::getVar('userparams', array(), 'post', 'array');
		$userpost['option'] = 'com_users';
		$table->loadByOption( 'com_users' );
		$table->bind( $userpost );

		// pre-save checks
		if (!$table->check()) {
			JError::raiseWarning( 500, $table->getError() );
			return false;
		}

		// save the changes
		if (!$table->store()) {
			JError::raiseWarning( 500, $table->getError() );
			return false;
		}

		$mediapost['params'] = JRequest::getVar('mediaparams', array(), 'post', 'array');
		$mediapost['option'] = 'com_media';
		//Sanitize $file_path and $image_path
		$file_path = $mediapost['params']['file_path'];
		$image_path = $mediapost['params']['image_path'];
		if(strpos($file_path, '/') === 0 || strpos($file_path, '\\') === 0) {
			//Leading slash.  Kill it and default to /media
			$file_path = 'images';
		}
		if(strpos($image_path, '/') === 0 || strpos($image_path, '\\') === 0) {
			//Leading slash.  Kill it and default to /media
			$image_path = 'images/stories';
		}
		if(strpos($file_path, '..') !== false) {
			//downward directories.  Kill it and default to images/
			$file_path = 'images';
		}
		if(strpos($image_path, '..') !== false) {
			//downward directories  Kill it and default to images/stories
			$image_path = 'images/stories';
		}
		$mediapost['params']['file_path'] = $file_path;
		$mediapost['params']['image_path'] = $image_path;

		$table->loadByOption( 'com_media' );
		$table->bind( $mediapost );

		// pre-save checks
		if (!$table->check()) {
			JError::raiseWarning( 500, $table->getError() );
			return false;
		}

		// save the changes
		if (!$table->store()) {
			JError::raiseWarning( 500, $table->getError() );
			return false;
		}

		$config = new JRegistry('config');
		$config_array = array();

		// SITE SETTINGS
		$config_array['offline']	= JRequest::getVar('offline', 0, 'post', 'int');
		$config_array['editor']		= JRequest::getVar('editor', 'tinymce', 'post', 'cmd');
		$config_array['list_limit']	= JRequest::getVar('list_limit', 20, 'post', 'int');

		// DEBUG
		$config_array['debug']		= JRequest::getVar('debug', 0, 'post', 'int');
		$config_array['in_production']		= JRequest::getVar('in_production', 0, 'post', 'int');

		// SERVER SETTINGS
		$config_array['secret']				= JRequest::getVar('secret', 0, 'post', 'string');
		$config_array['gzip']				= JRequest::getVar('gzip', 0, 'post', 'int');
		$config_array['error_reporting']	= JRequest::getVar('error_reporting', -1, 'post', 'int');
		$config_array['log_path']			= JRequest::getVar('log_path', JPATH_ROOT.DS.'logs', 'post', 'string');
		$config_array['tmp_path']			= JRequest::getVar('tmp_path', JPATH_ROOT.DS.'tmp', 'post', 'string');
		$config_array['live_site'] 			= rtrim(JRequest::getVar('live_site','','post','string'), '/\\');

	
		// SEO SETTINGS
		$config_array['sef']			= JRequest::getVar('sef', 0, 'post', 'int');
		$config_array['sef_rewrite']	= JRequest::getVar('sef_rewrite', 0, 'post', 'int');
		$config_array['sef_suffix']		= JRequest::getVar('sef_suffix', 0, 'post', 'int');
		
		// CACHE SETTINGS
		$config_array['caching']			= JRequest::getVar('caching', 0, 'post', 'int');
		$config_array['cachetime']			= JRequest::getVar('cachetime', 900, 'post', 'int');
		$config_array['cache_handler']		= JRequest::getVar('cache_handler', 'file', 'post', 'word');
		$config_array['memcache_settings']	= JRequest::getVar('memcache_settings', array(), 'post');

		// DATABASE SETTINGS
		$config_array['dbtype']		= JRequest::getVar('dbtype', 'mysql', 'post', 'word');
		$config_array['host']		= JRequest::getVar('host', 'localhost', 'post', 'string');
		$config_array['user']		= JRequest::getVar('user', '', 'post', 'string');
		$config_array['db']		= JRequest::getVar('db', '', 'post', 'string');
		$config_array['dbprefix']	= JRequest::getVar('dbprefix', 'jos_', 'post', 'string');

		// MAIL SETTINGS
		$config_array['mailer']		= JRequest::getVar('mailer', 'mail', 'post', 'word');
		$config_array['mailfrom']	= JRequest::getVar('mailfrom', '', 'post', 'string');
		$config_array['fromname']	= JRequest::getVar('fromname', 'Anons 1.5', 'post', 'string');

		// SESSION SETTINGS
		$config_array['lifetime']			= JRequest::getVar('lifetime', 0, 'post', 'int');
		$config_array['session_handler']	= JRequest::getVar('session_handler', 'none', 'post', 'word');

	
		// MAIL TEMPLATES
		$config_array['register_tmpl'] = htmlspecialchars( JRequest::getVar( 'register_tmpl', '', 'post', 'string' ),  ENT_COMPAT, 'UTF-8' );
		$config_array['reset_tmpl']    = htmlspecialchars( JRequest::getVar( 'reset_tmpl', '', 'post', 'string' ),  ENT_COMPAT, 'UTF-8' );
		
		
		$config->loadArray($config_array);

		//override any possible database password change
		$config->setValue('config.password', $mainframe->getCfg('password'));

		// handling of special characters
		$sitename			= htmlspecialchars( JRequest::getVar( 'sitename', '', 'post', 'string' ), ENT_COMPAT, 'UTF-8' );
		$config->setValue('config.sitename', $sitename);

		$MetaDesc			= htmlspecialchars( JRequest::getVar( 'MetaDesc', '', 'post', 'string' ),  ENT_COMPAT, 'UTF-8' );
		$config->setValue('config.MetaDesc', $MetaDesc);

		$MetaKeys			= htmlspecialchars( JRequest::getVar( 'MetaKeys', '', 'post', 'string' ),  ENT_COMPAT, 'UTF-8' );
		$config->setValue('config.MetaKeys', $MetaKeys);

		// handling of quotes (double and single) and amp characters
		// htmlspecialchars not used to preserve ability to insert other html characters
		$offline_message	= JRequest::getVar( 'offline_message', '', 'post', 'string' );
		$offline_message	= JFilterOutput::ampReplace( $offline_message );
		$offline_message	= str_replace( '"', '&quot;', $offline_message );
		$offline_message	= str_replace( "'", '&#039;', $offline_message );
		$config->setValue('config.offline_message', $offline_message);

		//purge the database session table (only if we are changing to a db session store)
		if($mainframe->getCfg('session_handler') != 'database' && $config->getValue('session_handler') == 'database')
		{
			$table =& JTable::getInstance('session');
			$table->purge(-1);
		}

		// Get the path of the configuration file
		$fname = JPATH_CONFIGURATION.DS.'configuration.php';

		$cache = JFactory::getCache();
		$cache->clean();

		// Try to make configuration.php writeable
		jimport('joomla.filesystem.path');
		if (!$ftp['enabled'] && JPath::isOwner($fname) && !JPath::setPermissions($fname, '0644')) {
			JError::raiseNotice('SOME_ERROR_CODE', 'Could not make configuration.php writable');
		}

		// Get the config registry in PHP class format and write it to configuation.php
		jimport('joomla.filesystem.file');
		if (JFile::write($fname, $config->toString('PHP', 'config', array('class' => 'JConfig')))) {
			$msg = JText::_('The Configuration Details have been updated');
		} else {
			$msg = JText::_('ERRORCONFIGFILE');
		}

		// Redirect appropriately
		$task = $this->getTask();
		switch ($task) {
			case 'apply' :
				$this->setRedirect('index.php?option=com_config', $msg);
				break;

			case 'save' :
			default :
				$this->setRedirect('index.php', $msg);
				break;
		}

	}

	/**
	 * Cancel operation
	 */
	function cancel()
	{
		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		$this->setRedirect( 'index.php' );
	}

	function refreshHelp()
	{
		jimport('joomla.filesystem.file');

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		JClientHelper::setCredentialsFromRequest('ftp');

		if (($data = file_get_contents('http://help.joomla.org/helpsites-15.xml')) === false ) {
			$this->setRedirect('index.php?option=com_config', JText::_('HELPREFRESH ERROR FETCH'), 'error');
		} else if (!JFile::write(JPATH_BASE.DS.'help'.DS.'helpsites-15.xml', $data)) {
			$this->setRedirect('index.php?option=com_config', JText::_('HELPREFRESH ERROR STORE'), 'error');
		} else {
			$this->setRedirect('index.php?option=com_config', JText::_('HELPREFRESH SUCCESS'));
		}
	}
}
