<?php
defined('_JEXEC') or die('Restricted access');

$lang	=& JFactory::getLanguage();
$doc	=& JFactory::getDocument();
$user	=& JFactory::getUser();
echo JAdminSubMenu::get();

class JAdminSubMenu
{
	function get()
	{
		global $mainframe;

		// Lets get some variables we are going to need
		$menu = JToolBar::getInstance('submenu');
		$list = $menu->_bar;
		if(!is_array($list) || !count($list))
		{
			$option = JRequest::getCmd('option');
			if($option == 'com_categories')
			{
				$section = JRequest::getCmd('section');
				if ($section) {
					if ($section != 'content') {
						// special handling for specific core components
						$map['com_contact_details']	= 'com_contact';
						$map['com_banner']			= 'com_banners';

						$option = isset( $map[$section] ) ? $map[$section] : $section;
					}
				}
			}
			$list = JAdminSubMenu::_loadDBList($option);
		}

		if (!is_array($list) || !count($list)) {
			return null;
		}

		$hide = JRequest::getInt('hidemainmenu');
		$txt = "<ul id=\"submenu\">\n";

		/*
		 * Iterate through the link items for building the menu items
		 */
		foreach ($list as $item)
		{
			$txt .= "<li>\n";
			if ($hide)
			{
				if (isset ($item[2]) && $item[2] == 1) {
					$txt .= "<span class=\"nolink active\">".$item[0]."</span>\n";
				}
				else {
					$txt .= "<span class=\"nolink\">".$item[0]."</span>\n";
				}
			}
			else
			{
				if (isset ($item[2]) && $item[2] == 1) {
					$txt .= "<a class=\"active\" href=\"".JFilterOutput::ampReplace($item[1])."\">".$item[0]."</a>\n";
				}
				else {
					$txt .= "<a href=\"".JFilterOutput::ampReplace($item[1])."\">".$item[0]."</a>\n";
				}
			}
			$txt .= "</li>\n";
		}

		$txt .= "</ul>\n";

		return $txt;
	}

	function _loadDBList( $componentOption )
	{
		$db   =& JFactory::getDBO();
		$lang =& JFactory::getLanguage();

		$lang->load($componentOption.'.menu');

		$query = 'SELECT a.name, a.admin_menu_link, a.admin_menu_img' .
		' FROM #__components AS a' .
		' INNER JOIN #__components AS b ON b.id = a.parent' .
		' WHERE b.option = ' . $db->Quote( $componentOption ) .
		' AND b.parent = 0'.
		' ORDER BY a.ordering ASC';

		$db->setQuery($query);
		$items = $db->loadObjectList();

		// Process the items
		$subMenuList = array();

		foreach ($items as $item)
		{
			if (trim($item->admin_menu_link))
			{
				// handling for active sub menu item
				$active = 0;
				if (strpos( @$_SERVER['QUERY_STRING'], $item->admin_menu_link ) !== false ) {
					$active = 1;
				}

				$key = $componentOption.'.'.$item->name;
				$subMenuItem[0]	= $lang->hasKey($key) ? JText::_($key) : $item->name;
				$subMenuItem[1]	= 'index.php?'. $item->admin_menu_link;
				$subMenuItem[2]	= $active;

				$subMenuList[] = $subMenuItem;
			}
		}

		return $subMenuList;
	}
}