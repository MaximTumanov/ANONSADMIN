<?php
function ContentBuildRoute( &$query )
{
	$segments = array();
	$db = &JFactory::getDBO();

	switch( $query['view'] ):
		case'frontpage':
		case'articles':
			if( isset($query['catid']) ):
				$id_category = $query['catid'];
				$db->setQuery(" SELECT `alias` FROM `#__categories` WHERE `id_categories` = '{$id_category}' ");
				$alias_cat = $db->loadResult();
				$segments[] = $alias_cat;
				unset( $query['catid'] );
			endif;	

			if( isset($query['id']) ):
				$id_item = $query['id'];
				$db->setQuery(" SELECT `alias` FROM `#__content` WHERE id_item = '{$id_item}' ");
				$alias_item = $db->loadResult();
				$segments[] = $alias_item;
				unset( $query['id'] );
			endif;	
			
			if( isset($query['page']) ):
				$segments[] = 'page-'.$query['page'];
				unset( $query['page'] );
			endif;		
		break;
		case'news':
			unset( $query['catid'] );
			if( isset($query['id']) ):
				$id_item = $query['id'];
				$db->setQuery(" SELECT `alias` FROM `#__content` WHERE id_item = '{$id_item}' ");
				$alias_item = $db->loadResult();
				$segments[] = $alias_item;
				unset( $query['id'] );
			endif;	
			
			if( isset($query['page']) ):
				$segments[] = 'page-'.$query['page'];
				unset( $query['page'] );
			endif;		
		break;
		case'item':
			if( $query['layout'] == 'item' ):
				$id_item = $query['id'];
				$db->setQuery(" SELECT `alias` FROM `#__content` WHERE `id_item` = '{$id_item}' ");
				$alias = $db->loadResult();
				$segments[] = $alias;
				unset( $query['id'] );
			else:
				unset( $query['id'] );
			endif;
		break;
	endswitch;

	unset( $query['view'] );
	unset( $query['layout'] );
	return $segments;
}

function ContentParseRoute( $segments )
{

	$vars = array();
	$menu   =& JSite::getMenu();
	$menus	= $menu->getMenu();
	$item   = $menu->getActive();        
	
	$count = count( $segments );         
	$db = &JFactory::getDBO();

	switch( $item->query['view'] ):
		case'frontpage':
		case'services':
		case'item':
			$vars['view']   = $item->query['view'];
			$vars['layout'] = 'item';
			$alias_item     = $segments[0];
			$db->setQuery(" SELECT `id_item` FROM `#__content` WHERE `alias` = '{$alias_item}' ");
			$id             = $db->loadResult();
			$vars['id']     = (int) $id;
		break;
		case'news':
			if( $count == 1 ):
				$vars['view']   = 'news';
				if( preg_match('/page-/', $segments[0]) ):
					$vars['layout'] = 'default';
					$vars['page']   = (int) str_replace('page-','', $segments[0]);
				else:
					$vars['layout'] = 'item';
					$db->setQuery(" SELECT `id_item` FROM `#__content` WHERE `alias` = '{$segments[0]}'  ");
					$id             = $db->loadResult();
					$vars['id']     = (int) $id;
				endif;	
				$vars['catid'] = 12;		
			endif;
		break;
		case'articles':
			if( $count == 1 ):
				$vars['view']   = 'articles';
				if( preg_match('/page-/', $segments[0]) ):
					$vars['layout'] = 'default';
					$vars['page']   = (int) str_replace('page-','', $segments[0]);
				else:
					$vars['layout'] = 'category';
					$db->setQuery(" SELECT `id_categories` FROM `#__categories` WHERE `alias` = '{$segments[0]}'  ");
					$catid          = $db->loadResult();
					$vars['catid']  = (int) $catid;
				endif;
			endif;
			if( $count == 2 ):
				if( preg_match('/page-/', $segments[1]) ):
					$vars['view']   = 'articles';
					$vars['layout'] = 'category';
					$db->setQuery(" SELECT `id_categories` FROM `#__categories` WHERE `alias` = '{$segments[0]}'  ");
					$catid          = $db->loadResult();
					$vars['catid']  = (int) $catid;
					$vars['page']   = (int) str_replace('page-','', $segments[1]);					
				else:
					$vars['view']   = 'articles';
					$vars['layout'] = 'item';	
					$db->setQuery(" SELECT `id_categories` FROM `#__categories` WHERE `alias` = '{$segments[0]}'  ");
					$catid = $db->loadResult();
					$db->setQuery(" SELECT `id_item` FROM `#__content` WHERE `alias` = '{$segments[1]}'  ");
					$id             = $db->loadResult();
					$vars['catid']  = (int) $catid;
					$vars['id']     = (int) $id;					
				endif;
			endif;
		break;
	endswitch;
                       
	return $vars;
}

?>