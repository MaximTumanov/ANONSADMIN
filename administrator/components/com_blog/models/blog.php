<?php 
jimport('joomla.application.component.model');
class BlogModelBlog extends JModel {
	var $tBlog = '#__blog';
	/**
	* @var string $id поле в таблице с PRIMARY KEY
	*/	
	var $id    = 'id_blog';
	/**
	* возвращает кол-во записей с учетом фильтров
	* @return int
	*/		
	function getItemsCount($lists){
		$db = &JFactory::getDBO();
		$q = " SELECT count(*) FROM `{$this->tBlog}` as c WHERE `{$this->id}` > 0 ";
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
		

		$q = " SELECT c.* FROM `{$this->tBlog}` AS c WHERE `{$this->id}` > 0 ";	
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
		$db->setQuery("SELECT * FROM {$this->tBlog} WHERE `{$this->id}` = '{$id}' ");
		return $db->loadObject();	
	}
	/**
	* публикует выбранные записи
	* @param string $cids список ID записей через запятую (1,2,3,4....)
	* @return void
	*/
	function _publish($cids){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tBlog}` SET `published` = '1' where `{$this->id}` IN({$cids})";
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
		$q = "UPDATE `{$this->tBlog}` SET `published` = '0' where `{$this->id}` IN({$cids})";
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
		$q = " DELETE FROM `{$this->tBlog}` WHERE {$this->id} IN({$cids}) ";
		$db->setQuery($q);
		$db->query();		
	}

	function _save($title, $alias, $s_desc, $desc, $date, $published, $k_title, $k_desc, $k_keyw, $img, $images_folder) {
		$db = &JFactory::getDBO();
		$q = "INSERT INTO `{$this->tBlog}` VALUES('', '{$title}', '{$alias}', '{$s_desc}', '{$desc}', '{$date}', '{$published}', '{$k_title}', '{$k_desc}', '{$k_keyw}', '{$img}', '{$images_folder}')";
		$db->setQuery($q);
		$db->query();
	}
	/**
	 * перезаписывает информацию о комментарии
	 * @param int    $id_comment  ID комментария
	 * @param string $text текст  комментария
	 */
	function _update($id_blog, $title, $alias, $s_desc, $desc, $date, $published, $k_title, $k_desc, $k_keyw, $img, $images_folder){
		$db = &JFactory::getDBO();
		$q = "UPDATE `{$this->tBlog}` SET 
			`title`   = '{$title}',
			`alias`   = '{$alias}',
			`s_desc`  = '{$s_desc}',
			`desc`    = '{$desc}',
			`date`    = '{$date}',
			`published` = '{$published}',
			`k_title` = '{$k_title}',
			`k_desc` = '{$k_desc}',
			`k_keyw` = '{$k_keyw}',
			`image`  = '{$img}',
			`images_folder` = '{$images_folder}'
		WHERE `{$this->id}` = '{$id_blog}'";
		$db->setQuery($q);
		$db->query();		
	}	
}

?>