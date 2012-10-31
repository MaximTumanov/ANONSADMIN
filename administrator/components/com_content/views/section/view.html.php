<?php 

jimport( 'joomla.application.component.view');
class ContentViewSection extends JView
{
	function display($tpl = null)
	{	
		$layout = JRequest::getVar('layout');
		if(isset($layout)):
			JToolBarHelper::back();
			JToolBarHelper::save();
		else:
			JToolBarHelper::addNewX();
			JToolBarHelper::customX( 'copyselect', 'copy.png', 'copy.png', 'Copy' );
			JToolBarHelper::customX( 'removeselect', 'delete.png', 'trash_f2.png', 'Trash' );
			JToolBarHelper::customX( 'publish', 'publish.png', 'publish_f2.png', 'Publish' );
			JToolBarHelper::customX( 'unpublish', 'unpublish.png', 'unpublish_f2.png', 'Unpublish' );

		endif;	

		$model = $this->getModel();
		global $mainframe;
		
		$id    = JRequest::getVar('id'); 

		$lists['order']		        = $mainframe->getUserStateFromRequest( "com_content.section.filter_order",     'filter_order',     'c.ordering', 'cmd' );
		$lists['order_Dir']	= $mainframe->getUserStateFromRequest( "com_content.section.order_Dir", 'filter_order_Dir', 'ASC',        'word' );

		
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit',      'limit',      $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( 'com_content.section.limitstart', 'limitstart', 0,                                'int' );

			$items      = $model->getItems($lists, $limit, $limitstart);
	
			$total      = $model->getItemsCount();
			$page       = new JPagination($total, $limitstart, $limit);

		$this->assignRef('items', $items);
		$this->assignRef('page', $page);
		$this->assignRef( 'lists', $lists );
		parent::display($tpl);
	}	
}

?>