<?php

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.html.parameter');
class mosUserParameters extends JParameter
{
	function __construct($text, $file = '', $type = 'component')
	{
		parent::__construct($text, $file);
	}
}
