<?php 
	$db = &JFactory::getDBO();
	$db->setQuery(" SELECT * FROM `#__content` WHERE `alias` = '404' ");
	$item = $db->loadObject();
?>
<div class="company_text">
	<div class="content_right" style="width: 100%">
		<h1><?php echo $item->title?></h1>
		<div><?php echo $item->desc?></div>
	</div>
	<div class="clearer"></div>
</div>