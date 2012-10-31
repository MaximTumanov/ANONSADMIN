<?php
jimport('joomla.application.component.model');
/**
* Content Component Content Articles
* @package Anons
* @subpackage Content
*/
class ContentModelArticles extends JModel
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
	
	/**
	* возвращает список статей
	* @param int    $limitstart  точка отчета
	* @param int    $limit       кол-во статей на странице
	* @param int    $id_category ID категории статей, по умолчанию FALSE
	* @param string $word        поисковый запрос
	* @return array
	*/
	function getItemList($limitstart, $limit, $id_category=false, $word=false)
	{
		$db = &JFactory::getDBO();
		
		$q = " SELECT GROUP_CONCAT(distinct id_item) FROM `{$this->tXref}` WHERE `id_section` = '2' ";
		if( $id_category ):
			$q .= " AND `id_category` = '{$id_category}' ";
		endif;
		$db->setQuery($q);
		$list_id_item = $db->loadResult();

		if( empty($list_id_item) ) return NULL;
		
		$q = " SELECT * FROM `{$this->tItem}` WHERE `{$this->id}` IN({$list_id_item}) AND `published` = '1' ";
		if( $word ):
			$good = $this->_word($word);
			$q .= " AND ( `title` LIKE '%". str_replace(" ", "%' OR `title` LIKE '%", $good). "%' OR `desc` LIKE '%". str_replace(" ", "%' OR `desc` LIKE '%", $good). "%' ) ";
		endif;
		$q .= " ORDER BY `ordering` ";
		$db->setQuery($q, $limitstart, $limit);
		return $db->loadObjectList();
	}
	/**
	* возвращает информацию выбранной статьи
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
	* возвращает кол-во статей
	* @param int    $id_category ID категории статей, по умолчанию FALSE
	* @param string $word        поисковый запрос
	* @return int
	*/
	function getItemCount($id_category=false, $word=false)
	{
		$db = &JFactory::getDBO();
		
		$q = " SELECT GROUP_CONCAT(distinct id_item) FROM `{$this->tXref}` WHERE `id_section` = '2' ";
		if( $id_category ):
			$q .= " AND `id_category` = '{$id_category}' ";
		endif;
		$db->setQuery($q);
		$list_id_item = $db->loadResult();
		
		if( empty($list_id_item) ) return NULL;
		
		$q = " SELECT count(*) FROM `{$this->tItem}` WHERE `{$this->id}` IN({$list_id_item}) AND `published` = '1' ";
		if( $word ):
			$good = $this->_word($word);
			$q .= " AND ( `title` LIKE '%". str_replace(" ", "%' OR `title` LIKE '%", $good). "%' OR `desc` LIKE '%". str_replace(" ", "%' OR `desc` LIKE '%", $good). "%' ) ";
		endif;
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
	* возвращает информацию о категории статей
	* @param int $id_category ID категории статей
	* @return object
	*/
	function getCatData($id_category)
	{
		$db = &JFactory::getDBO();
		$q = " SELECT * FROM `#__categories` WHERE `id_categories` = '{$id_category}' ";
		$db->setQuery($q);
		return $db->loadObject();
	}
	/**
	 * возвращает список комментариев для материала
	 * @param int $id ID материала
	 * @return array
	 */
	function getItemCommentList($id)
	{
		$db = &JFactory::getDBO();
		$q = " SELECT * FROM `{$this->tComment}` USE INDEX(IK_id_article)
			   WHERE `id_article` = '{$id}' AND
			   		 `type`       = '2' AND 
			   		 `published`  = '1' 
			   ORDER BY `date` ASC ";
		$db->setQuery($q); 
		return $db->loadObjectList();
	}
	/**
	* возвращает кол-во комментариев для объявления
	* @param ind $id_notice ID объявления
	* @param int $type тип комментариев ( 1 - объявления, 2 - материалы )
	* @return int кол-во комментариев
	*/
	function getItemCommentCount($id, $type)
	{
		$db = &JFactory::getDBO();
		$q = " SELECT count(*) FROM `{$this->tComment}` 
				WHERE `id_article` = '{$id}' AND 
				      `type`       = '{$type}' AND
				      `published`  = '1'  ";
		$db->setQuery($q);
		return $db->loadResult();
	}
	/**
	* возвращает информацию о пользователе
	* @param int $id      ID пользователя
	* @return object
	*/
	function getUserInfo($id, $isAdmin=0)
	{
		$db = &JFactory::getDBO();
		if( $isAdmin ):
			$q = " SELECT `name` FROM `#__users` WHERE `id` = '{$id}' ";
		else:
			$q = " SELECT `id_partner`, `title`, `login`, `published`, `avatar`, `referer`, `activ` FROM `{$this->tPartner}` WHERE `id_partner` = '{$id}' ";
		endif;
		$db->setQuery($q); 
		return $db->loadObject();
	}
	
	function _word($word)
	{
		$search = substr($word, 0, 64);
		$search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $search);
		$good = trim(preg_replace("/\s(\S{1,2})\s/", " ", ereg_replace(" +", "  "," $search ")));
		$good = ereg_replace(" +", " ", $good);
		if(empty($good)) $good = '-_-';
		return $good;
	}
	/**
	 * возвращает информацию о партнере его представителя
	 * @param string $referer Код партнера
	 * @return object
	 */	
	function getRefererPartner($referer)
	{
		$db = &JFactory::getDBO();
		$db->setQuery(" SELECT `title`, `id_partner` FROM `{$this->tPartner}` WHERE `referer` = '{$referer}' AND `published` = '1' ");
		return $db->loadObject();
	}
}
?>