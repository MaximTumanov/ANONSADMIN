<?php 

jimport('joomla.application.component.view');
class OurusersViewEvents extends JView {
	function display($tpl = null) {
		
		$layout = JRequest::getVar('layout');
		if(isset($layout)):
			JToolBarHelper::back();
			JToolBarHelper::save();
		else:
			//JToolBarHelper::addNewX();
			JToolBarHelper::customX( 'removeselect', 'delete.png', 'trash_f2.png', 'Trash' );
		endif;
		
		$model = $this->getModel();
		global $mainframe;
		
		$id    = JRequest::getVar('id');
		$component = 'com_ourusers';
		$view      = 'events';
		$lists['order']		  = $mainframe->getUserStateFromRequest("{$component}.{$view}.filter_order", 'filter_order', 'c.published', 'cmd');
		$lists['order_Dir']	  = $mainframe->getUserStateFromRequest("{$component}.{$view}.order_Dir", 'filter_order_Dir',	'DESC',	'word'); 
		$limit		= $mainframe->getUserStateFromRequest('global.list.limit',      'limit',      $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest("{$component}.{$view}.limitstart", 'limitstart', 0, 'int');	
		$items      = $model->getItems($lists, $limit, $limitstart);

		$total      = $model->getItemsCount($lists);
		$page       = new JPagination($total, $limitstart, $limit);
		
		$this->assignRef('items', $items);
		$this->assignRef('page', $page);
		$this->assignRef('lists', $lists );
		$this->assignRef('component', $component);
		$this->assignRef('view', $view);
		parent::display($tpl);
	}	
}
?>
