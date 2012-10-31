<?php

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.controller');
jimport('joomla.client.helper');

class InstallerController extends JController
{

	function installform()
	{
		global $mainframe;

		$model	= &$this->getModel( 'Install' );
		$model->setState( 'install.directory', $mainframe->getCfg( 'config.tmp_path' ));

		$view	= &$this->getView( 'Install');

		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		$view->assignRef('ftp', $ftp);

		$view->setModel( $model, true );
		$view->display();
	}


	function doInstall()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$model	= &$this->getModel( 'Install' );
		$view	= &$this->getView( 'Install' );

		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		$view->assignRef('ftp', $ftp);

		if ($model->install()) {
			$cache = &JFactory::getCache('mod_menu');
			$cache->clean();
		}

		$view->setModel( $model, true );
		$view->display();
	}


	function manage()
	{
		$type	= JRequest::getWord('type', 'components');
		$model	= &$this->getModel( $type );
		$view	= &$this->getView( $type );

		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		$view->assignRef('ftp', $ftp);

		$view->setModel( $model, true );
		$view->display();
	}


	function enable()
	{
		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );

		$type	= JRequest::getWord('type', 'components');
		$model	= &$this->getModel( $type );
		$view	= &$this->getView( $type );

		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		$view->assignRef('ftp', $ftp);

		if (method_exists($model, 'enable')) {
			$eid = JRequest::getVar('eid', array(), '', 'array');
			JArrayHelper::toInteger($eid, array());
			$model->enable($eid);
		}

		$view->setModel( $model, true );
		$view->display();
	}


	function disable()
	{
		// Check for request forgeries
		JRequest::checkToken( 'request' ) or jexit( 'Invalid Token' );

		$type	= JRequest::getWord('type', 'components');
		$model	= &$this->getModel( $type );
		$view	= &$this->getView( $type );

		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		$view->assignRef('ftp', $ftp);

		if (method_exists($model, 'disable')) {
			$eid = JRequest::getVar('eid', array(), '', 'array');
			JArrayHelper::toInteger($eid, array());
			$model->disable($eid);
		}

		$view->setModel( $model, true );
		$view->display();
	}

	function remove()
	{
		// Check for request forgeries
		JRequest::checkToken() or jexit( 'Invalid Token' );

		$type	= JRequest::getWord('type', 'components');
		$model	= &$this->getModel( $type );
		$view	= &$this->getView( $type );

		$ftp =& JClientHelper::setCredentialsFromRequest('ftp');
		$view->assignRef('ftp', $ftp);

		$eid = JRequest::getVar('eid', array(), '', 'array');

		if((count($eid) == 1) && ($type == 'components') && (isset($eid[0]))) $eid = array($eid[0] => 0);

		JArrayHelper::toInteger($eid, array());
		$result = $model->remove($eid);

		$view->setModel( $model, true );
		$view->display();
	}
}