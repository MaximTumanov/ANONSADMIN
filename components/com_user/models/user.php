<?php
jimport('joomla.application.component.model');

class UserModelUser extends JModel {

	var $tUser = '#__user';
	var $tUserEv = '#__user_events';
	var $tUserPl = '#__user_places';
	var $tEvent = '#__events';
	var $tPlace = '#__places';
	
	function getUserEvents($id_user) {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT GROUP_CONCAT(id_event) as `ids`, GROUP_CONCAT(date) as `dates` FROM `{$this->tUserEv}` WHERE `id_user` = '{$id_user}'");
		$res = $db->loadObject();
	
		if (!$res->ids) {
			return null;
		}
		
		$res->dates = explode(',', $res->dates);
		$arr = array();
		foreach ($res->dates as $d) {
			$arr[] = "'{$d}'";
		}
		
		$res->dates = implode(',', $arr);
				
		$q = "SELECT 
				distinct 
				event.title,
				event.id_event as eventId,
				event.image,
				event.s_desc,
				event.wtf,
				MIN(dates.date) as `date`,
				cat.id_category as catId,
				GROUP_CONCAT(distinct cat.icon) as `icons`
			  FROM `#__events` as event
			  	JOIN `#__events_dates` as `dates` ON event.id_event = dates.id_event
			  	JOIN `#__events_xref` as `xref` ON event.id_event = xref.id_event
			  	JOIN `#__events_category` as `cat` ON xref.id_category = cat.id_category
			  WHERE DATE(dates.date) IN({$res->dates}) ";
				
		$q .= " AND dates.type = '3' AND event.published  = '1' AND event.id_event IN({$res->ids}) 
		      GROUP BY event.id_event ORDER BY MIN(dates.date)";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	function getUserPlaces($id_user) {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT GROUP_CONCAT(id_place) FROM `{$this->tUserPl}` WHERE `id_user` = '{$id_user}'");
		$ids = $db->loadResult();
	
		if (!$ids) {
			return null;
		}

		$q = "SELECT * FROM `{$this->tPlace}` as `place`
			  WHERE place.id_place IN({$ids}) AND place.published = '1' ORDER BY place.title";
		$db->setQuery($q);
		return $db->loadObjectList();	
	}
	
	function eventToList($id_event, $id_user, $date, $action) {
		$db = &JFactory::getDBO();
		if ($action == 'add') {
			$db->setQuery("INSERT INTO `{$this->tUserEv}` VALUES('{$id_user}', '{$id_event}', '{$date}')");		
		} elseif ($action == 'remove') {
			$db->setQuery("DELETE FROM `{$this->tUserEv}` WHERE `id_user` = '{$id_user}' AND `id_event` = '{$id_event}' AND `date` = '{$date}'");			
		}
		$db->query();
		return true;
	}
	
	function placeToList($id_place, $id_user, $action) {
		$db = &JFactory::getDBO();
		if ($action == 'add') {
			$db->setQuery("INSERT INTO `{$this->tUserPl}` VALUES('{$id_user}', '{$id_place}')");		
		} elseif ($action == 'remove') {
			$db->setQuery("DELETE FROM `{$this->tUserPl}` WHERE `id_user` = '{$id_user}' AND `id_place` = '{$id_place}'");			
		}
		$db->query();
		return true;
	}
	
	function getPlacesLetters() {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT GROUP_CONCAT(distinct UPPER(SUBSTR(title, 1, 1))) FROM `{$this->tPlace}` WHERE published = '1' ORDER BY title");
		return $db->loadResult();
	}	
}
?>