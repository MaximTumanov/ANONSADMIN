<?php

defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class ContentViewFrontpage extends JView
{
	function display($tpl = null)
	{
		global $mainframe;

		$db			=& JFactory::getDBO();
		$document	=& JFactory::getDocument();
		$params =& $mainframe->getParams();
		$feedEmail = (@$mainframe->getCfg('feed_email')) ? $mainframe->getCfg('feed_email') : 'author';
		$siteEmail = $mainframe->getCfg('mailfrom');

		$component =& JComponentHelper::getComponent('com_content');
		
		$menu   =& JSite::getMenu();
		$items  = $menu->getItems('componentid', $component->id);
	
		foreach( $items as $item ):
			if( $item->id == 20 ):
				$menulinkNews = $item->route;
			endif;
			if( $item->id == 22 ):
				$menulinkArticle = $item->route;
			endif;
		endforeach;

		$component =& JComponentHelper::getComponent('com_partner');
		
		$menu   =& JSite::getMenu();
		$items  = $menu->getItems('componentid', $component->id);		

		foreach( $items as $item ):
			if( $item->id == 11 ):
				$menulinkNotice = $item->route;
			endif;
		endforeach;		
		
		$document->link = JURI::base()."{$menulinkNews}/";
		
		$item = new JFeedItem();
		$item->title  = 'Новости';
		$item->link   = JURI::base()."{$menulinkNews}/";
		$document->addItem( $item );
		
		$q = " SELECT content.* FROM `#__content` as `content` ";
		$q .= " JOIN `#__content_xref` as `xref` ON content.id_item = xref.id_item ";
		$q .= " WHERE content.published = '1' ";
		$q .= " AND xref.id_section = '1' ";
		$q .= " ORDER BY content.date DESC ";
		$db->setQuery($q, 0, 5);
		$rows = $db->loadObjectList();

		foreach ( $rows as $row )
		{
			$link               = JURI::base()."{$menulinkNews}/{$row->alias}/";								
			$item = new JFeedItem();
			$item->title 		= $row->title;
			$item->link 		= $link;
			$item->description 	= $row->s_desc;
			$item->date			= $row->date;
			$item->category   	= 'News';
			$item->author		= '';
			if ($feedEmail == 'site') {
				$item->authorEmail = $siteEmail;
			}
			else {
				$item->authorEmail = $row->author_email;
			}
			// loads item info into rss array
			$document->addItem( $item );
		}

		$item = new JFeedItem();
		$item->title  = 'Статьи';
		$item->link   = JURI::base()."{$menulinkArticle}/";
		$document->addItem( $item );				
	}
}
?>
