<?php
	$model = $this->getModel('Frontpage');
	if( $item  = $model->getFrontpageItem() ){
		// устанавливаем мета-данные
		$doc = &JFactory::getDocument();
		$doc->setMetaData('keywords', $item->keywords);
		
		$link =  JRoute::_("index.php?option=com_content&view=item&Itemid=4&id={$item->id_item}");
		
	}
	
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT * FROM `#__content` WHERE published='1'");
		$item = $db->loadCache('loadObjectList');

?>
