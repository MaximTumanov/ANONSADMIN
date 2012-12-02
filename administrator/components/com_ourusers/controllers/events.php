<?php 
class OurusersControllerEvents extends OurusersController {
	/**
	* @var string $view вид
	*/
	var $view      = 'events';
	/**
	* @var string $component компонент
	*/
	var $component = 'com_ourusers';
	var $model;
	
	function __construct(){
		parent::__construct();
		$this->registerTask('add', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('vip', 'vip');
		$this->registerTask('unvip', 'unvip');
		$this->registerTask('removeselect', 'removeSelect');
		$this->registerTask('save', 'save');
		$this->registerTask('check', 'check');
		$this->model = $this->getModel('Events');
	}
	function edit(){
		JRequest::setVar('view', $this->view);
		JRequest::setVar('layout', 'form');
		parent::display();
	}

	function check(){
		$title = mysql_real_escape_string(JRequest::getVar('title'));
		echo json_encode($this->model->checkName($title));
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
	
	function vip()
	{
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_vip($cids);		
		$count = count($cid);
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} событий установлен статус VIP"));
	}

	function unvip()
	{
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_unvip($cids);
		$count = count($cid);		
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} событий снят статус VIP"));
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
		
		$title = mysql_real_escape_string(JRequest::getVar('title'));
		$alias = JString::translit(JRequest::getVar('alias'));
		
		if(empty($alias)):
			$alias = JString::translit($title);
		endif;
		
		$alias     = mysql_real_escape_string($alias);
				
		$address   = mysql_real_escape_string(JRequest::getVar('address'));
		$s_desc    = mysql_real_escape_string(JRequest::getVar('s_desc', '', 'post', 'string', JREQUEST_ALLOWHTML));
		$desc      = mysql_real_escape_string(JRequest::getVar('desc', '', 'post', 'string', JREQUEST_ALLOWHTML));
		
		$type       = JRequest::getInt('type');
		$vip        = JRequest::getInt('vip');
		$wtf        = JRequest::getInt('wtf');
		$published  = JRequest::getInt('published');
		
		$id_place   = JRequest::getInt('id_place', 0);
		$category   = JRequest::getVar('id_category');
			
		$image      = JRequest::getVar('image', null, 'files', 'array' );
		$imageHid   = JRequest::getVar('imageHid');		

		$k_title 		= mysql_real_escape_string(JRequest::getVar('k_title', ''));
		$k_desc 		= mysql_real_escape_string(JRequest::getVar('k_desc', ''));
		$k_keyw 		= mysql_real_escape_string(JRequest::getVar('k_keyw', ''));

		$price = JRequest::getVar('price');
        $vk_video = JRequest::getVar('vk_video');

		if( $image['name'] && $image['name'] != '' ):
			$time = time();
			$ext = JString::extSpot($image['type']);
			$tmp_dest 	= JPath::clean(JPATH_ROOT.DS.'images\sunny\events\events').DS.'e_'.$time.$ext;
			$tmp_src	= $image['tmp_name'];
			jimport('joomla.filesystem.file');
			JFile::upload($tmp_src, $tmp_dest);
			$img    = "e_{$time}{$ext}";
		else:
			$img = $imageHid;
		endif;			

		switch ($type) {
			case 1:
				$dates = new stdClass();
				$dates->date = JRequest::getVar('date');
				$dates->time = JRequest::getVar('hour') . ':' . JRequest::getVar('minut');
				break;
			case 2:
				$dates = new stdClass();
				$dates->dateFrom  = JRequest::getVar('date_from');
				$dates->dateTo    = JRequest::getVar('date_to');
				$dates->day  = JRequest::getInt('day');
				$dates->time = JRequest::getVar('hour') . ':' . JRequest::getVar('minut');
				break;
			case 3:
				$dates = new stdClass();
				$dates->dateFrom  = JRequest::getVar('date_from');
				$dates->dateTo    = JRequest::getVar('date_to');
				$dates->day  = JRequest::getInt('day');
				$dates->time = JRequest::getVar('hour') . ':' . JRequest::getVar('minut');
				break;
			case 4:
				break;
			case 5:
				$dates = new stdClass();
				$dates->dateFrom  = JRequest::getVar('date_from');
				$dates->dateTo    = JRequest::getVar('date_to');
				$dates->time = JRequest::getVar('hour') . ':' . JRequest::getVar('minut');
				break;
				case 6:
				$dates = new stdClass();
				$dates->date = JRequest::getVar('date');
				$dates->time = JRequest::getVar('hour') . ':' . JRequest::getVar('minut');
				break;
		}
		
		if (!$id) {
			$this->model->_save($title, $alias, $img, $s_desc, $desc, $address, $type, $vip, $wtf, $published, $category, $id_place, $dates, $k_title, $k_desc, $k_keyw, $price, $vk_video);
		} else {
			$this->model->_update($id, $title, $alias, $img, $s_desc, $desc, $address, $type, $vip, $wtf, $published, $category, $id_place, $dates, $k_title, $k_desc, $k_keyw, $price, $vk_video);
		}
		
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));	
	}	
}

?>