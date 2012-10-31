<?php 
/**
* ContentControllerItem
* @package Content
* @subpackage Controller
*/
class ContentControllerItem extends ContentController
{
	/**
	* @var string $view вид
	*/	
	var $view      = 'item';
	/**
	* @var string $component компонент
	*/
	var $component = 'com_content';
	var $model;
	
	function __construct()
	{
		parent::__construct();
		$this->registerTask('add', 'edit');
		$this->registerTask('save', 'save');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		
		$this->registerTask('onfront','onfront');
		$this->registerTask('unonfront','unonfront');
		
		$this->registerTask('removeselect', 'removeSelect');
		$this->registerTask('saveorder', 'saveOrder');
		$this->registerTask('copyselect','copySelect');
		// получаем модель ContentModelItem
		$this->model = $this->getModel('Item');
	}
	/**
	* публикует запись на главной
	*/
	function onfront()
	{
		$id = JRequest::getVar('id');
		$this->model->_onfront($id);		
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));
	}
	/**
	* снимает публикацию записи с главной
	*/
	function unonfront()
	{
		$id = JRequest::getVar('id');
		$this->model->_unonfront($id);
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));
	}
	/**
	* копирует выбранные записи
	*/	
	function copySelect()
	{
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );	
		JArrayHelper::toInteger($cid);
		
		$db = &JFactory::getDBO();
		foreach( $cid as $key => $val ):
			$this->model->_copySelect($val);
		endforeach;
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));
	}
	/**
	* сохраняет порядок вывода записей
	*/
	function saveOrder()
	{
		$itemList = JRequest::getVar('item');
		foreach( $itemList as $key => $val ):
			$this->model->_saveOrder($val, $key);
		endforeach;
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));
	}
	/**
	* опубликовывает выбранные записи
	*/
	function publish()
	{
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_publish($cids);
		$count = count($cid);
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} материалов опубликовано"));
	}
	/**
	* снимает с публикации выбранные записи
	*/
	function unpublish()
	{
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_unpublish($cids);
		$count = count($cid);	
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} материалов снято с публикации"));
	}
	/**
	* удаляет выбранные записи
	*/
	function removeSelect()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_removeSelect($cids);
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Выбранные материалы и сопутствующие материалы успешно удалены'));
		
	}
	/**
	* выводит форму редактирования информации о материале
	*/			
	function edit()
	{
		JRequest::setVar('view', $this->view);
		$this->setRedirect("index.php?option={$this->component}&layout=form&view={$this->view}");
	}
	/**
	* редактирует запись
	*/		
	function save()
	{
		
		$id          = JRequest::getInt('id');
		$title       = mysql_real_escape_string(JRequest::getVar('title'));
		$published   = JRequest::getInt('published');
		$keywords    = mysql_real_escape_string(JRequest::getVar('keywords'));
		$date        = JRequest::getVar('date');
		$s_desc      = mysql_real_escape_string(JRequest::getVar( 's_desc', '', 'post', 'string', JREQUEST_ALLOWHTML ));
		$desc        = mysql_real_escape_string(JRequest::getVar( 'desc', '', 'post', 'string', JREQUEST_ALLOWHTML ));
				
		$id_section  = JRequest::getInt('id_section');
		$id_category = JRequest::getInt('id_category');
		
		$id_user     = JRequest::getInt('id_user');
		
		$alias       = JString::translit(JRequest::getVar('alias'));
		if( empty($alias) ):
			$alias = JString::translit($title);
		endif;
		$alias       = mysql_real_escape_string($alias);
		
		$maxOrdering = $this->model->getMaxOrdering();
		$ordering = ( $maxOrdering ) ? ($maxOrdering+1) : 1;

		$image       = JRequest::getVar('image', null, 'files', 'array' );
		$image_hid   = JRequest::getVar('image_hid');
		
		if( $image['name'] && $image['name'] != '' ):
			$time = time();
			$ext = JString::extSpot($image['type']);
			$tmp_dest 	= JPath::clean(JPATH_ROOT.DS.'images\Anons\content').DS.'item_'.$time.$ext;
			$tmp_src	= $image['tmp_name'];
			jimport('joomla.filesystem.file');
			JFile::upload($tmp_src, $tmp_dest);
			$img = "item_{$time}{$ext}";
		else:
			$img = $image_hid;
		endif;
		
		if( !$id):
			$this->model->_save($title, $alias, $s_desc, $desc, $keywords, $date, 0, $ordering, $published, $id_section, $id_category, $img, $id_user);	
			$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("Запись {$title} сохранена"));	
		else:
			$this->model->_update($id, $title, $alias, $s_desc, $desc, $keywords, $date, $published, $id_section, $id_category, $img, $id_user);	
			$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));
		endif;
				
	}
}

?>