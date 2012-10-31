<?php 
jimport('joomla.application.component.model');
class EventsModelPlaces extends JModel {
	var $tPlace = '#__places';
	var $tXref  = '#__events_xref';
	var $tEvent = '#__events';
	var $tDates = '#__events_dates';
	var $tCat   = '#__events_category';
	/**
	* @var string $id поле в таблице с PRIMARY KEY
	*/	
	var $id    = 'id_place';
	/**
	* возвращает кол-во записей с учетом фильтров
	* @return int
	*/		
	function getItemsCount($lists){
		$db = &JFactory::getDBO();
		$q = " SELECT count(*) FROM `{$this->tPlace}` as c WHERE c.{$this->id} != '0' ";

		if (isset($lists['filter_title']) && $lists['filter_title']) {
			$q .= " AND (c.title LIKE '%{$lists['filter_title']}%' OR c.dop_title LIKE '%{$lists['filter_title']}%') ";
		}

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
		$order = "";
		if( $lists['order'] && $lists['order_Dir'] ) {
			$order = " ORDER BY {$lists['order']} {$lists['order_Dir']} ";
		}
		
		$db = &JFactory::getDBO();
		$q = " SELECT c.* FROM `{$this->tPlace}` AS c WHERE c.{$this->id} != '0' ";

		if (isset($lists['filter_title']) && $lists['filter_title']) {
			$q .= " AND (c.title LIKE '%{$lists['filter_title']}%' OR c.dop_title LIKE '%{$lists['filter_title']}%') ";
		}

		$q .= $order;
		$db->setQuery($q, $limitstart, $limit);
		return $db->loadObjectList();
	}
	/**
	* возвращает информацию об объявлении
	* @param int $id ID объявления
	* @return object
	*/
	function getItem($id){
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT * FROM {$this->tPlace} WHERE `{$this->id}` = '{$id}' ");
		return $db->loadObject();	
	}
	
	function getEvents($id) {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT GROUP_CONCAT(id_event) FROM `{$this->tXref}` WHERE `id_place` = '{$id}'");
		$id_events = $db->loadResult();
		
		if ($id_events) {
			$db->setQuery("SELECT `id_event`, `title`, `type`, `published` FROM `{$this->tEvent}` WHERE `id_event` IN({$id_events})");
			return $db->loadObjectList();
		}
	}
	
	function getDates($id_event) {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT * FROM `{$this->tDates}` WHERE `id_event` = '{$id_event}'");
		return $db->loadObjectList();
	}

	function getCategory() {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT `id_category` as `value`, `title` as `text` FROM `{$this->tCat}` ORDER BY `title`");
		return $db->loadObjectList();
	}	
	
	function getIdCategory($id) {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT GROUP_CONCAT(id_category) FROM `#__places_category` WHERE `{$this->id}` = '{$id}'");
		$cats = $db->loadResult();
		$array = array();
		if ($cats) {
			$array = explode(',', $cats);
		}
		return $array;
	}	
	
	/**
	* публикует выбранные записи
	* @param string $cids список ID записей через запятую (1,2,3,4....)
	* @return void
	*/
	function _publish($cids){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tPlace}` SET `published` = '1' where `{$this->id}` IN({$cids})";
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
		$q = "UPDATE `{$this->tPlace}` SET `published` = '0' where `{$this->id}` IN({$cids})";
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
		$q = "DELETE FROM `{$this->tPlace}` WHERE {$this->id} IN({$cids})";
		$db->setQuery($q);
		$db->query();		
	}
	
	function _save($title, $dop_title, $alias, $img, $desc, $address, $tel, $web, $email, $show_email, $google, $published, $category, $k_title, $k_desc, $k_keyw) {
		$db = &JFactory::getDBO();
		$db->setQuery("INSERT INTO `{$this->tPlace}` VALUES('', '{$title}', '{$dop_title}', '{$alias}', '{$img}', '{$desc}', '{$address}', '{$tel}', '{$web}', '{$email}', '{$show_email}', '{$google}', '{$published}', '{$k_title}', '{$k_desc}', '{$k_keyw}')");
		$db->query();
		
		$id_place = $db->insertid();
		
		// заполняем таблицу XREF
		foreach ($category as $key => $val) {
			$db->setQuery("INSERT INTO `#__places_category` VALUES('{$id_place}', '{$val}')");
			$db->query();
		}		
	}
	
	/**
	 * перезаписывает информацию о комментарии
	 * @param int    $id_comment  ID комментария
	 * @param string $text текст  комментария
	 */
	function _update($id_place, $title, $dop_title, $alias, $img, $desc, $address, $tel, $web, $email, $show_email, $google, $published, $category, $k_title, $k_desc, $k_keyw){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tPlace}` SET 
			`title`   = '{$title}',
			`dop_title` = '{$dop_title}',
			`alias`   = '{$alias}',
			`image`   = '{$img}',
			`desc`    = '{$desc}',
			`address` = '{$address}',
			`tel`     = '{$tel}',
			`web`     = '{$web}',
			`email`   = '{$email}',
			`show_email` = '{$show_email}',
			`google` = '{$google}',
			`published` = '{$published}',
			`k_title` = '{$k_title}',
			`k_desc` = '{$k_desc}',
			`k_keyw` = '{$k_keyw}'
		WHERE `{$this->id}` = '{$id_place}'";
		$db->setQuery($q);
		$db->query();
		
		// перезаписываем таблицу XREF для выбранного события
		$db->setQuery("DELETE FROM `#__places_category` WHERE `id_place` = '{$id_place}'");
		$db->query();

		foreach ($category as $key => $val) {
			$db->setQuery("INSERT INTO `#__places_category` VALUES('{$id_place}', '{$val}')");
			$db->query();
		}		
	}
}

?>