<?php 
class FilmsControllerCinema extends FilmsController {
	/**
	* @var string $view вид
	*/
	var $view      = 'cinema';
	/**
	* @var string $component компонент
	*/
	var $component = 'com_films';
	var $model;
	
	function __construct(){
		parent::__construct();
		$this->registerTask('add', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('removeselect', 'removeSelect');
		$this->registerTask('save', 'save');
		$this->model = $this->getModel('Cinema');
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
	function publish(){
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_publish($cids);		
		$count = count($cid);
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));
	}
	/**
	* unpublish
	* снимает с публикации выбранные записи
	*/
	function unpublish(){
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_unpublish($cids);
		$count = count($cid);			
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));
	}
	/**
	* removeselect
	* удаляет выбранные записи
	*/
	function removeSelect(){
		$db  = & JFactory::getDBO();
		$cid = JRequest::getVar('cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_removeSelect($cids);
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));
		
	}
	/**
	 * save
	 * сохраняет выбранные записи
	 */
	function save() {
		
		$id    = JRequest::getInt('id');
		$title = mysql_real_escape_string(JRequest::getVar('title'));
		
		$alias = JString::translit(JRequest::getVar('alias'));
		
		if(empty($alias)):
			$alias = JString::translit($title);
		endif;
		
				
		$alias     = mysql_real_escape_string($alias);
		$desc      = mysql_real_escape_string(JRequest::getVar('desc', '', 'post', 'string', JREQUEST_ALLOWHTML));
		$address   = mysql_real_escape_string(JRequest::getVar('address'));
		$tel       = mysql_real_escape_string(JRequest::getVar('tel'));
		$k_title   = '';
		$k_desc    = '';
		$k_keyw    = '';
		$published  = JRequest::getInt('published');
		
		
		
		$google    = JRequest::getVar('g_x') . ':' . JRequest::getVar('g_y') . ':' . JRequest::getVar('g_zoom');
		//echo $google; die;
		$logo      = JRequest::getVar('image', null, 'files', 'array' );
		$logoHid   = JRequest::getVar('imageHid');

		if( $logo['name'] && $logo['name'] != '' ):
			$time = time();
			$ext = JString::extSpot($logo['type']);
			$tmp_dest 	= JPath::clean(JPATH_ROOT.DS.'images\sunny\films\cinema').DS.'pl_'.$time.$ext;
			$tmp_src	= $logo['tmp_name'];
			jimport('joomla.filesystem.file');
			JFile::upload($tmp_src, $tmp_dest);
			$img    = "pl_{$time}{$ext}";
		else:
			$img = $logoHid;
		endif;		

		if (!$id) {
			$this->model->_save($title, $alias, $img, $desc, $address, $tel, $k_title, $k_desc, $k_keyw, $google, $published);	
		} else {
			$this->model->_update($id, $title, $alias, $img, $desc, $address, $tel, $k_title, $k_desc, $k_keyw, $google, $published);			
		}
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));	
	}

}

?>