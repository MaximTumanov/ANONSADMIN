<?php 
jimport('joomla.application.component.model');
class FilmsModelCinema extends JModel {
	var $tCinema = '#__cinema';
	var $tXref  = '#__films_xref';
	var $tFilms = '#__films';
	var $tTime = '#__films_time';
	
	/**
	* @var string $id поле в таблице с PRIMARY KEY
	*/	
	var $id    = 'id_cinema';
	/**
	* возвращает кол-во записей с учетом фильтров
	* @return int
	*/		
	function getItemsCount($lists){
		$db = &JFactory::getDBO();
		$q = " SELECT count(*) FROM `{$this->tCinema}` as c WHERE c.{$this->id} != '0' ";

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
		$q = " SELECT c.* FROM `{$this->tCinema}` AS c WHERE c.{$this->id} != '0' ";

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
		$db->setQuery("SELECT * FROM {$this->tCinema} WHERE `{$this->id}` = '{$id}' ");
		return $db->loadObject();	
	}
	
	function getFilms($id) {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT GROUP_CONCAT(id_films) FROM `{$this->tXref}` WHERE `id_cinema` = '{$id}'");
		$id_films = $db->loadResult();
		
		if ($id_films) {
			$db->setQuery("SELECT `id_films`, `title`, `type`, `published` FROM `{$this->tFilms}` WHERE `id_films` IN({$id_films})");
			return $db->loadObjectList();
		}
	}
	
	function getTimes($id_films) {
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT * FROM `{$this->tTime}` WHERE `id_films` = '{$id_films}'");
		return $db->loadObjectList();
	}

	
	/**
	* публикует выбранные записи
	* @param string $cids список ID записей через запятую (1,2,3,4....)
	* @return void
	*/
	function _publish($cids){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tCinema}` SET `published` = '1' where `{$this->id}` IN({$cids})";
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
		$q = "UPDATE `{$this->tCinema}` SET `published` = '0' where `{$this->id}` IN({$cids})";
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
		$q = "DELETE FROM `{$this->tCinema}` WHERE {$this->id} IN({$cids})";
		$db->setQuery($q);
		$db->query();		
	}
	
	function _save($title, $alias, $img, $desc, $address, $tel, $k_title, $k_desc, $k_keyw, $google, $published) {
		$db = &JFactory::getDBO();
		$db->setQuery("INSERT INTO `{$this->tCinema}` VALUES('', '{$title}', '{$alias}', '{$img}',  '{$desc}', '{$address}', '{$tel}', '{$k_title}', '{$k_desc}', '{$k_keyw}', '{$google}', '{$published}' )");
		$db->query();
		
	}
	
	/**
	 * перезаписывает информацию о комментарии
	 * @param int    $id_comment  ID комментария
	 * @param string $text текст  комментария
	 */
	function _update($id_cinema, $title, $alias, $img, $desc, $address, $tel, $k_title, $k_desc, $k_keyw, $google, $published){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tCinema}` SET 
			`title`   = '{$title}',
			`alias`   = '{$alias}',
			`image`   = '{$img}',
			`desc`    = '{$desc}',
			`address` = '{$address}',
			`tel`     = '{$tel}',
			`k_title`     = '{$k_title}',
			`k_desc`   = '{$k_desc}',
			`k_keyw` = '{$k_keyw}',
			`google` = '{$google}',
			`published` = '{$published}' 
		WHERE `{$this->id}` = '{$id_cinema}'";
		$db->setQuery($q);
		$db->query();
		
		
	}
}

?>