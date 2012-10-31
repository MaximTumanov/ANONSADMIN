<?php

class InstallerHelper
{
	function writable( $folder )
	{
		return is_writable( JPATH_ROOT.DS.$folder )
			? '<strong><span class="writable">'.JText::_( 'Writable' ).'</span></strong>'
			: '<strong><span class="unwritable">'.JText::_( 'Unwritable' ).'</span></strong>';
	}
}