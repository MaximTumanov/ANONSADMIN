<?php
jimport('joomla.application.component.model');
/**
* Content Component Content Item
* @package Anons
* @subpackage Content
*/
class ContentModelItem extends JModel
{
	/**
	* @var string $table_item таблица с материалами
	*/
	var $tItem = '#__content';
	/**
	* @var string $table_xref
	*/
	var $tXref = '#__content_xref';
	/**
	* @var string $id поле с Primary Key
	*/
	var $id         = 'id_item';
	
	/**
	* возвращает информацию о мамтериале
	* @param int $id ID материала
	* @return object
	*/
	function getItem($id)
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" SELECT * FROM `{$this->tItem}` WHERE `{$this->id}` = '{$id}' ");
		return $db->loadObject();
	}
}
?>