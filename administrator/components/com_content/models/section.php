<?php 
/**
* ContentModelSection
* @package Content
* @subpackage Model
*/
jimport('joomla.application.component.model');
class ContentModelSection extends JModel
{
	/**
	* @var string $table таблица с разделами материалов
	*/		
	var $table = '#__section';
	/**
	* @var string $id поле в таблице с PRIMARY KEY
	*/	
	var $id    = 'id_section';
	/**
	* возвращает кол-во записей
	* @return int
	*/		
	function getItemsCount()
	{
		$db = &JFactory::getDBO();
		$q = " SELECT count(id_section) FROM `{$this->table}` ";
		$db->setQuery($q);
		return $db->loadResult();
	}
	/**
	* получает массив записей с учетом фильтров
	* @param array $lists array('order' => по какому полю сортируем, 'order_Dir' => ASC или DESC)
	* @param int $limit кол-во записей
	* @param int $limitstart с какой записи начинать отчет
	* @return object
	*/
	function getItems($lists, $limit, $limitstart)
	{
		$order = "";
		if( $lists['order'] && $lists['order_Dir'] )
			$order = " ORDER BY ".$lists['order']." ".$lists['order_Dir'];
		
		$db = &JFactory::getDBO();
		$q = " SELECT c.* FROM `{$this->table}` AS c ";
		$q .= $order;
		$db->setQuery($q, $limitstart, $limit);
		return $db->loadObjectList();
	}
	/**
	* возвращает информацию о разделе материалов
	* @param int $id ID раздела
	* @return object
	*/
	function getItem($id)
	{
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT * FROM {$this->table} WHERE {$this->id} = '{$id}' ");
		return $db->loadObject();	
	}
	/**
	* возвращает максимальное существующее значение порядка вывода для записей
	* @return int
	*/	
	function getMaxOrdering()
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" SELECT max(ordering) FROM `{$this->table}` ");
		return $db->loadResult();		
	}
	/**
	* сохраняет порядок показа записей
	* @param int $val порядок
	* @param int $key ID записи
	* @return void
	*/
	function _saveorder($val, $key)
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" UPDATE `{$this->table}` SET `ordering` = '$val' WHERE {$this->id} = '{$key}' ");
		$db->query();
	}
	/**
	* копирует выбранные записи
	* @param int $val ID записи
	* @return void
	*/
	function _copyselect($val)
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" SELECT * FROM `{$this->table}` WHERE {$this->id} = '{$val}' ");
		$item = $db->loadObject();
			
		$title      = 'Копия '.$item->title;
		$alias      = 'copy-'.$item->alias;
		$published  = $item->published;
		$parent_id  = $item->parent_id;
		$id_section = $item->id_section;
			
		$ordering  = ($this->getMaxOrdering() + 1);
			
		$db->setQuery(" INSERT INTO `{$this->table}` VALUES('', '{$title}', '{$alias}', '{$parent_id}', '{$published}', '{$ordering}', '{$id_section}') ");
		$db->query();		
	}
	/**
	* публикует выбранные записи
	* @param string $cids список ID записей через запятую (1,2,3,4....)
	* @return void
	*/
	function _publish($cids)
	{
		$db = &JFactory::getDBO();
		$db->setQuery("UPDATE `{$this->table}` SET `published` = '1' where {$this->id} IN({$cids})");
		$db->query();			
	}
	/**
	* снимает с публикации выбранные записи
	* @param string $cids список ID записей через запятую (1,2,3,4....)
	* @return void
	*/
	function _unpublish($cids)
	{
		$db = &JFactory::getDBO();
		$db->setQuery("UPDATE `{$this->table}` SET `published` = '0' where {$this->id} IN({$cids})");
		$db->query();		
	}
	/**
	* удаляет выбранные записи
	* @param string $cids список ID записей через запятую (1,2,3,4....)
	* @return void
	*/
	function _removeSelect($cids)
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" DELETE FROM `{$this->table}` WHERE {$this->id} IN({$cids}) ");
		$db->query();		
	}
	/**
	* сохраняет запись
	* @param string $title заголовок
	* @param string $alias псевдоним, используется в ЧПУ
	* @param int $published 1 или 0 ( 1 - опубликовано, 0 - неопубликовано )
	* @param int $ordering порядок показа
	* @return void
	*/
	function _save($title, $alias, $published, $ordering)
	{
		$db = &JFactory::getDBO();
		$q = " INSERT INTO `{$this->table}` VALUES('', '{$title}', '{$alias}', '{$published}', '{$ordering}') ";
		$db->setQuery($q);
		$db->query();		
	}
	/**
	* перезаписывает данные
	* @param int $id ID записи
	* @param string $title заголовок
	* @param string $alias псевдоним, используется в ЧПУ
	* @param int $published 1 или 0 ( 1 - опубликовано, 0 - неопубликовано )
	* @return void
	*/
	function _update($id, $title, $alias, $published, $id_section)
	{
		$db = &JFactory::getDBO();
		$q = " UPDATE `{$this->table}` SET `title` = '{$title}', `alias` = '{$alias}', `published` = '{$published}' WHERE {$this->id} = '{$id}' ";
		$db->setQuery($q);
		$db->query();	
	}
	
}

?>