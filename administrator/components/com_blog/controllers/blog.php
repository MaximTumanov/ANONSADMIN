<?php 
class BlogControllerBlog extends BlogController {
	/**
	* @var string $view вид
	*/
	var $view      = 'blog';
	/**
	* @var string $component компонент
	*/
	var $component = 'com_blog';
	var $model;
	
	function __construct(){
		parent::__construct();
		$this->registerTask('add', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('removeselect', 'removeSelect');
		$this->registerTask('save', 'save');
		$this->model = $this->getModel('Blog');
	}
	function edit(){
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
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} событий опубликовано"));
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
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} событий снято с публикации"));
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
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Выбранные события успешно удалены'));
		
	}
	/**
	 * save
	 * сохраняет выбранные записи
	 */
	function save(){
		$id = JRequest::getInt('id');
		
		$title = JRequest::getVar('title');
		$alias = JString::translit(JRequest::getVar('alias'));
		
		if(empty($alias)):
			$alias = JString::translit($title);
		endif;
		
		$alias     = mysql_real_escape_string($alias);
		$s_desc    = mysql_real_escape_string(JRequest::getVar('s_desc', '', 'post', 'string', JREQUEST_ALLOWHTML));
		$desc      = mysql_real_escape_string(JRequest::getVar('desc', '', 'post', 'string', JREQUEST_ALLOWHTML));
		
		$published  = JRequest::getInt('published');	

		$k_title 		= mysql_real_escape_string(JRequest::getVar('k_title', ''));
		$k_desc 		= mysql_real_escape_string(JRequest::getVar('k_desc', ''));
		$k_keyw 		= mysql_real_escape_string(JRequest::getVar('k_keyw', ''));
		
		$date       = JRequest::getVar('date');

		$image      = JRequest::getVar('image', null, 'files', 'array' );
		$imageHid   = JRequest::getVar('imageHid');		

		$images_folder = JRequest::getVar('images_folder');

		if( $image['name'] && $image['name'] != '' ):
			$time = time();
			$ext = JString::extSpot($image['type']);
			$tmp_dest 	= JPath::clean(JPATH_ROOT.DS.'images\sunny\blog').DS.'e_'.$time.$ext;
			$tmp_src	= $image['tmp_name'];
			jimport('joomla.filesystem.file');
			JFile::upload($tmp_src, $tmp_dest);
			$img    = "e_{$time}{$ext}";
		else:
			$img = $imageHid;
		endif;


		if (!$id) {
			$this->model->_save($title, $alias, $s_desc, $desc, $date, $published, $k_title, $k_desc, $k_keyw, $img, $images_folder);
		} else {
			$this->model->_update($id, $title, $alias, $s_desc, $desc, $date, $published, $k_title, $k_desc, $k_keyw, $img, $images_folder);
		}
		
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));	
	}	
}

?>