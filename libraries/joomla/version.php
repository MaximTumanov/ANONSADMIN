<?php
defined('JPATH_BASE') or die();
class JVersion
{
	var $PRODUCT 	= '';
	var $RELEASE 	= '1.0';
	var $DEV_STATUS = 'Stable';
	var $DEV_LEVEL 	= '1';
	var $BUILD	    = '';
	var $CODENAME 	= '';
	var $RELDATE 	= '2011.01.01';
	var $RELTIME 	= '04:00';
	var $RELTZ 	    = 'GMT';
	var $COPYRIGHT 	= '';
	var $URL 	= '<a href="http://www.anons.dp.ua"></a>';

	function getLongVersion()
	{
		return $this->PRODUCT .' '. $this->RELEASE .'.'. $this->DEV_LEVEL .' '
			. $this->DEV_STATUS
			.' [ '.$this->CODENAME .' ] '. $this->RELDATE .' '
			. $this->RELTIME .' '. $this->RELTZ;
	}

	function getShortVersion() {
		return $this->RELEASE .'.'. $this->DEV_LEVEL;
	}

	function getHelpVersion()
	{
		if ($this->RELEASE > '1.0') {
			return '.' . str_replace( '.', '', $this->RELEASE );
		} else {
			return '';
		}
	}

	function isCompatible ( $minimum ) {
		return (version_compare( JVERSION, $minimum, 'eq' ) == 1);
	}
}
