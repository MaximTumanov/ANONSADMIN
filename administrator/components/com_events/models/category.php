<?php 
jimport('joomla.application.component.model');
class EventsModelCategory extends JModel {
	var $tCat = '#__events_category';
	/**
	* @var string $id поле в таблице с PRIMARY KEY
	*/	
	var $id    = 'id_category';
	/**
	* возвращает кол-во записей с учетом фильтров
	* @return int
	*/		
	function getItemsCount()
	{
		$db = &JFactory::getDBO();
		$q = " SELECT count(*) FROM `{$this->tCat}` as c ";
		$db->setQuery($q);
		return $db->loadResult();
	}
	/**
	* получает массив записей с учетом фильтров
	* @param array $lists array('order' => по какому полю сортируем, 'order_Dir' => ASC или DESC)
	* @param int $limit кол-во записей
	* @param int $limitstart с какой записи начинать отчет
	* @return array
	*/
	function getItems($lists, $limit, $limitstart)
	{
		$order = "";
		if( $lists['order'] && $lists['order_Dir'] )
			$order = " ORDER BY {$lists['order']} {$lists['order_Dir']} ";
		
		$db = &JFactory::getDBO();
		$q = " SELECT c.* FROM `{$this->tCat}` AS c ";
		$q .= $order;
		$db->setQuery($q, $limitstart, $limit);
		return $db->loadObjectList();
	}
	/**
	* возвращает информацию об объявлении
	* @param int $id ID объявления
	* @return object
	*/
	function getItem($id)
	{
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT * FROM {$this->tCat} WHERE `{$this->id}` = '{$id}' ");
		return $db->loadObject();	
	}
	/**
	* публикует выбранные записи
	* @param string $cids список ID записей через запятую (1,2,3,4....)
	* @return void
	*/
	function _publish($cids)
	{
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tCat}` SET `published` = '1' where `{$this->id}` IN({$cids})";
		$db->setQuery($q);
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
		$q = "UPDATE `{$this->tCat}` SET `published` = '0' where `{$this->id}` IN({$cids})";
		$db->setQuery($q);
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
		$q = " DELETE FROM `{$this->tCat}` WHERE {$this->id} IN({$cids}) ";
		$db->setQuery($q);
		$db->query();		
	}
	
	function _save($title, $alias, $icon, $published) {
		$db = &JFactory::getDBO();
		$db->setQuery("INSERT INTO `{$this->tCat}` VALUES('', '{$title}', '{$alias}', '{$icon}', '{$published}')");
		$db->query();
	}
	
	/**
	 * перезаписывает информацию о комментарии
	 * @param int    $id_comment  ID комментария
	 * @param string $text текст  комментария
	 */
	function _update($id_event, $title, $alias, $icon, $published){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tCat}` SET 
			`title` = '{$title}',
			`alias` = '{$alias}',
			`icon` = '{$icon}',
			`published` = '{$published}' 
		WHERE `{$this->id}` = '{$id_event}'";
		$db->setQuery($q);
		$db->query();
	}
}

?>