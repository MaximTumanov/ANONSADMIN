<?php
jimport('joomla.application.component.model');

class PlacesModelPlaces extends JModel {

	var $tItem  = '#__events';
	var $tXref  = '#__events_xref';
	var $tDates = '#__events_dates';
	var $tCat   = '#__events_category';
	var $tPlace = '#__places';
	var $id     = 'id_event';
	
	function getPlace($id_place) {
		$db = &JFactory::getDBO();
		$q = "SELECT
				place.*,
				GROUP_CONCAT(distinct cat.icon) as `icons` 
			  FROM `{$this->tPlace}` as place
			  	JOIN `#__places_category` as xref ON xref.id_place = place.id_place
			  	JOIN `{$this->tCat}` as `cat` ON xref.id_category = cat.id_category
			  WHERE place.id_place = '{$id_place}' GROUP BY place.id_place";
		$db->setQuery($q);
		return $db->loadObject();
	}

	function getCountUsersLike($id) {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT count(*) FROM `#__user_places` WHERE `id_place` = '{$id}'");
		return $db->loadResult();
	}	
	
	function getPlacesList() {
		$db = &JFactory::getDBO();
		$q = "SELECT * FROM `{$this->tPlace}` as `place`
			  WHERE place.published = '1' ORDER BY place.title";
		$db->setQuery($q); 
		return $db->loadObjectList();
	}
	
	function getPlacesLetters() {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT GROUP_CONCAT(distinct UPPER(SUBSTR(title, 1, 1))) FROM `{$this->tPlace}` WHERE published = '1' ORDER BY title");
		return $db->loadResult();
	}
	
	function getRelatedEvents($id_place) {
		$db = &JFactory::getDBO();
		$q = "SELECT 
				distinct 
				event.id_event,
				event.title,
				event.image,
				MIN(dates.date) as `date`,
				dates.time,
				xref.id_category as catId 
			  FROM `{$this->tItem}` as `event`
			    JOIN `{$this->tXref}` as `xref` ON xref.id_event = event.id_event
			    JOIN `{$this->tDates}` as `dates` ON xref.id_event = dates.id_event
			  WHERE xref.id_place = '{$id_place}' AND dates.type = '3' AND dates.date >= NOW() AND event.published = '1' GROUP BY event.id_event ORDER BY dates.date";
		$db->setQuery($q);
		return $db->loadObjectList();
	}
	
	function getPlacesFromSearch($text, $id_category) {
		$db = &JFactory::getDBO();
		$q = "SELECT 
			    distinct 
			    place.id_place as `placeId`,
			    place.title,
			    place.dop_title,
			    place.image,
			    place.tel,
			    place.address,
			    place.web,
			    place.email,
			    place.show_email
			  FROM `{$this->tPlace}` as `place`
				JOIN `#__places_category` as xref ON xref.id_place = place.id_place
			  WHERE place.published = '1' ";
		
		if ($id_category) {
			$q .= " AND xref.id_category = '{$id_category}' ";
		}
		
		if ($text && $text != 'Поиск') {
			$text = JString::strtolower($this->_good($text));
			$q .= " AND (LOWER(place.title) LIKE '%". str_replace(" ", "%' OR LOWER(place.title) LIKE '%", $text). "%'";
			$q .= " OR  LOWER(place.dop_title) LIKE '%". str_replace(" ", "%' OR LOWER(place.dop_title) LIKE '%", $text). "%')";
		}
		
		$q .= " ORDER BY place.title ";

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