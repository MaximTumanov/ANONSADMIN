<?php 
class FilmsControllerFilms extends FilmsController {
	/**
	* @var string $view РІРёРґ
	*/
	var $view      = 'films';
	/**
	* @var string $component РєРѕРјРїРѕРЅРµРЅС‚
	*/
	var $component = 'com_films';
	var $model;
	
	function __construct(){
		parent::__construct();
		$this->registerTask('add', 'edit');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('premiere', 'premiere');
		$this->registerTask('unpremiere', 'unpremiere');
		$this->registerTask('removeselect', 'removeSelect');
		$this->registerTask('save', 'save');
		$this->model = $this->getModel('Films');
	}
	function edit(){
		JRequest::setVar('view', $this->view);
		JRequest::setVar('layout', 'form');
		parent::display();
	}
	/**
	* publish
	* РѕРїСѓР±Р»РёРєРѕРІС‹РІР°РµС‚ РІС‹Р±СЂР°РЅРЅС‹Рµ Р·Р°РїРёСЃРё
	*/	
	function publish()
	{
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_publish($cids);		
		$count = count($cid);
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} Изменения сохранены"));
	}
	/**
	* unpublish
	* СЃРЅРёРјР°РµС‚ СЃ РїСѓР±Р»РёРєР°С†РёРё РІС‹Р±СЂР°РЅРЅС‹Рµ Р·Р°РїРёСЃРё
	*/
	function unpublish()
	{
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_unpublish($cids);
		$count = count($cid);			
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} Изменения сохранены"));
	}
	
	function premiere()
	{
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_premiere($cids);		
		$count = count($cid);
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} Изменения сохранены"));
	}

	function unpremiere()
	{
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_unvip($cids);
		$count = count($cid);		
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} Изменения сохранены"));
	}
	/**
	* removeselect
	* СѓРґР°Р»СЏРµС‚ РІС‹Р±СЂР°РЅРЅС‹Рµ Р·Р°РїРёСЃРё
	*/
	function removeSelect()
	{
		$db  = & JFactory::getDBO();
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_removeSelect($cids);
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));
		
	}
	/**
	 * save
	 * СЃРѕС…СЂР°РЅСЏРµС‚ РІС‹Р±СЂР°РЅРЅС‹Рµ Р·Р°РїРёСЃРё
	 */
	function save(){
		$id = JRequest::getInt('id');
		
		$title = JRequest::getVar('title');
		//$alias = JString::translit(JRequest::getVar('alias'));
		
		$id_cinema   = JRequest::getInt('id_cinema', 0);

		$original_name = mysql_real_escape_string(JString::translit($title));

		$alias = JString::translit($title) . '-' . $id_cinema;
						
		$address   = mysql_real_escape_string(JRequest::getVar('address'));
		$s_desc    = mysql_real_escape_string(JRequest::getVar('s_desc', '', 'post', 'string', JREQUEST_ALLOWHTML));
		$desc      = mysql_real_escape_string(JRequest::getVar('desc', '', 'post', 'string', JREQUEST_ALLOWHTML));
		$video    = mysql_real_escape_string(JRequest::getVar('video', '', 'post', 'string', JREQUEST_ALLOWHTML));
		$type       = mysql_real_escape_string(JRequest::getInt('type'));
		$premiere        = JRequest::getInt('premiere');
		$genre   = mysql_real_escape_string(JRequest::getVar('genre'));
		$duration   = mysql_real_escape_string(JRequest::getVar('duration'));
		$director   = mysql_real_escape_string(JRequest::getVar('director'));
		$country   = mysql_real_escape_string(JRequest::getVar('country'));
		$actors   = mysql_real_escape_string(JRequest::getVar('actors'));
		$_3d        = JRequest::getInt('_3d');
		$published  = JRequest::getInt('published');
		
		$date_first = JRequest::getVar('date_first', '');
		$date_last = JRequest::getVar('date_last', '');
		
		$id_cinema   = JRequest::getInt('id_cinema', 0);
			
		$image      = JRequest::getVar('image', null, 'files', 'array' );
		$imageHid   = JRequest::getVar('imageHid');		

		$k_title 		= JRequest::getVar('k_title', '');
		$k_desc 		= JRequest::getVar('k_desc', '');
		$k_keyw 		= JRequest::getVar('k_keyw', '');

		$time_at    = JRequest::getVar('time_at', array());
		

		foreach($time_at as $key => $val){
		  if (empty($val)){
		  	unset($time_at[$key]);
		  }
		}

		if( $image['name'] && $image['name'] != '' ):
			$time = time();
			$ext = JString::extSpot($image['type']);
			$tmp_dest 	= JPath::clean(JPATH_ROOT.DS.'images\sunny\films\films').DS.'e_'.$time.$ext;
			$tmp_src	= $image['tmp_name'];
			jimport('joomla.filesystem.file');
			JFile::upload($tmp_src, $tmp_dest);
			$img    = "e_{$time}{$ext}";
		else:
			$img = $imageHid;
		endif;			

				$time = JRequest::getVar('hour') . ':' . JRequest::getVar('minut');

		
		if (!$id) {
			$this->model->_save($title, $alias, $img, $s_desc, $desc, $premiere, $video, $date_first, $date_last, $genre, $duration, $director, $country, $actors, $_3d, $published, $id_cinema, $time, $k_title, $k_desc, $k_keyw, $time_at, $original_name);
		} else {
			$this->model->_update($id, $title, $alias, $img, $s_desc, $desc, $premiere, $video, $date_first, $date_last, $genre, $duration, $director, $country, $actors, $_3d, $published, $id_cinema, $time, $k_title, $k_desc, $k_keyw, $time_at, $original_name);
		}
		
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));	
	}	
}

?>d