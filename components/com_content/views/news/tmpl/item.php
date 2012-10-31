<?php
	$model = $this->getModel('News');
	$id    = JRequest::getInt('id');
	$item  = $model->getItem($id);
	// устанавливаем мета-данные
	$doc = &JFactory::getDocument();
	$doc->setTitle($item->title);
	$doc->setMetaData('keywords', $item->keywords);
	$date     = JHTML::_('date', $item->date);
	
	$item->desc = str_replace("images/stories/", "{$this->baseurl}/images/stories/", $item->desc);
	
?>
<div class="content_right inside">
	<h1><?php echo $item->title?></h1>
	<h3><?php echo $date?></h3>
	<div><?php echo $item->desc?></div>
</div>
<div class="clearer"></div>