<?php
	$model      = $this->getModel();
	$page       = $this->page;   
	$lists      = $this->lists;
	$component  = $this->component;
	$view       = $this->view; 
	$i=0;
?>
<style>
	.show a{
		display: none;
	}

	.show{
		height: 10px;
	}

	td:hover .show a{
		display: block;
	}
</style>
<script type="text/javascript">
jQuery(function(){
	jQuery('#reset').click(function(){
		jQuery('#filter_title').val('');
		jQuery('form:first').submit();
	});

	jQuery('#accept').click(function(){
		jQuery('#filter_title').val(jQuery('#filter_title_input').val());
		jQuery('form:first').submit();		
	});
})
</script>
<div style="margin-bottom: 10px; float:right">
	<span>Фильтры: </span>
	<span><input type="text" id="filter_title_input" value="<?php echo $lists['filter_title']?>" /></span>
	<span><input type="button" style="cursor: pointer" id="accept" value="Применить" /></span>
	<span><input type="button" style="cursor: pointer" id="reset" value="Сбросить" /></span>
</div>

<form action="index.php?option=<?php echo $component?>&view=<?php echo $view?>" method="post" name="adminForm">
<div id="editcell">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%" class="title"><?php echo JText::_('ID')?></th>
				<th width="2%">&nbsp;</th>
				<th><?php echo JHTML::_('grid.sort', 'Название', 'c.title', @$lists['order_Dir'], @$lists['order'] ); ?></th>
				<th width="250"><?php echo JText::_('Адрес')?></th>
				<th width="100"><?php echo JText::_('Телефон')?></th>
				<th width="200"><?php echo JText::_('Ссылка')?></th>
				<th width="30"><?php echo JHTML::_('grid.sort', 'Изображение', 'c.image', @$lists['order_Dir'], @$lists['order'] ); ?></th>
				<th width="100"><?php echo JHTML::_('grid.sort', 'Опубликовано', 'c.published', @$lists['order_Dir'], @$lists['order'] ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if($this->items): foreach($this->items as $item):?>
					<?php 
						$i++;
						$id = $item->id_place;
						$link_edit = JRoute::_("index.php?option={$component}&view={$view}&layout=form&task=edit&id={$id}");
						$link_to_site = "index.php?option=com_places&layout=item&id={$id}&Itemid=35";
						
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
                        <td>
                        	<a href="<?php echo $link_edit;?>"><?php echo $item->title;?></a>
                        	<p style="margin:0; padding:0"><span style="color: #666"><?php echo $item->dop_title?></span></p>
                        	<p class='show' style="margin:0; padding:0"><a title="Посмотреть '<?php echo $item->title;?>' на сайте" target="blank" style="color: #666" href="http://design.anons.dp.ua/places/show/<?php echo $item->alias?>">посмотреть на сайте →</a></p>
                        </td>
                        <td align="center"><?php echo $item->address;?></td>
                        <td align="center"><?php echo $item->tel;?></td>
                        <td align="center"><?php echo $item->web;?></td>
                        <td align="center"><?php echo ($item->image) ? '+' : '-';?></td>
						<td align="center"><?php echo $link_pub; ?></td>
					</tr>
			<?php endforeach; endif;?>
			<tr>
				<td colspan="8" align="center" style="background-color:#F0F0F0"><?php echo $page->getListFooter(); ?></td>
			</tr>
		</tbody>		
	</table>
</div>
<input type="hidden" name="option" value="<?php echo $component?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="<?php echo $view?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
<input type="hidden" name="filter_title" id="filter_title" value="<?php echo $lists['filter_title']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
</form>
