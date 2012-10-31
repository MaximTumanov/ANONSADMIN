<?php
jimport('joomla.application.component.model');
/**
* Contact Component Contact Model
* @package Anons
* @subpackage Contact
*/
class ContactModelContact extends JModel
{
	/**
	* @var string $table таблица с контактами
	*/
	var $table = '#__contact';
	/**
	* @var string $id поле с Primary Key
	*/
	var $id    = 'id';
	
	/**
	* возвращает контактную информацию
	* @return object
	*/
	function getItem()
	{
		$db = &JFactory::getDBO();
		$q = " SELECT * FROM `{$this->table}` WHERE `{$this->id}` = '1' ";
		$db->setQuery($q);
		return $db->loadObject();
	}
}
?>