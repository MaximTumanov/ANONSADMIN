<?php 

jimport('joomla.application.component.view');
class ContactViewContact extends JView
{
	function display($tpl=null)
	{
		// проверяем данные сессии
		$session = &JFactory::getSession();
		$user    = unserialize($session->get('JUser'));
		$this->assignRef('user', $user);
		parent::display($tpl);
	}
}

?>