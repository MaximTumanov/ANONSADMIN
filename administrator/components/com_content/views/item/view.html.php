<?php 

jimport( 'joomla.application.component.view');
class ContentViewItem extends JView
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

		$lists['order']		        = $mainframe->getUserStateFromRequest( "com_content.item.filter_order",     'filter_order',     'i.ordering', 'cmd' );
		$lists['order_Dir']     	= $mainframe->getUserStateFromRequest( "com_content.item.order_Dir",        'filter_order_Dir', 'ASC',        'word' );
		$lists['id_section']	    = $mainframe->getUserStateFromRequest( "com_content.item.id_section",       'id_section',       '0',          'int' ); 
		$lists['id_category']	    = $mainframe->getUserStateFromRequest( "com_content.item.id_category",      'id_category',      '0',          'int' ); 		
		
		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit',      'limit',      $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( 'com_content.item.limitstart', 'limitstart', 0,                                'int' );

			$items      = $model->getItems($lists, $limit, $limitstart);
	
			$total      = $model->getItemsCount($lists);
			$page       = new JPagination($total, $limitstart, $limit);
		
		$sectionList  = ContentHelper::selectList($model->getSectionList(),'id_section','Выбирите раздел', $lists['id_section']);
		$categoryList = ContentHelper::selectList($model->getCategoryList(),'id_category','Выбирите категорию', $lists['id_category']);
		
		$this->assignRef('items', $items);
		$this->assignRef('page', $page);
		$this->assignRef('lists', $lists );
		$this->assignRef('sectionList', $sectionList);
		$this->assignRef('categoryList', $categoryList);
		parent::display($tpl);
	}	
}

?>