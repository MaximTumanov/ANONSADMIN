<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

class plgXMLRPCJoomla extends JPlugin
{

	function plgXMLRPCJoomla(& $subject, $config)
	{
		parent::__construct($subject, $config);
	}

	function onGetWebServices()
	{
		global $xmlrpcString;

		// Initialize variables
		$services = array();

		// Site search service
		$services['joomla.searchSite'] = array(
			'function' => 'plgXMLRPCJoomlaServices::searchSite',
			'docstring' => 'Searches a remote site.',
			'signature' => array(array($xmlrpcString, $xmlrpcString, $xmlrpcString))
			);

		return $services;
	}
}

class plgXMLRPCJoomlaServices
{

	function searchSite($searchword, $phrase='', $order='')
	{
		global $mainframe;

		// Initialize variables
		$db		=& JFactory::getDBO();

		// Prepare arguments
		$searchword	= $db->getEscaped( trim( $searchword ) );
		$phrase		= '';
		$ordering	= '';

		// Load search plugins and fire the onSearch event
		JPluginHelper::importPlugin( 'search' );
		$results = $mainframe->triggerEvent( 'onSearch', array( $searchword, $phrase, $ordering ) );

		// Iterate through results building the return array
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_search'.DS.'helpers'.DS.'search.php');

		foreach ($results as $i=>$rows)
		{
			foreach ($rows as $j=>$row) {
				$results[$i][$j]->href = preg_match('#^(http|https)://#i', $row->href) ? $row->href : JURI::root().'/'.$row->href;
				$results[$i][$j]->text = SearchHelper::prepareSearchContent( $row->text, 200, $searchword);
			}
		}
		return $results;
	}
}
