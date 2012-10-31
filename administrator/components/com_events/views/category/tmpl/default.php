<?php
	$model      = $this->getModel();
	$page       = $this->page;   
	$lists      = $this->lists;
	$component  = $this->component;
	$view       = $this->view; 
	$i=0;
?>

<form action="index.php?option=<?php echo $component?>&view=<?php echo $view?>" method="post" name="adminForm">
<div id="editcell">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%" class="title"><?php echo JText::_('ID')?></th>
				<th width="2%">&nbsp;</th>
				<th><?php echo JHTML::_('grid.sort', 'Название', 'c.title', @$lists['order_Dir'], @$lists['order'] ); ?></th>
				<th width="300"><?php echo JText::_('Иконка')?></th>
				<th width="100"><?php echo JHTML::_('grid.sort', 'Опубликовано', 'c.published', @$lists['order_Dir'], @$lists['order'] ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->items as $item):?>
					<?php 
						$i++;
						$id = $item->id_category;
						$link_edit = JRoute::_("index.php?option={$component}&view={$view}&layout=form&task=edit&id={$id}");
						
						if( $item->published == 0 ):
							$link_pub = JRoute::_("index.php?option={$component}&view={$view}&controller={$view}&task=publish&cid[]={$id}");
							$link_pub = "<a href=\"{$link_pub}\" alt=\"Опубликовать\"><img src='images/publish_x.png' /></a>";						
						else:
							$link_pub = JRoute::_("index.php?option={$component}&view={$view}&controller={$view}&task=unpublish&cid[]={$id}");
							$link_pub = "<a href=\"{$link_pub}\" alt=\"Не публиковать\"><img src='images/tick.png' /></a>";						
						endif;
					?>
					<tr class="row<?php echo ($i%2) ? 0 : 1;?>">
						<td><?php echo $id; ?></td>
						<td><input type="checkbox" name="cid[]" id="cb<?php echo $id; ?>" onclick="isChecked(this.checked);" value="<?php echo $id; ?>" /></td>
                        <td><a href="<?php echo $link_edit;?>"><?php echo $item->title;?></a></td>
                        <td align="center"><img src="../images/sunny/events/category/<?php echo $item->icon?>" /></td>
						<td align="center"><?php echo $link_pub; ?></td>
					</tr>
			<?php endforeach;?>
			<tr>
				<td colspan="5" align="center" style="background-color:#F0F0F0"><?php echo $page->getListFooter(); ?></td>
			</tr>
		</tbody>		
	</table>
</div>
<input type="hidden" name="option" value="<?php echo $component?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="<?php echo $view?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
</form>
