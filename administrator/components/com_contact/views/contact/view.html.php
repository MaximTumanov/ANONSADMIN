<?php 

jimport('joomla.application.component.view');
class ContactViewContact extends JView
{
	function display($tpl = null)
	{

		JToolBarHelper::save();

		$model = $this->getModel();
		$item  = $model->getItem();
		
		$this->assignRef('item', $item);
		parent::display($tpl);
	}	
}
?>