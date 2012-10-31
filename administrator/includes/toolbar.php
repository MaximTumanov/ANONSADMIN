<?php
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.html.toolbar');
class JToolBarHelper
{

	function title($title, $icon = 'generic.png')
	{
		global $mainframe;
		$icon	= preg_replace('#\.[^.]*$#', '', $icon);

		$html  = "<div class=\"header icon-48-$icon\">\n";
		$html .= "$title\n";
		$html .= "</div>\n";

		$mainframe->set('JComponentTitle', $html);
	}

	function spacer($width = '')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Separator', 'spacer', $width );
	}

	function divider()
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Separator', 'divider' );
	}

	function custom($task = '', $icon = '', $iconOver = '', $alt = '', $listSelect = true, $x = false)
	{
		$bar = & JToolBar::getInstance('toolbar');
		$icon	= preg_replace('#\.[^.]*$#', '', $icon);
		$bar->appendButton( 'Standard', $icon, $alt, $task, $listSelect, $x );
	}

	function customX($task = '', $icon = '', $iconOver = '', $alt = '', $listSelect = true)
	{
		$bar = & JToolBar::getInstance('toolbar');
		$icon	= preg_replace('#\.[^.]*$#', '', $icon);
		$bar->appendButton( 'Standard', $icon, $alt, $task, $listSelect, true );
	}

	function preview($url = '', $updateEditors = false)
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Popup', 'preview', 'Preview', "$url&task=preview" );
	}

	function help($ref, $com = false)
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Help', $ref, $com );
	}

	function back($alt = 'Back', $href = 'javascript:history.back();')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Link', 'back', $alt, $href );
	}

	function media_manager($directory = '', $alt = 'Upload')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Popup', 'upload', $alt, "index.php?option=com_media&tmpl=component&task=popupUpload&directory=$directory", 640, 520 );
	}

	function addNew($task = 'add', $alt = 'New')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'new', $alt, $task, false, false );
	}

	function addNewX($task = 'add', $alt = 'New')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'new', $alt, $task, false, true );
	}

	function publish($task = 'publish', $alt = 'Publish')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'publish', $alt, $task, false, false );
	}

	function publishList($task = 'publish', $alt = 'Publish')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'publish', $alt, $task, true, false );
	}

	function makeDefault($task = 'default', $alt = 'Default')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'default', $alt, $task, true, false );
	}

	function assign($task = 'assign', $alt = 'Assign')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'assign', $alt, $task, true, false );
	}

	function unpublish($task = 'unpublish', $alt = 'Unpublish')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'unpublish', $alt, $task, false, false );
	}

	function unpublishList($task = 'unpublish', $alt = 'Unpublish')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'unpublish', $alt, $task, true, false );
	}

	function archiveList($task = 'archive', $alt = 'Archive')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'archive', $alt, $task, true, false );
	}

	function unarchiveList($task = 'unarchive', $alt = 'Unarchive')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'unarchive', $alt, $task, true, false );
	}

	function editList($task = 'edit', $alt = 'Edit')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'edit', $alt, $task, true, false );
	}

	function editListX($task = 'edit', $alt = 'Edit')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'edit', $alt, $task, true, true );
	}

	function editHtml($task = 'edit_source', $alt = 'Edit HTML')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'edithtml', $alt, $task, true, false );
	}

	function editHtmlX($task = 'edit_source', $alt = 'Edit HTML')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'edithtml', $alt, $task, true, true );
	}

	function editCss($task = 'edit_css', $alt = 'Edit CSS')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'editcss', $alt, $task, true, false );
	}

	function editCssX($task = 'edit_css', $alt = 'Edit CSS')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'editcss', $alt, $task, true, true );
	}

	function deleteList($msg = '', $task = 'remove', $alt = 'Delete')
	{
		$bar = & JToolBar::getInstance('toolbar');
		if ($msg) {
			$bar->appendButton( 'Confirm', $msg, 'delete', $alt, $task, true, false );
		} else {
			$bar->appendButton( 'Standard', 'delete', $alt, $task, true, false );
		}
	}

	function deleteListX($msg = '', $task = 'remove', $alt = 'Delete')
	{
		$bar = & JToolBar::getInstance('toolbar');
		if ($msg) {
			$bar->appendButton( 'Confirm', $msg, 'delete', $alt, $task, true, true );
		} else {
			$bar->appendButton( 'Standard', 'delete', $alt, $task, true, true );
		}
	}

	function trash($task = 'remove', $alt = 'Trash', $check = true)
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'trash', $alt, $task, $check, false );
	}

	function apply($task = 'apply', $alt = 'Apply')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'apply', $alt, $task, false, false );
	}

	function save($task = 'save', $alt = 'Save')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'save', $alt, $task, false, false );
	}

	function cancel($task = 'cancel', $alt = 'Cancel')
	{
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Standard', 'cancel', $alt, $task, false, false );
	}

	function preferences($component, $height='150', $width='570', $alt = 'Preferences', $path = '')
	{
		$user =& JFactory::getUser();
		if ($user->get('gid') != 25) {
			return;
		}

		$component	= urlencode( $component );
		$path		= urlencode( $path );
		$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Popup', 'config', $alt, 'index.php?option=com_config&amp;controller=component&amp;component='.$component.'&amp;path='.$path, $width, $height );
	}
}


class JSubMenuHelper
{
	function addEntry($name, $link = '', $active = false)
	{
		$menu = &JToolBar::getInstance('submenu');
		$menu->appendButton($name, $link, $active);
	}
}
?>
