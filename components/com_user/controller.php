<?php 
jimport('joomla.application.component.controller');
class UserController extends JController {
	function display() {	
		parent::display();
	}
	
	function eventToList() {
		$id_event = JRequest::getInt('id_event');
		$id_user = substr(JRequest::getVar('id_user'), 32);
		$date = JRequest::getVar('date');
		$action = JRequest::getVar('action');
		$model = $this->getModel();
		
		if ($model->eventToList($id_event, $id_user, $date, $action)) {
			echo json_encode(array('error' => 0));
		} else {
			echo json_encode(array('error' => 1));
		}
	}
	
	function placeToList() {
		$id_place = JRequest::getInt('id_place');
		$id_user = substr(JRequest::getVar('id_user'), 32);
		$action = JRequest::getVar('action');
		$model = $this->getModel();
		
		if ($model->placeToList($id_place, $id_user, $action)) {
			echo json_encode(array('error' => 0));
		} else {
			echo json_encode(array('error' => 1));
		}
	}	
}
?>