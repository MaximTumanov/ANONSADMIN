<?php
/**
* @version		$Id: admin.cpanel.php 10381 2008-06-01 03:35:53Z pasamio $
* @package		Anons
* @subpackage	Admin
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Anons! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once( JApplicationHelper::getPath( 'admin_html' ) );

switch (JRequest::getCmd('task'))
{
	default:
	{
		//set the component specific template file in the request
		JRequest::setVar('tmpl', 'cpanel');
		HTML_cpanel::display();
	}	break;
}