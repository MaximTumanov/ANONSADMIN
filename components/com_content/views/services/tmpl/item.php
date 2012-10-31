<?php
	$id    = JRequest::getInt('id');
	$model = $this->getModel('Services');
	$item  = $model->getServicesItem($id);
	// устанавливаем мета-данные
	$doc = &JFactory::getDocument();
	$doc->setTitle($item->title);
	$doc->setMetaData('keywords', $item->keywords);

?>
<div class="company_text">
	<div class="yellow_shade right logo_ot">
		<table class="service_image">
			<tr>
				<td>
					<?php if($item->image) echo "<img src='{$this->baseurl}/images/sunny/content/{$item->image}'  class='group_foto' alt='{$item->title}' title='{$item->title}' />"; ?>
				</td>
			</tr>
		</table>
	</div>
	<h1><?php echo $item->title;?></h1>
	<div class="margin_top_25px"><?php echo $item->desc;?></div>
</div>