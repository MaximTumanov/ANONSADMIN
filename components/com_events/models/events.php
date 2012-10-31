<?php
jimport('joomla.application.component.model');

class EventsModelEvents extends JModel {

	var $tItem  = '#__events';
	var $tXref  = '#__events_xref';
	var $tDates = '#__events_dates';
	var $tCat   = '#__events_category';
	var $tPlace = '#__places';
	var $id     = 'id_event';
	
	function getItem($id, $date = false) {
		$db = &JFactory::getDBO();		
		$q = "SELECT 
				distinct event.*,
				place.title as `placeTitle`,
				place.dop_title as `placeDopTitle`,
				place.id_place as `placeId`,
				place.address,
				place.tel,
				place.web,
				place.email,
				place.show_email,
				place.google,
				MIN(dates.date) as `date`,
				dates.time as `time`,
				GROUP_CONCAT(distinct cat.icon) as `icons` 
			  FROM `{$this->tItem}` as `event` 
			    JOIN `{$this->tXref}` as `xref` ON xref.id_event = event.id_event
			    JOIN `{$this->tPlace}` as `place` ON xref.id_place = place.id_place
			    JOIN `{$this->tDates}` as `dates` ON xref.id_event = dates.id_event
			    JOIN `{$this->tCat}` as `cat` ON xref.id_category = cat.id_category
		   	  WHERE event.{$this->id} = '{$id}' AND dates.type = '3' AND ";
		if ($date) {
			$q .= " DATE(dates.date) = '{$date}' ";
		} else {
			$q .= " DATE(dates.date) >= DATE(NOW()) ";
		}
		
		$q .= " GROUP BY event.id_event ";

		$db->setQuery($q);
		return $db->loadObject();
	}
	
	function getCountUsersGo($id, $date) {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT count(*) FROM `#__user_events` WHERE `id_event` = '{$id}' AND DATE(date) = DATE('{$date}')");
		return $db->loadResult();
	}
	
	function getDates($id) {
		$db = &JFactory::getDBO();
		$q = "SELECT * FROM `{$this->tDates}` WHERE `id_event` = '{$id}'";
		$db->setQuery($q);
		$res = $db->loadObjectList();
		$return = new stdClass();
		
		foreach ($res as $date) {
			if ($date->type == 1) {
				$return->from = $date->date;
			} elseif ($date->type == 2) {
				$return->to = $date->date;
			}
		}
			
		$return->day = $res[0]->day;
		$return->time = substr($res[0]->time, 0, 5);
		
		return $return;
	}
	
	function getRelatedEvents($date, $id_event) {
		$db = &JFactory::getDBO();
		$q = "SELECT 
				distinct 
				event.title,
				event.id_event,
				event.image,
				dates.time,
				xref.id_category as catId				
			  FROM  `{$this->tItem}` as `event`
			  	JOIN `{$this->tXref}` as `xref` ON xref.id_event = event.id_event
			  	JOIN `{$this->tDates}` as `dates` ON xref.id_event = dates.id_event			  
			  WHERE dates.date = '{$date}' AND event.id_event != '{$id_event}' AND dates.type = '3' AND event.published = '1' GROUP BY event.id_event ORDER BY dates.time";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	function getEventsDates($from, $to) {
		$db = &JFactory::getDBO();
		$q = "SELECT GROUP_CONCAT(distinct UNIX_TIMESTAMP(DATE(dates.date))) FROM `{$this->tDates}` as `dates`
			    JOIN `{$this->tItem}` as `event` ON event.id_event = dates.id_event
			  WHERE DATE(dates.date) >= STR_TO_DATE(NOW(),'%Y-%m-%d') AND DATE(dates.date) >= '{$from}' 
			    AND DATE(dates.date) <= '{$to}' AND dates.type = '3' AND event.published = '1'";
		$db->setQuery($q);
		return $db->loadResult();
	}
	
	function getEventsList($date = false) {
		$db = &JFactory::getDBO();
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
			  WHERE ";
		
		$q .= ($date) ? "DATE(dates.date) = '{$date}'" : "DATE(dates.date) >= DATE(NOW())"; 
		
		$q .= " AND dates.type = '3' AND event.published  = '1' GROUP BY event.id_event ORDER BY MIN(dates.date)";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	function getEventsFromSearch($text, $id_category, $date_type, $date_from, $date_to) {
		$db = &JFactory::getDBO();
		$q = "SELECT 
			    distinct 
			    event.id_event as `eventId`,
			    event.title,
			    event.image,
			    event.s_desc,
			    event.wtf,
			    dates.date as `date`,
			    cat.id_category as catId,
				GROUP_CONCAT(distinct cat.icon) as `icons` 
			  FROM `{$this->tItem}` as `event`
			    JOIN `{$this->tXref}` as `xref` ON xref.id_event = event.id_event
			    JOIN `{$this->tDates}` as `dates` ON dates.id_event = event.id_event
			    JOIN `{$this->tCat}` as `cat` ON xref.id_category = cat.id_category
			  WHERE event.published = '1' AND dates.type = '3' ";
		
		switch ($date_type) {
			case 0:
				$q .= " AND DATE(dates.date) >= DATE(NOW()) ";
				break;
			case 1:
				$q .= " AND DATE(dates.date) = CURDATE() ";
				break;
			case 2:
				$q .= " AND DATE(dates.date) = (CURDATE() + INTERVAL 1 DAY) ";
				break;
			case 3:
				$d = date('w', strtotime(date('d.m.y')));
				if ($d == 6) {
					$q .= " AND DATE(dates.date) BETWEEN CURDATE() AND (CURDATE() + INTERVAL 1 DAY) ";
				} elseif ($d != 6 && $d) {
					$saturday = 6 - $d;
					$sunday = $saturday + 1;
					$q .= " AND DATE(dates.date) BETWEEN (CURDATE() + INTERVAL {$saturday} DAY) AND (CURDATE() + INTERVAL {$sunday} DAY) ";
				} elseif (!$d) {
					$q .= " AND DATE(dates.date) = CURDATE() ";
				} 
				break;
			case 4:
				$d = date('w', strtotime(date('d.m.y')));
				if ($d == 6) {
					$q .= " AND DATE(dates.date) BETWEEN (CURDATE() + INTERVAL 7 DAY) AND (CURDATE() + INTERVAL 8 DAY) ";
				} elseif ($d != 6 && $d) {
					$saturday = 13 - $d;
					$sunday = $saturday + 1;
					$q .= " AND DATE(dates.date) BETWEEN (CURDATE() + INTERVAL {$saturday} DAY) AND (CURDATE() + INTERVAL {$sunday} DAY) ";
				} elseif (!$d) {
					$q .= " AND DATE(dates.date) = (CURDATE() + INTERVAL 7 DAY) ";
				} 
				break;
			case 5:
				if ($date_from && $date_to) {
					$q .= " AND DATE(dates.date) >= '{$date_from}' AND DATE(dates.date) <= '{$date_to}' ";
				}
				break;			
		}
	
		if ($id_category) {
			$q .= " AND cat.id_category = '{$id_category}' ";
		}
		
		if ($text && $text != 'Поиск') {
			$text = JString::strtolower($this->_good($text));
			$q .= " AND LOWER(event.title) LIKE '%". str_replace(" ", "%' OR LOWER(event.title) LIKE '%", $text). "%'";
		}
		
		$q .= " GROUP BY event.id_event ORDER BY dates.date ";

		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	function getAllRecomended($ids) {
		$db = JFactory::getDBO();
		$q = "SELECT 
				event.*,
				place.title as place_title,
				place.dop_title as place_dop_title,
				xref.id_category as catId,
				MIN(dates.date) as `date`
			  FROM `#__events` as `event` 
			  	JOIN `#__events_dates` as `dates` ON dates.id_event = event.id_event
			  	JOIN `#__events_xref` as `xref` ON event.id_event = xref.id_event
			  	JOIN `#__places` as `place` ON xref.id_place = place.id_place
			  WHERE event.vip = '1' AND dates.type = '3' AND dates.date >= DATE(NOW()) AND event.published = '1' AND event.id_event NOT IN({$ids}) 
			    GROUP BY event.id_event";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	function _good($text) {
		$search = substr($text, 0, 64);
		$search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search);
		$good = trim(preg_replace("/\s(\S{1,2})\s/", " ", ereg_replace(" +", "  "," $search ")));
		$good = ereg_replace(" +", " ", $good);
		return $good;
	}
}
?>