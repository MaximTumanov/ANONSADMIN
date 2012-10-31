<?php 
class EventsControllerCategory extends EventsController {
	/**
	* @var string $view вид
	*/
	var $view      = 'category';
	/**
	* @var string $component компонент
	*/
	var $component = 'com_events';
	var $model;
	
	function __construct(){
		parent::__construct();
		$this->registerTask('add', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('removeselect', 'removeSelect');
		$this->registerTask('save', 'save');
		$this->model = $this->getModel('Category');
	}
	function edit()
	{
		JRequest::setVar('view', $this->view);
		JRequest::setVar('layout', 'form');
		parent::display();
	}
	/**
	* publish
	* опубликовывает выбранные записи
	*/	
	function publish()
	{
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_publish($cids);		
		$count = count($cid);
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} комментариев опубликовано"));
	}
	/**
	* unpublish
	* снимает с публикации выбранные записи
	*/
	function unpublish()
	{
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_unpublish($cids);
		$count = count($cid);			
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} комментариев снято с публикации"));
	}
	/**
	* removeselect
	* удаляет выбранные записи
	*/
	function removeSelect()
	{
		$db  = & JFactory::getDBO();
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_removeSelect($cids);
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Выбранные комментариев успешно удалены'));
		
	}
	/**
	 * save
	 * сохраняет выбранные записи
	 */
	function save() {
		
		$id    = JRequest::getInt('id');
		$title = JRequest::getVar('title');
		$alias = JString::translit(JRequest::getVar('alias'));
		if( empty($alias) ):
			$alias = JString::translit($title);
		endif;
		$alias     = mysql_real_escape_string($alias);
				
		$published = JRequest::getInt('published');
		
		$logo      = JRequest::getVar('icon', null, 'files', 'array' );
		$logoHid   = JRequest::getVar('iconHid');
		
		if( $logo['name'] && $logo['name'] != '' ):
			$time = time();
			$ext = JString::extSpot($logo['type']);
			$tmp_dest 	= JPath::clean(JPATH_ROOT.DS.'images\sunny\events\category').DS.'cat_'.$time.$ext;
			$tmp_src	= $logo['tmp_name'];
			jimport('joomla.filesystem.file');
			JFile::upload($tmp_src, $tmp_dest);
			$img    = "cat_{$time}{$ext}";
		else:
			$img = $logoHid;
		endif;		
		
		if (!$id) {
			$this->model->_save($title, $alias, $img, $published);	
		} else {
			$this->model->_update($id, $title, $alias, $img, $published);			
		}
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));	
	}

}

?>