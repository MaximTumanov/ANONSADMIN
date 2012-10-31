<?php
defined('_JEXEC') or die('Restricted access');

$user = & JFactory::getUser();
if (!$user->authorize('com_installer', 'installer')) {
	$mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
}

$ext	= JRequest::getWord('type');

$subMenus = array(
	'Components' => 'components',
	'Plugins' => 'plugins',
	'Languages' => 'languages');

JSubMenuHelper::addEntry(JText::_( 'Install' ), '#" onclick="javascript:document.adminForm.type.value=\'\';submitbutton(\'installer\');', !in_array( $ext, $subMenus));
foreach ($subMenus as $name => $extension) {
	JSubMenuHelper::addEntry(JText::_( $name ), '#" onclick="javascript:document.adminForm.type.value=\''.$extension.'\';submitbutton(\'manage\');', ($extension == $ext));
}

require_once( JPATH_COMPONENT.DS.'controller.php' );

$controller = new InstallerController( array('default_task' => 'installform') );
$controller->execute( JRequest::getCmd('task') );
$controller->redirect();
