<?php
	$model   = $this->getModel('Item');
	$id_item = JRequest::getInt('id');
	$item    = $model->getItem($id_item);
	
	// устанавливаем мета-данные
	$doc = &JFactory::getDocument();
	$doc->setTitle($item->title);
	$doc->setMetaData('keywords', $item->keywords);	

	
	$item->desc = str_replace("images/stories/", "{$this->baseurl}/images/stories/", $item->desc); 

?>
<div class="content_right">
	<h1><?php echo $item->title?></h1>
	<div><?php echo $item->desc?></div>
</div>
<div class="clearer"></div>