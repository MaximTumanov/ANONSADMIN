<?php 

jimport('joomla.application.component.view');
class EventsViewParser extends JView {
	function display($tpl = null) {
		
		$layout = JRequest::getVar('layout');
		JToolBarHelper::save();

		$component = 'com_events';
		$view      = 'parser';

		$model = $this->getModel();
		global $mainframe;


		$catList    = EventsHelper::selectListMulti($model->getCategory(), 'id_category', '');
		$placeList  = EventsHelper::selectList($model->getPlaceList(), 'id_place', 'Выбирите организатора', 0);

		$this->assignRef('component', $component);
		$this->assignRef('view', $view);
		$this->assignRef('catList', $catList);
		$this->assignRef('placeList', $placeList);

		parent::display($tpl);
	}	
}
?>
