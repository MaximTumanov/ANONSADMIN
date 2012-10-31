<?php 
jimport('joomla.application.component.controller');

$view   = JRequest::getVar("view");
$layout = JRequest::getVar("layout");

if( $view ):
	( $view == "section" ) 
		? JSubMenuHelper::addEntry(JText::_('Разделы'), 'index.php?option=com_content&view=section',true) 
		: JSubMenuHelper::addEntry(JText::_('Разделы'), 'index.php?option=com_content&view=section');
		
	( $view == "category" )
		? JSubMenuHelper::addEntry(JText::_('Категории'), 'index.php?option=com_content&view=category',true)
		: JSubMenuHelper::addEntry(JText::_('Категории'), 'index.php?option=com_content&view=category');

	( $view == "item" && $layout == "form" )
		? JSubMenuHelper::addEntry(JText::_('Добавить материал'), 'index.php?option=com_content&view=item&layout=form',true)
		: JSubMenuHelper::addEntry(JText::_('Добавить материал'), 'index.php?option=com_content&view=item&layout=form');

	( $view == "item" && $layout != "form" )
		? JSubMenuHelper::addEntry(JText::_('Все материалы'), 'index.php?option=com_content&view=item',true)
		: JSubMenuHelper::addEntry(JText::_('Все материалы'), 'index.php?option=com_content&view=item');
endif;

class ContentController extends JController
{
	function display()
	{
		parent::display();
	}
}
?>