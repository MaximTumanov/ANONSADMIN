<?php 
/**
* ContactControllerContact
* @package Content
* @subpackage Controller
*/
class ContactControllerContact extends ContactController
{
	/**
	* @var string $view вид
	*/
	var $view      = 'contact';
	/**
	* @var string $component компонент
	*/
	var $component = 'com_contact';
	var $model;
	
	function __construct()
	{
		parent::__construct();
		$this->registerTask('save', 'save');
		// получаем модель ContactModelContact
		$this->model = $this->getModel('Contact');
	}
	/**
	* сохраняет запись
	* @return ContactModelContact::_save()
	*/
	function save()
	{
		$g_x    = JRequest::getVar('g_x');
		$g_y    = JRequest::getVar('g_y');
		$g_zoom = JRequest::getVar('g_zoom');
		$g_key  = JRequest::getVar('g_key');
		$email  = JRequest::getVar('email');
		$text   = JRequest::getVar('text');
		$tel    = JRequest::getVar( 'tel', '', 'post', 'string', JREQUEST_ALLOWHTML );
		$adress = JRequest::getVar( 'adress', '', 'post', 'string', JREQUEST_ALLOWHTML );
				
		$this->model->_update($g_x, $g_y, $g_zoom, $g_key, $email, $text, $tel, $adress);	
		$this->setRedirect('index.php?option='.$this->component.'&view='.$this->view, JText::_('Изменения сохранены'));
				
	}

}

?>
