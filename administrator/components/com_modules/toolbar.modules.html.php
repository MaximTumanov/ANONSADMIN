<?php

class TOOLBAR_modules {
	function _NEW($client)
	{
		JToolBarHelper::title( JText::_( 'Module' ) . ': <small><small>[ '. JText::_( 'New' ) .' ]</small></small>', 'module.png' );
		JToolBarHelper::customX( 'edit', 'forward.png', 'forward_f2.png', 'Next', true );
		JToolBarHelper::cancel();
	}
	function _EDIT( $client )
	{
		$moduleType = JRequest::getCmd( 'module' );
		$cid 		= JRequest::getVar( 'cid', array(0), '', 'array' );
		JArrayHelper::toInteger($cid, array(0));

		JToolBarHelper::title( JText::_( 'Module' ) . ': <small><small>[ '. JText::_( 'Edit' ) .' ]</small></small>', 'module.png' );

		if($moduleType == 'custom') {
			JToolBarHelper::Preview('index.php?option=com_modules&tmpl=component&client='.$client->id.'&pollid='.$cid[0]);
		}

		JToolBarHelper::save();
		JToolBarHelper::apply();
		if ( $cid[0] ) {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		} else {
			JToolBarHelper::cancel();
		}
	}

	function _DEFAULT($client)
	{
		JToolBarHelper::title( JText::_( 'Module Manager' ), 'module.png' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::custom( 'copy', 'copy.png', 'copy_f2.png', 'Copy', true );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
	}
}
