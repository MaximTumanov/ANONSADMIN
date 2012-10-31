<?php 
jimport('joomla.application.component.controller');
/**
* Contact Controller
* @package		Anons
* @subpackage	Contact
*/
class ContactController extends JController
{
	/**
	* отображает вид
	* @access	public
	*/
	function display()
	{
		parent::display();
	}
	
	/**
	* отправляет письмо администратору с формы обратной связи
	* @return void
	*/
	function sendMessage()
	{
		$text        = mysql_real_escape_string(JRequest::getVar('text'));
		$email       = mysql_real_escape_string(JRequest::getVar('email'));
		$name        = mysql_real_escape_string(JRequest::getVar('name'));
		$admin_email = JRequest::getVar('admin_email');
		
		jimport( 'joomla.mail.mail' ); 
		$config =& JFactory::getConfig();
		$mail   =& JMail::getInstance(); 

		$mail->setBody($text); 
		$mail->setSubject( 'Вопрос с сайта '.$config->getValue('config.sitename') );
		$mail->setSender(array( $email, $name )); 
		$mail->isHTML( true ); 
		$mail->addRecipient( $admin_email );
		$mail->Send();	
	}
}
?>