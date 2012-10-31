<?php
defined('JPATH_BASE') or die();
jimport('joomla.base.observable');
define('JAUTHENTICATE_STATUS_SUCCESS', 1);
define('JAUTHENTICATE_STATUS_CANCEL', 2);
define('JAUTHENTICATE_STATUS_FAILURE', 4);

class JAuthentication extends JObservable
{

	function __construct()
	{
		$isLoaded = JPluginHelper::importPlugin('authentication');

		if (!$isLoaded) {
			JError::raiseWarning('SOME_ERROR_CODE', JText::_('JAuthentication::__construct: Could not load authentication libraries.'));
		}
	}

	function & getInstance()
	{
		static $instances;

		if (!isset ($instances)) {
			$instances = array ();
		}

		if (empty ($instances[0])) {
			$instances[0] = new JAuthentication();
		}

		return $instances[0];
	}

	function authenticate($credentials, $options)
	{
		// Initialize variables
		$auth = false;

		// Get plugins
		$plugins = JPluginHelper::getPlugin('authentication');

		// Create authencication response
		$response = new JAuthenticationResponse();

		foreach ($plugins as $plugin)
		{
			$className = 'plg'.$plugin->type.$plugin->name;
			if (class_exists( $className )) {
				$plugin = new $className($this, (array)$plugin);
			}

			// Try to authenticate
			$plugin->onAuthenticate($credentials, $options, $response);

			// If authentication is successfull break out of the loop
			if($response->status === JAUTHENTICATE_STATUS_SUCCESS)
			{
				if (empty( $response->type )) {
					$response->type = isset( $plugin->_name ) ? $plugin->_name : $plugin->name;
				}
   				if (empty( $response->username )) {
					$response->username = $credentials['username'];
				}

				if (empty( $response->fullname )) {
					$response->fullname = $credentials['username'];
				}

				if (empty( $response->password )) {
					$response->password = $credentials['password'];
				}

				break;
			}
		}
		return $response;
	}
}


class JAuthenticationResponse extends JObject
{

	var $status 		= JAUTHENTICATE_STATUS_FAILURE;
	var $type 		= '';
	var $error_message 	= '';
	var $username 		= '';
	var $password 		= '';
	var $email			= '';
	var $fullname 		= '';
	var $birthdate	 	= '';
	var $gender 		= '';
	var $postcode 		= '';
	var $country 		= '';
	var $language 		= '';
	var $timezone 		= '';

	function __construct() { }
}
