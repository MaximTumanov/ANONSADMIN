<?php
jimport('joomla.application.component.model');
/**
* Content Component Content Frontpage
* @package    Anons
* @subpackage Content
*/
class ContentModelFrontpage extends JModel
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
	
	var $published = 1;
	var $onFront   = 1;
	/**
	* возвращает информацию о материале на главной
	* @return object
	*/
	function getFrontpageItem()
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" SELECT * FROM `{$this->tItem}` WHERE `published` = '{$this->published}' AND `on_front` = '{$this->onFront}' ", 0, 1);
		return $db->loadObject();
	}
}
?>