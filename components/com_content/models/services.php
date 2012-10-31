<?php
jimport('joomla.application.component.model');
/**
* Content Component Content Services
* @package Anons
* @subpackage Content 
*/
class ContentModelServices extends JModel
{
	/**
	* @var string $table_section таблица с разделами
	*/
	var $tSection = '#__sections';
	/**
	* @var string $table_item таблица с материалами
	*/
	var $tItem = '#__content';
	var $tXref = '#__content_xref';
	var $id    = 'id_item';
	/**
	 * возвращает список услуг компании
	 * @param int $id_section
	 * @param int $id_category
	 * @return array
	 */
	function getServices($id_section, $id_category)
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" SELECT GROUP_CONCAT({$this->id}) FROM `{$this->tXref}` WHERE `id_section` = '{$id_section}' AND `id_category` = '{$id_category}' ");
		$IN = $db->loadResult();
		
		$q = " SELECT * FROM `{$this->tItem}` WHERE `{$this->id}` IN({$IN}) AND `published` = '1' ORDER BY `ordering` ";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	/**
	* возвращает информацию о услуге компании
	* @param int $id ID услуги
	* @return object
	*/
	function getServicesItem($id)
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" SELECT * FROM `{$this->tItem}` WHERE `{$this->id}` = '{$id}' ");
		return $db->loadObject();
	}
}
?>