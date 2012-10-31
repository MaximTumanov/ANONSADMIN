<?php
	$model = $this->getModel('Item');
	$id    = JRequest::getInt('id');
	$item  = $model->getItem($id);
	// устанавливаем мета-данные
	$doc = &JFactory::getDocument();
	$doc->setTitle($item->title);
	$doc->setMetaData('keywords', $item->keywords);
	
	$item->text = $item->desc;
	$params = array();
	
	JPluginHelper::importPlugin( 'content' );
	$dispatcher =& JDispatcher::getInstance();
	$results    = $dispatcher->trigger( 'onPrepareContent', array( &$item, &$params ) );
	
?>	
<div class="content_right inside">
	<h1><?php echo $item->title;?></h1>
	<div><?php echo $item->text;?></div>
</div>
<div class="clearer"></div>
