<?php 
jimport('joomla.application.component.model');
class OurusersModelUser extends JModel {
	var $tUsers  = '#__user';
	var $tPlace  = '#__places';
	/**
	* @var string $id поле в таблице с PRIMARY KEY
	*/	
	var $id    = 'id';
	/**
	* возвращает кол-во записей с учетом фильтров
	* @return int
	*/		
	function getItemsCount($lists){
		$db = &JFactory::getDBO();
		$q = " SELECT count(*) FROM `{$this->tUsers}` as c WHERE `id` > 0 ";		
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
	function getItems($lists, $limit, $limitstart){
		$db = &JFactory::getDBO();

		$db->setQuery("SET SESSION group_concat_max_len = 16384");
		$db->query();

		$order = "";
		if( $lists['order'] && $lists['order_Dir'] ) {
			$order = " ORDER BY {$lists['order']} {$lists['order_Dir']} ";
		}

		$q = " SELECT c.* FROM `{$this->tUsers}` AS c WHERE `id` > 0 ";		
		$q .= $order;
		$db->setQuery($q, $limitstart, $limit);
		return $db->loadObjectList();
	}

	function getPlaceList() {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT `id_place` as `value`, `title` as `text` FROM `{$this->tPlace}` WHERE `id_place` != '0' ORDER BY `title`");
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
		$db->setQuery("SELECT * FROM {$this->tUsers} WHERE `{$this->id}` = '{$id}' ");
		return $db->loadObject();	
	}
			
	function getPlace($id) {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT `id_place`, `title` FROM `{$this->tPlace}` WHERE `id_place` = '{$id}'");
		return $db->loadObject();
	}

	/**
	* публикует выбранные записи
	* @param string $cids список ID записей через запятую (1,2,3,4....)
	* @return void
	*/
	function _publish($cids){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tUsers}` SET `public` = '1' where `{$this->id}` IN({$cids})";
		$db->setQuery($q);
		$db->query();			
	}
	/**
	* снимает с публикации выбранные записи
	* @param string $cids список ID записей через запятую (1,2,3,4....)
	* @return void
	*/
	function _unpublish($cids){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tUsers}` SET `public` = '2' where `{$this->id}` IN({$cids})";
		$db->setQuery($q);
		$db->query();		
	}
	
	function _vip($cids){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tUsers}` SET `vip` = '1' where `{$this->id}` IN({$cids})";
		$db->setQuery($q);
		$db->query();			
	}

	function _unvip($cids){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tUsers}` SET `vip` = '0' where `{$this->id}` IN({$cids})";
		$db->setQuery($q);
		$db->query();		
	}	
	
	/**
	* удаляет выбранные записи
	* @param string $cids список ID записей через запятую (1,2,3,4....)
	* @return void
	*/
	function _removeSelect($cids){
		$db = &JFactory::getDBO();
		$q = " DELETE FROM `{$this->tUsers}` WHERE {$this->id} IN({$cids}) ";
		$db->setQuery($q);
		$db->query();		
	}
	/**
	 * перезаписывает информацию о комментарии
	 * @param int    $id_comment  ID комментария
	 * @param string $text текст  комментария
	 */
	function _update($id, $public, $vip, $new, $id_place){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tUsers}` SET 
			`public` = '{$public}',
			`vip` = '{$vip}',
			`new` = '{$new}',
			`id_place` => '{$id_place}'
		WHERE `{$this->id}` = '{$id}'";
		$db->setQuery($q);
		
		$db->query();		
	}	
}

?>