<?php 
/**
* ContentModelItem
* @package Content
* @subpackage Model
*/
jimport('joomla.application.component.model');
class ContentModelItem extends JModel
{
	/**
	* @var string $table таблица с материалами
	*/	
	var $table = '#__content';
	/**
	* @var string $id поле в таблице с PRIMARY KEY
	*/	
	var $id    = 'id_item';
	/**
	* возвращает кол-во записей с учетом фильтров
	* @param array $lists array('id_section' => ID раздела, 'id_category' => ID категории)
	* @return int
	*/		
	function getItemsCount($lists)
	{
		$db = &JFactory::getDBO();
		$q = " SELECT count(i.id_item) FROM `{$this->table}` as i ";
		$q .= " JOIN `#__content_xref` as x ON i.id_item = x.id_item ";
	
		$q .= " WHERE i.id_item > 0 ";
		
		if( $lists['id_section'] ):
			$q .= " AND x.id_section = ".$lists['id_section'];
		endif;

		if( $lists['id_category'] ):
			$q .= " AND x.id_category = ".$lists['id_category'];
		endif;


		$db->setQuery($q);
		return $db->loadResult();
	}
	/**
	* получает массив записей с учетом фильтров
	* @param array $lists array('order' => по какому полю сортируем, 'order_Dir' => ASC или DESC, 'id_section' => ID раздела, 'id_category' => ID категории)
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
		$q = " SELECT i.* FROM `{$this->table}` AS i ";
		$q .= " JOIN `#__content_xref` as x ON i.id_item = x.id_item ";
		
		$q .= " WHERE i.id_item > 0 ";
		
		if( $lists['id_section'] ):
			$q .= " AND x.id_section = ".$lists['id_section'];
		endif;

		if( $lists['id_category'] ):
			$q .= " AND x.id_category = ".$lists['id_category'];
		endif;
			
		$q .= $order;
		$db->setQuery($q, $limitstart, $limit);
		return $db->loadObjectList();
	}
	/**
	* возвращает информацию о материале
	* @param int $id ID материала
	* @return object
	*/
	function getItem($id)
	{
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT * FROM `{$this->table}` WHERE `{$this->id}` = '{$id}' ");
		return $db->loadObject();	
	}
	/**
	* возвращает ID раздела и категории для записи
	* @param int $id_item ID записи
	* @return object
	*/
	function getXrefData($id_item)
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" SELECT id_section, id_category FROM `#__content_xref` WHERE `{$this->id}` = '{$id_item}' ");
		return $db->loadObject();
	}
	/**
	* возвращает название раздела
	* @param int $id_section ID раздела
	* @return string
	*/	
	function getSectionName($id_section)
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" SELECT title FROM `#__section` WHERE `id_section` = '{$id_section}' ");
		return $db->loadResult();
	}
	/**
	* возвращает список разделов
	* @return array
	*/		
	function getSectionList()
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" SELECT `id_section` as `value`, `title` as `text` FROM `#__section` ORDER BY `title` ");
		return $db->loadObjectList();
	}
	/**
	* возвращает название категории
	* @param int $id_category 
	* @return string
	*/	
	function getCategoryName($id_category)
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" SELECT `title` FROM `#__categories` WHERE `id_category` = '{$id_category}' ");
		return $db->loadResult();
	}
	/**
	* возвращает список категорий
	* @return array
	*/		
	function getCategoryList()
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" SELECT `id_category` as `value`, `title` as `text` FROM `#__categories` ORDER BY `title` ");
		return $db->loadObjectList();
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
	 * возвращает имя пользователя, разместившего материал
	 * @param  int    $id_user ID пользователя
	 * @param  bool   $isJUser 1 | 0
	 * @return object
	 */
	function getUserName($id_user, $isJUser)
	{
		$db = &JFactory::getDBO();
		if( $isJUser ):
			$db->setQuery(" SELECT `name` as `title` FROM `#__users` WHERE `id` = '{$id_user}' ");
		else:
			$db->setQuery(" SELECT `title`,`login` FROM `#__as_partner` WHERE `id_partner` = '{$id_user}' ");
		endif;
		return $db->loadObject();
	}
	/**
	* публикует материал на главной
	* @param int $id ID материала
	* @return void
	*/
	function _onfront($id)
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" UPDATE `{$this->table}` SET `on_front` = '1' WHERE `{$this->id}` = '{$id}' ");
		$db->query();		
	}
	/**
	* снимает с публикации материал на главной
	* @param int $id ID материала
	* @return void
	*/
	function _unonfront($id)
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" UPDATE `{$this->table}` SET `on_front` = '0' WHERE `{$this->id}` = '{$id}' ");
		$db->query();		
	}	
	/**
	* копирует выбранные записи
	* @param int $val ID записи
	* @return void
	*/
	function _copySelect($val)
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" SELECT * FROM `{$this->table}` WHERE `{$this->id}` = '{$val}' ");
		$item = $db->loadObject();
			
		$title     = "Копия {$item->title}";
		$alias     = "copy-{$item->alias}";
			
		$s_desc    = $item->s_desc;
		$desc      = $item->desc;
		$keywords  = $item->keywords;
		$date      = date("Y-m-d H:i:s");
		$on_front  = $item->on_front;
		$published = $item->published;
	
		$ordering  = ($this->getMaxOrdering() + 1);
			
		$db->setQuery(" INSERT INTO `{$this->table}` VALUES('', '{$title}', '{$alias}', '{$s_desc}', '{$desc}', '{$keywords}', '{$date}', '{$on_front}', '{$ordering}', '{$published}', '', '62', '1') ");
		$db->query();
			
		$id_item = $db->insertid();
			
		$db->setQuery(" SELECT `id_section`, `id_category` FROM `#__content_xref` WHERE `{$this->id}` = '{$val}' ");
		$xref = $db->loadObject();
			
		$id_section  = $xref->id_section;
		$id_category = $xref->id_category;
			
		$db->setQuery(" INSERT INTO `#__content_xref` VALUES('{$id_item}', '{$id_section}', '{$id_category}') ");
		$db->query();
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
		$db->setQuery(" UPDATE `{$this->table}` SET `ordering` = '{$val}' WHERE `{$this->id}` = '{$key}' ");
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
	* @param string   $title       заголовок
	* @param string   $alias       псевдоним, используется в ЧПУ
	* @param string   $desc        полное описание
	* @param string   $keywords    ключевые слова
	* @param datetime $date дата   публикации в формате Y-m-d H:i:s
	* @param int      $on_front    1 или 0 ( 1 - выводится на главной, 0 - не выводится на главной )
	* @param int 	  $ordering    порядок показа
	* @param int 	  $published   1 или 0 ( 1 - опубликовано, 0 - неопубликовано )
	* @param int 	  $id_section  ID раздела
	* @param int 	  $id_category ID категоории
	* @param string   $img         название изображения
	* @param int      $id_user     ID пользователя
	* @return void
	*/
	function _save($title, $alias, $s_desc, $desc, $keywords, $date, $on_front, $ordering, $published, $id_section, $id_category, $img, $id_user)
	{
		$db = &JFactory::getDBO();
		$q = " INSERT INTO `{$this->table}` VALUES('', '{$title}', '{$alias}', '{$s_desc}', '{$desc}', '{$keywords}', '{$date}','0', '{$ordering}', '{$published}', '{$img}', '{$id_user}', '1') ";
		$db->setQuery($q);
		$db->query();	
			$id_item = $db->insertid();
		$db->setQuery(" INSERT INTO `#__content_xref` VALUES('{$id_item}', '{$id_section}', '{$id_category}') ");
		$db->query();
	}
	/**
	* перезаписывает запись
	* @param int      $id           ID записи
	* @param string   $title        заголовок
	* @param string   $alias        псевдоним, используется в ЧПУ
	* @param string   $desc         полное описание
	* @param string   $keywords     ключевые слова
	* @param datetime $date         дата публикации в формате Y-m-d H:i:s
	* @param int      $published    1 или 0 ( 1 - опубликовано, 0 - неопубликовано )
	* @param int      $id_section   ID раздела
	* @param int      $id_category  ID категоории
	* @param string   $img          название изображения
	* @param int      $id_user      ID пользователя
	* @return void
	*/
	function _update($id, $title, $alias, $s_desc, $desc, $keywords, $date, $published, $id_section, $id_category, $img, $id_user)
	{
		$db = &JFactory::getDBO();
		$q = " UPDATE `{$this->table}` SET 
		`title` = '{$title}', 
		`alias` = '{$alias}', 
		`s_desc` = '{$s_desc}', 
		`desc` = '{$desc}', 
		`keywords` = '{$keywords}', 
		`date` = '{$date}', 
		`published` = '{$published}', 
		`image` = '{$img}',
		`id_user` = '{$id_user}' 
		WHERE {$this->id} = '{$id}' ";
		$db->setQuery($q);
		$db->query();
			
		$db->setQuery(" UPDATE `#__content_xref` SET `id_section` = '{$id_section}', `id_category` = '{$id_category}' WHERE {$this->id} = '{$id}' ");
		$db->query();		
	}
	
	
}

?>