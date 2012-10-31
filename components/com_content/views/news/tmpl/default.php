<?php
	$model       = $this->getModel('News');
	// получаем кол-во новостей на страницу
	$limit      = $this->params->get('limit');
	
	$page       = JRequest::getInt('page', 1);
	$limitstart = $limit * ($page - 1);
	
	$itemList    = $model->getItemList($limitstart, $limit);
	$all         = $model->getItemCount();
	$option      = 'option=com_content&view=news&layout=default';
	
	
	$pagination  = JHTML::_('pagination', $page, $all, $limit, $option);
	
	$imageParam  = NEWS_IMAGE_PARAM;
?>
<div class="content_right">
	<h1><?php echo JText::_('NEWS')?></h1>
	<?php
	if( $itemList ):
		foreach( $itemList as $key => $item ):
			$category = $model->getItemCatData($item->id_item);
			$link     = JRoute::_("index.php?option=com_content&view=news&layout=item&catid={$category->id_category}&id={$item->id_item}");
			$date     = JHTML::_('date', $item->date);
			$image    = ($item->image) 
							? "{$this->baseurl}/images/sunny/content/{$item->image}{$imageParam}" 
							: "{$this->baseurl}/templates/template/images/" . NO_IMAGE_NEWS;		
	?>
		<div class="twoo_cols_item">
			<a href="<?php echo $link?>"><img title="<?php echo $item->title?>" src="<?php echo $image?>" alt="<?php echo $item->title?>" class="cols_left" /></a>
			<div class="cols_right">
				<h2><a href="<?php echo $link?>" title="<?php echo $item->title?>"><?php echo $item->title?></a></h2>
				<h3><?php echo $date?></h3>
				<div><?php echo $item->s_desc?></div>
				<a href="<?php echo $link?>" class="all_grey"><?php echo JText::_('READ_MORE')?></a>
			</div>
		<div class="clearer"></div>
		</div>
		
	<?php 
			if ($key != (count($itemList) - 1)):
				echo '<div class="dashed"></div>';
			endif;
		endforeach;
		echo $pagination;
	endif;
	 ?>
</div>
<div class="clearer"></div>