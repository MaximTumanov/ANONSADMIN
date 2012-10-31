<?php
defined('_JEXEC') or die('Restricted access');

$user = & JFactory::getUser();
if (!$user->authorize('com_templates', 'manage')) {
	$mainframe->redirect('index.php', JText::_('ALERTNOTAUTH'));
}

JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_templates'.DS.'tables');

require_once (JPATH_COMPONENT.DS.'helpers'.DS.'template.php');
require_once (JPATH_COMPONENT.DS.'controller.php');

$task = JRequest::getCmd('task');

$client	= JRequest::getVar('client', 0, '', 'int');

switch ($task)
{
	case 'edit' :
		TemplatesController::editTemplate();
		break;

	case 'save'  :
	case 'apply' :
		TemplatesController::saveTemplate();
		break;

	case 'edit_source' :
		TemplatesController::editTemplateSource();
		break;

	case 'save_source'  :
	case 'apply_source' :
		TemplatesController::saveTemplateSource();
		break;

	case 'choose_css' :
		TemplatesController::chooseTemplateCSS();
		break;

	case 'edit_css' :
		TemplatesController::editTemplateCSS();
		break;

	case 'save_css'  :
	case 'apply_css' :
		TemplatesController::saveTemplateCSS();
		break;

	case 'publish' :
	case 'default' :
		TemplatesController::publishTemplate();
		break;

	case 'cancel' :
		TemplatesController::cancelTemplate();
		break;

	case 'save_positions' :
		TemplatesController::savePositions();
		break;

	case 'preview' :
		TemplatesController::previewTemplate();
		break;

	default :
		TemplatesController::viewTemplates();
		break;
}