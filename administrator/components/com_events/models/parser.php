<?php 
jimport('joomla.application.component.model');
class EventsModelParser extends JModel {
	var $tEvents = '#__events';
	var $tXref   = '#__events_xref';
	var $tCat    = '#__events_category';
	var $tPlace  = '#__places';
	var $eDate   = '#__events_dates';
	/**
	* @var string $id поле в таблице с PRIMARY KEY
	*/	
	var $id    = 'id_event';
	/**
	* возвращает кол-во записей с учетом фильтров
	* @return int
	*/

	function check_alias($alias){
		$db = &JFactory::getDBO();
		$q = "SELECT count(*) FROM `#__events` WHERE `alias` = '{$alias}'";
		$db->setQuery($q);
		return $db->loadResult();
	}

	function getItemsCount($lists){
		$db = &JFactory::getDBO();
		$q = " SELECT count(*) FROM `{$this->tEvents}` as c WHERE `id_event` > 0 ";
		if ($lists['id_category']) {
			$db->setQuery("SELECT GROUP_CONCAT(distinct id_event) FROM `{$this->tXref}` WHERE `id_category` = '{$lists['id_category']}'");
			$id_events = $db->loadResult();
			if (!$id_events) return null;
			$q .= " AND c.id_event IN({$id_events}) ";
		}
		
		if ($lists['date_from'] && $lists['date_to']) {
			$db->setQuery("SELECT GROUP_CONCAT(distinct id_event) FROM `{$this->eDate}` WHERE `date` >= '{$lists['date_from']}' AND `date` <= '{$lists['date_to']}'");
			$id_events = $db->loadResult();
			if (!$id_events) return null;
			$q .= " AND c.id_event IN({$id_events}) ";
		} else if($lists['date_from']) {
			$db->setQuery("SELECT GROUP_CONCAT(distinct id_event) FROM `{$this->eDate}` WHERE `date` >= '{$lists['date_from']}'");
			$id_events = $db->loadResult();
			if (!$id_events) return null;
			$q .= " AND c.id_event IN({$id_events}) ";			
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
		$db = &JFactory::getDBO();

		$db->setQuery("SET SESSION group_concat_max_len = 16384");
		$db->query();

		$order = "";
		if( $lists['order'] && $lists['order_Dir'] )
			$order = " ORDER BY {$lists['order']} {$lists['order_Dir']} ";
		

		$q = " SELECT c.* FROM `{$this->tEvents}` AS c WHERE `id_event` > 0 ";
		if ($lists['id_category']) {
			$db->setQuery("SELECT GROUP_CONCAT(distinct id_event) FROM `{$this->tXref}` WHERE `id_category` = '{$lists['id_category']}'");
			$id_events = $db->loadResult();
			if (!$id_events) return null;
			$q .= " AND c.id_event IN({$id_events}) ";
		}
		
			
		if ($lists['date_from'] && $lists['date_to']) {
			$db->setQuery("SELECT GROUP_CONCAT(distinct id_event) FROM `{$this->eDate}` WHERE `date` >= '{$lists['date_from']}' AND `date` <= '{$lists['date_to']}'");
			$id_events = $db->loadResult();
			if (!$id_events) return null;
			$q .= " AND c.id_event IN({$id_events}) ";
		} else if($lists['date_from']) {
			$db->setQuery("SELECT GROUP_CONCAT(distinct id_event) FROM `{$this->eDate}` WHERE `date` >= '{$lists['date_from']}'");
			$id_events = $db->loadResult();
			if (!$id_events) return null;
			$q .= " AND c.id_event IN({$id_events}) ";			
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
	function getItem($id)
	{
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT * FROM {$this->tEvents} WHERE `{$this->id}` = '{$id}' ");
		return $db->loadObject();	
	}
	
	function getCategory() {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT `id_category` as `value`, `title` as `text` FROM `{$this->tCat}` ORDER BY `title`");
		return $db->loadObjectList();
	}
	
	function getMoreInfo($id){
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT `id_place` as `idPlace` FROM `{$this->tXref}` WHERE `{$this->id}` = '{$id}'");
		return $db->loadObject();
	}
	
	function getCatIcon($ids) {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT `title`, `icon` FROM `{$this->tCat}` WHERE `id_category` IN({$ids})");
		return $db->loadObjectList();
	}
	
	function getPlace($id) {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT `id_place`, `title` FROM `{$this->tPlace}` WHERE `id_place` = '{$id}'");
		return $db->loadObject();
	}
	
	function getPlaceList() {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT `id_place` as `value`, `title` as `text` FROM `{$this->tPlace}` WHERE `id_place` != '0' ORDER BY `title`");
		return $db->loadObjectList();
	}
	
	function getIdPlace($id) {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT `id_place` FROM `{$this->tXref}` WHERE `{$this->id}` = '{$id}'");
		return $db->loadResult();
	}
	
	function getEventDate($id, $fromTo = false) {
		$db = &JFactory::getDBO();
		if ($fromTo) {
			$db->setQuery("SELECT `date`, `time`, `day`, `type` FROM `{$this->eDate}` WHERE `{$this->id}` = '{$id}'");
			$res = $db->loadObjectList();			
			
			$date = new stdClass();
			$date->time = null;
			$date->day  = null;
			$date->dateFrom = null;
			$date->dateTo = null;
			
			if ($res) {
				foreach ($res as $key => $val):
					if (!$date->time) {
						$date->time = $val->time;
					}
					
					if (!$date->day) {
						$date->day = $val->day;
					}
					
					if ($val->type == 1) {
						$date->dateFrom = $val->date;
					} else {
						$date->dateTo = $val->date;
					}
				endforeach;
				$result = $date;
			}
			
		} else {
			$db->setQuery("SELECT `date`, `time`, `day` FROM `{$this->eDate}` WHERE `{$this->id}` = '{$id}'");	
			$result = $db->loadObject();		
		}
		return $result;
	}
	
	function getIdCategory($id) {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT GROUP_CONCAT(id_category) FROM `{$this->tXref}` WHERE `{$this->id}` = '{$id}'");
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
		$q = "UPDATE `{$this->tEvents}` SET `published` = '1' where `{$this->id}` IN({$cids})";
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
		$q = "UPDATE `{$this->tEvents}` SET `published` = '0' where `{$this->id}` IN({$cids})";
		$db->setQuery($q);
		$db->query();		
	}
	
	function _vip($cids){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tEvents}` SET `vip` = '1' where `{$this->id}` IN({$cids})";
		$db->setQuery($q);
		$db->query();			
	}

	function _unvip($cids){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tEvents}` SET `vip` = '0' where `{$this->id}` IN({$cids})";
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
		$q = " DELETE FROM `{$this->tEvents}` WHERE {$this->id} IN({$cids}) ";
		$db->setQuery($q);
		$db->query();		
	}
	function _save($title, $alias, $img, $s_desc, $desc, $address, $type, $vip, $wtf, $published, $category, $id_place, $dates, $k_title, $k_desc, $k_keyw, $price) {
		$db = &JFactory::getDBO();
		$q = "INSERT INTO `{$this->tEvents}` VALUES('', '{$title}', '{$alias}', '{$img}', '{$s_desc}', '{$desc}', '{$address}', '{$type}', '{$vip}', '{$wtf}', '{$published}', '{$k_title}', '{$k_desc}', '{$k_keyw}', '{$price}', '', '')";
		$db->setQuery($q);
		$db->query();

		$id_event = $db->insertid();
		
		// заполняем таблицу XREF
		foreach ($category as $key => $val) {
			$db->setQuery("INSERT INTO `{$this->tXref}` VALUES('{$id_event}', '{$val}', '{$id_place}')");
			$db->query();
		}
		
		$this->_updateDates($id_event, $type, $dates);
		return $id_event;
	}
	/**
	 * перезаписывает информацию о комментарии
	 * @param int    $id_comment  ID комментария
	 * @param string $text текст  комментария
	 */
	function _update($id_event, $title, $alias, $img, $s_desc, $desc, $address, $type, $vip, $wtf, $published, $category, $id_place, $dates, $k_title, $k_desc, $k_keyw, $price){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tEvents}` SET 
			`title`   = '{$title}',
			`alias`   = '{$alias}',
			`image`   = '{$img}',
			`s_desc`  = '{$s_desc}',
			`desc`    = '{$desc}',
			`address` = '{$address}',
			`type`    = '{$type}',
			`vip`     = '{$vip}',
			`wtf`     = '{$wtf}',
			`published` = '{$published}',
			`k_title` = '{$k_title}',
			`k_desc` = '{$k_desc}',
			`k_keyw` = '{$k_keyw}'
			`price`  = '{$price}'
		WHERE `{$this->id}` = '{$id_event}'";
		$db->setQuery($q);
		
		$db->query();
		
		// перезаписываем таблицу XREF для выбранного события
		$db->setQuery("DELETE FROM `{$this->tXref}` WHERE `{$this->id}` = '{$id_event}'");
		$db->query();

		foreach ($category as $key => $val) {
			$db->setQuery("INSERT INTO `{$this->tXref}` VALUES('{$id_event}', '{$val}', '{$id_place}')");
			$db->query();
		}
		
		$this->_updateDates($id_event, $type, $dates);
			
	}
	
	function _updateDates($id_event, $type, $dates) {
		$db = &JFactory::getDBO();
		$db->setQuery("DELETE FROM `{$this->eDate}` WHERE `{$this->id}` = '{$id_event}'");
		$db->query();
		
		switch ($type) {
			case 1:
				$db->setQuery("INSERT INTO `{$this->eDate}` VALUES('{$id_event}', '{$dates->date} {$dates->time}', '{$dates->time}', '', '3')");
				$db->query();
				break;
			case 2:
				if ($dates->dateFrom) {
					$db->setQuery("INSERT INTO `{$this->eDate}` VALUES('{$id_event}', '{$dates->dateFrom} {$dates->time}', '{$dates->time}', '{$dates->day}', '1')");
					$db->query();					
				}
				
				if ($dates->dateTo) {
					$db->setQuery("INSERT INTO `{$this->eDate}` VALUES('{$id_event}', '{$dates->dateTo} {$dates->time}', '{$dates->time}', '{$dates->day}', '2')");
					$db->query();					
				}
				
				$this->_addPeriodWeek($id_event, $dates->time, $dates->dateFrom, $dates->dateTo, $dates->day);
				
				break;
			case 3:
				if ($dates->dateFrom) {
					$db->setQuery("INSERT INTO `{$this->eDate}` VALUES('{$id_event}', '{$dates->dateFrom} {$dates->time}', '{$dates->time}', '{$dates->day}', '1')");
					$db->query();					
				}
				
				if ($dates->dateTo) {
					$db->setQuery("INSERT INTO `{$this->eDate}` VALUES('{$id_event}', '{$dates->dateTo} {$dates->time}', '{$dates->time}', '{$dates->day}', '2')");
					$db->query();					
				}	

				$this->_addPeriodMonth($id_event, $dates->time, $dates->dateFrom, $dates->dateTo, $dates->day);
				
				break;
			case 4:
				break;
			case 5:
				if ($dates->dateFrom) {
					$db->setQuery("INSERT INTO `{$this->eDate}` VALUES('{$id_event}', '{$dates->dateFrom} {$dates->time}', '{$dates->time}', '', '1')");
					$db->query();					
				}
				
				if ($dates->dateTo) {
					$db->setQuery("INSERT INTO `{$this->eDate}` VALUES('{$id_event}', '{$dates->dateTo} {$dates->time}', '{$dates->time}', '', '2')");
					$db->query();					
				}
				
				$this->_addPeriodDay($id_event, $dates->time, $dates->dateFrom, $dates->dateTo);
				
				break;
				case 6:
				$db->setQuery("INSERT INTO `{$this->eDate}` VALUES('{$id_event}', '{$dates->date} {$dates->time}', '{$dates->time}', '', '3')");
				$db->query();
				break;
		}
	}

	function _addPeriodDay($id_event, $time, $from, $to) {
		$db = &JFactory::getDBO();
		$startdate = strtotime($from);
		$enddate   = strtotime($to);
		
		while($startdate <= $enddate) {  
			$date = date('Y-m-d', $startdate);
			$db->setQuery("INSERT INTO `{$this->eDate}` VALUES('{$id_event}', '{$date} {$time}', '{$time}', '', '3')");
			$db->query(); 
		   	$startdate += 86400; 
		}		
	}
	
	function _addPeriodWeek($id_event, $time, $from, $to, $day) {
		$db = &JFactory::getDBO();
		$startdate = strtotime($from);
		$enddate   = strtotime($to);
		
		while($startdate <= $enddate) {  
			if(date('w', $startdate) == $day) {
				$date = date('Y-m-d', $startdate);
				$db->setQuery("INSERT INTO `{$this->eDate}` VALUES('{$id_event}', '{$date} {$time}', '{$time}', '{$day}', '3')");
				$db->query(); 
			}
		   	$startdate += 86400; 
		}		
	}
	
	function _addPeriodMonth($id_event, $time, $from, $to, $day) {
		$db = &JFactory::getDBO();
		$startdate = strtotime($from);
		$enddate   = strtotime($to);

		while($startdate <= $enddate) {  
			if(date('d', $startdate) == $day) {
				break;
			}
		   	$startdate += 86400; 
		}
				
		while($startdate <= $enddate) {
			$date = date('Y-m-d', $startdate);
			$db->setQuery("INSERT INTO `{$this->eDate}` VALUES('{$id_event}', '{$date} {$time}', '{$time}', '{$day}', '3')");
			$db->query(); 
			$startdate = strtotime('+1 month', $startdate);			
		}		
	}	
}

?>