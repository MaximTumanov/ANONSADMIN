<?php
	$model    = $this->getModel('Articles');
	$id       = JRequest::getInt('id');
	$item     = $model->getItem($id);
	$category = $model->getItemCatData($id);
	$category_link = JRoute::_("index.php?option=com_content&view=articles&layout=category&catid={$category->id_category}");
	// устанавливаем мета-данные
	$doc = &JFactory::getDocument();
	$doc->setTitle($item->title);
	$doc->setMetaData('keywords', $item->keywords);

	$item->desc = str_replace("images/stories/", "{$this->baseurl}/images/stories/", $item->desc); 
	
?>
<div class="content_right inside">
	<h1><?php echo $item->title?></h1>
	<h3><a href="<?php echo $category_link?>" title="<?php echo $category->title?>"><?php echo $category->title?></a></h3>
	<div><?php echo $item->desc?></div>
</div>
<div class="clearer"></div>

