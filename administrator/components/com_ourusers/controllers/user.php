<?php 
class OurusersControllerUser extends OurusersController {
	/**
	* @var string $view вид
	*/
	var $view      = 'user';
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
		$this->model = $this->getModel('User');
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
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} пользователей опубликовано"));
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
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} пользователей снято с публикации"));
	}
	
	function vip()
	{
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_vip($cids);		
		$count = count($cid);
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} пользователей установлен статус VIP"));
	}

	function unvip()
	{
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		JArrayHelper::toInteger($cid);
		$cids   = implode(',', $cid);
		
		$this->model->_unvip($cids);
		$count = count($cid);		
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_("{$count} пользователей снят статус VIP"));
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
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Выбранные пользователи успешно удалены'));
		
	}
	/**
	 * save
	 * сохраняет выбранные записи
	 */
	function save(){
		$id = JRequest::getInt('id');

		$public = JRequest::getInt('public');
		$vip = JRequest::getInt('vip');
	
		$new = ($public == 1 ) ? 0 : 1;
		$public = (JRequest::getInt('denied', 0)) ? 2 : $public;

		$this->model->_update($id, $public, $vip, $new);		
		$this->setRedirect("index.php?option={$this->component}&view={$this->view}", JText::_('Изменения сохранены'));	
	}	
}

?>