<?php 
jimport('joomla.application.component.controller');
/**
* Content Controller
* @package		Anons
* @subpackage	Content
*/
class ContentController extends JController
{
	/**
	* отображает вид
	* @access	public
	*/
	function display()
	{
		global $mainframe;
		$data = false;
		// получаем текущий view
		$view = JRequest::getVar('view');
		// получаем текущий layout
		$layout = JRequest::getVar('layout');

		if( $view == 'services' || $view == 'item' || $view == 'news' ):
			// получаем список меню сайта
			$menu   =& JSite::getMenu();
			$menus	= $menu->getMenu();
			// получаем активный пункт меню
			$item   = $menu->getActive();

			if( $layout == 'item' ):
				$data[0] = new stdClass();
				$data[0]->name = $item->name;
				$data[0]->link = $item->link;				
			endif;
			
		endif;
		
		if( $view == 'articles' ):
			// получаем список меню сайта
			$menu   =& JSite::getMenu();
			$menus	= $menu->getMenu();
			// получаем активный пункт меню
			$item   = $menu->getActive();
						
			switch( $layout ):
				case'category':
					$data[0]       = new stdClass();
					$data[0]->name = $item->name;
					$data[0]->link = $item->link;				
				break;
				case'item':
					$data[0]       = new stdClass();
					$data[0]->name = $item->name;
					$data[0]->link = $item->link;
					
					$model       = $this->getModel('Articles');
					$id_category = JRequest::getInt('catid');
					$item        = $model->getCatData($id_category);
					$data[1]     = new stdClass();
					$data[1]->name = $item->title;
					$data[1]->link = JRoute::_("index.php?option=com_content&view=articles&layout=category&catid={$item->id_category}");
			endswitch;		
		endif;

		// устанавливаем хлебные крошки
		$pathway = &$mainframe->getPathWay();
		$pathway->updatePathWay($data);	
		
		parent::display();
	}
}
?>