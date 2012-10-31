<?php
jimport('joomla.application.component.model');
/**
* Content Component Content News
* @package Anons
* @subpackage Content
*/
class ContentModelNews extends JModel
{
	/**
	* @var string $table_item таблица с материалами
	*/
	var $tItem    = '#__content';
	/**
	* @var string $table_xref
	*/
	var $tXref    = '#__content_xref';
	/**
	* @var string $id поле с Primary Key
	*/
	var $id            = 'id_item';
	/**
	* @var string $table_comment таблица с комментариями
	*/
	var $tComment = '#__as_comment';
	/**
	* @var string $table_user таблица с информацией о пользователях
	*/
	var $tPartner    = '#__as_partner';
	
	var $newsSection         = 1;
	var $published           = 1;
	var $articlesCommentType = 2;
	
	/**
	* возвращает список новостей
	* @param int $limitstart точка отчета
	* @param int $limit кол-во статей на странице
	* @return array
	*/
	function getItemList($limitstart, $limit)
	{
		$db = &JFactory::getDBO();

		$q = " SELECT GROUP_CONCAT(distinct id_item) FROM `{$this->tXref}` WHERE `id_section` = '{$this->newsSection}' ";
		$db->setQuery($q);
		$list_id_item = $db->loadResult();

		if( empty($list_id_item) ) return NULL;
		
		$q = " SELECT * FROM `{$this->tItem}` 
				WHERE `{$this->id}` IN({$list_id_item}) 
				AND `published` = '{$this->published}' 
				ORDER BY `date` DESC ";
		$db->setQuery($q, $limitstart, $limit); 
		return $db->loadObjectList();
	}
	/**
	* возвращает информацию выбранной новости
	* @param int $id_item ID статьи
	* @return object
	*/
	function getItem($id_item)
	{
		$db = &JFactory::getDBO();
		$q = " SELECT * FROM `{$this->tItem}` WHERE `{$this->id}` = '{$id_item}' ";
		$db->setQuery($q);
		return $db->loadObject();
	}
	/**
	* возвращает кол-во новостей
	* @return int
	*/
	function getItemCount()
	{
		$db = &JFactory::getDBO();
		
		$q = " SELECT GROUP_CONCAT(distinct id_item) FROM `{$this->tXref}` WHERE id_section = '{$this->newsSection}' ";
		$db->setQuery($q);
		$list_id_item = $db->loadResult();
		
		if( empty($list_id_item) ) return NULL;
		
		$q = " SELECT count(*) FROM `{$this->tItem}` 
				WHERE `{$this->id}` IN({$list_id_item}) 
				AND `published` = '{$this->published}' ";
		$db->setQuery($q);
		return $db->loadResult();
	}
	/**
	* возвращает информацию о категории статей выбранной статьи
	* @param int $id_item ID статьи
	* @return object
	*/
	function getItemCatData($id_item)
	{
		$db = &JFactory::getDBO();
		$q = " SELECT category.id_categories, category.title FROM `#__categories` as `category` ";
		$q .= " JOIN `{$this->tXref}` as `xref` ON category.id_categories = xref.id_category ";
		$q .= " WHERE xref.id_item = '{$id_item}' ";
		$db->setQuery($q);
		return $db->loadObject();
	}
	/**
	 * возвращает список комментариев для новости
	 * @param int $id ID материала
	 * @return array
	 */
	function getItemCommentList($id)
	{
		$db = &JFactory::getDBO();
		$q = " SELECT * FROM `{$this->tComment}` USE INDEX(IK_id_article) 
				WHERE `id_article` = '{$id}' AND 
				      `type`       = '{$this->articlesCommentType}' AND 
				      `published`  = '{$this->published}' 
				ORDER BY `date` ASC ";
		$db->setQuery($q); 
		return $db->loadObjectList();
	}
	/**
	* возвращает кол-во комментариев для новости
	* @param ind $id_notice ID объявления
	* @param int $type тип комментариев ( 1 - объявления, 2 - материалы )
	* @return int кол-во комментариев
	*/
	function getItemCommentCount($id, $type)
	{
		$db = &JFactory::getDBO();
		$q = " SELECT count(*) FROM `{$this->tComment}` USE INDEX(IK_id_article)
				WHERE `id_article` = '{$id}' AND 
				      `type`       = '{$type}' AND 
				      `published`  = '{$this->published}' ";
		$db->setQuery($q);
		return $db->loadResult();
	}
	/**
	* возвращает информацию о пользователе
	* @param int $id ID пользователя
	* @return object
	*/
	function getUserInfo($id)
	{
		$db = &JFactory::getDBO();
		$q = " SELECT * FROM `{$this->tPartner}` WHERE `id_partner` = '{$id}' ";
		$db->setQuery($q); 
		return $db->loadObject();
	}
}
?>