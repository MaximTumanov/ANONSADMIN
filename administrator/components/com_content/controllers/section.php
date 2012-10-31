<?php 
/**
* ContentControllerSection
* @package Content
* @subpackage Controller
*/
class ContentControllerSection extends ContentController
{
	/**
	* @var string $view вид
	*/	
	var $view      = 'section';
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
		$this->registerTask('removeselect', 'removeSelect');
		$this->registerTask('saveorder', 'saveOrder');
		$this->registerTask('copyselect','copyselect');
		// получаем модель ContentModelSection
		$this->model = $this->getModel('Section');
	}
	/**
	* копирует выбранные записи
	*/	
	function copyselect()
	{
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );	
		JArrayHelper::toInteger($cid);
		foreach( $cid as $key => $val ):
			$this->model->_copeselect($val);			
		endforeach;
		$this->setRedirect('index.php?option='.$this->component.'&view='.$this->view, JText::_('Изменения сохранены'));
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
		$this->setRedirect('index.php?option='.$this->component.'&view='.$this->view, JText::_('Изменения сохранены'));
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
		$this->setRedirect('index.php?option='.$this->component.'&view='.$this->view, JText::_(''.count($cid).' разделов опубликовано'));
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
		$this->setRedirect('index.php?option='.$this->component.'&view='.$this->view, JText::_(''.count($cid).' разделов снято с публикации'));
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
		$this->setRedirect('index.php?option='.$this->component.'&view='.$this->view, JText::_('Выбранные разделы и сопутствующие материалы успешно удалены'));
		
	}
	/**
	* выводит форму редактирования информации о разделе
	*/		
	function edit()
	{
		JRequest::setVar('view', $this->view);
		JRequest::setVar('layout', 'form');
		parent::display();
	}
	/**
	* редактирует запись
	* @return ContentModelSection::_save() если новая запись или ContentModelSection::_update() если редактируется существующая
	*/		
	function save()
	{
		
		$id          = JRequest::getVar('id');
		$title       = mysql_real_escape_string(JRequest::getVar('title'));
		$published   = JRequest::getVar('published');
		
		$alias       = JString::translit(JRequest::getVar('alias'));
		if( empty($alias) ):
			$alias = JString::translit($title);
		endif;
		$alias       = mysql_real_escape_string($alias);
			
		$maxOrdering = $this->model->getMaxOrdering();
		$ordering = ( $maxOrdering ) ? ($maxOrdering+1) : 1;
	
		if( !$id ):
			$this->model->_save($title, $alias, $published, $ordering);
			$this->setRedirect('index.php?option='.$this->component.'&view='.$this->view, JText::_('Раздел '.$title.' сохранен'));	
		else:
			$this->model->_update($id, $title, $alias, $published);	
			$this->setRedirect('index.php?option='.$this->component.'&view='.$this->view, JText::_('Изменения сохранены'));
		endif;
				
	}

}

?>