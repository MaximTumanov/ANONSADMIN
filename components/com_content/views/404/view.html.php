<?php 

jimport('joomla.application.component.view');
class ContentView404 extends JView
{
	function display($tpl=null)
	{
		global $mainframe;
		$params	= &$mainframe->getParams();
		$this->assignRef('params', $params);
		parent::display($tpl);
	}
}

?>