<?php
	$model = $this->getModel('Services');
	$itemList = $model->getServices(3, 6);
?>
<div class="company_text">
	<h1>Услуги компании</h1>
	<p class="margin_top_25px">&nbsp;</p>
	<?php
		$i = 1;
		foreach( $itemList as $item ):
			$link = JRoute::_("index.php?option=com_content&view=services&layout=item&id={$item->id_item}");
			$class = 'margin_r10';
			if( $i == 3 ){ $i = 0; $class = ''; }
	?>
				<div class="groupp_items <?php echo $class?>">
					<a href="<?php echo $link?>">
					<div class="yellow_shade">
						<table>
							<tr>
								<td>
									<?php if( $item->image ) echo "<img src='{$this->baseurl}/images/sunny/content/{$item->image}' class='group_foto' alt='{$item->title}' title='{$item->title}' />"; ?>
								</td>
							</tr>
						</table>
					</div>
					</a>
					<table>
						<tr>
							<td><?php echo $item->title?></td>
						</tr>
					</table>
					<div class="groupp_items_text"><?php echo $item->s_desc?></div>
					<a class="button left" href="<?php echo $link?>"></a>
				</div>	
	<?php 
		$i++; 
		endforeach;
	?>
</div>