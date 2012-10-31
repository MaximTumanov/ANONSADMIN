<?php 
jimport('joomla.application.component.view');
class UserViewUser extends JView {
	function display($tpl=null) {
		$ITEMID_PLACES = ITEMID_PLACES;
		$ITEMID_EVENTS = ITEMID_EVENTS;
		$this->assignRef('ITEMID_PLACES', $ITEMID_PLACES);
		$this->assignRef('ITEMID_EVENTS', $ITEMID_EVENTS);
		parent::display($tpl);
	}
}

?>