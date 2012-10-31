<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

class TOOLBAR_config
{
	function _DEFAULT() {

		JToolBarHelper::title( JText::_( 'Global Configuration' ), 'config.png' );
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel('cancel', 'Close');
	}
}