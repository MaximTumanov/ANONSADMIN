<?php 
jimport('joomla.application.component.model');
/**
* ContactModelContact
* @package Content
* @subpackage Model
*/
class ContactModelContact extends JModel
{
	/**
	* @var string $table таблица с сотрудниками
	*/	
	var $table = '#__contact';
	/**
	* @var string $id поле в таблице с PRIMARY KEY
	*/	
	var $id    = 'id';
	/**
	 * возвращает контактную информацию
	 * @return object 
	 */
	function getItem()
	{	
		$db = &JFactory::getDBO();
		$q = " SELECT * FROM `{$this->table}` WHERE id = '1' ";
		$db->setQuery($q);
		return $db->loadObject();
	}
	/**
	* перезаписывает данные
	* @return void
	*/
	/**
	 * 
	 * перезаписывает контактную информацию
	 * @param int    $g_x     GPS-широта маркера
	 * @param int    $g_y     GPS-долгота маркера
	 * @param int    $g_zoom  приближение
	 * @param string $g_key   Google API Key
	 * @param string $email   контактный email
	 * @param string $text    текст маркера
	 * @param string $tel     контактные телефоны
	 * @param string $adress  адреса
	 */
	function _update($g_x, $g_y, $g_zoom, $g_key, $email, $text, $tel, $adress)
	{
		$db = &JFactory::getDBO();
		$q = " UPDATE `{$this->table}` SET 
			`g_x` = '{$g_x}', 
			`g_y` = '{$g_y}', 
			`g_zoom` = '{$g_zoom}', 
			`g_key` = '{$g_key}', 
			`email` = '{$email}', 
			`text` = '{$text}',
			`tel` = '{$tel}',
			`adress` = '{$adress}' 
			WHERE {$this->id} = '1' ";
		$db->setQuery($q);
		$db->query();		
	}
	
}

?>