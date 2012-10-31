<?php
	$model      = $this->getModel();
	$page       = $this->page;   
	$lists      = $this->lists;
	$component  = $this->component;
	$view       = $this->view; 
	$i=0;
	JHTML::_('behavior.calendar');
?>
<script type="text/javascript">
jQuery(function(){
	jQuery('#reset').click(function(){
		jQuery('#filter_date_from').val('<?php echo date('Y-m-d')?>');
		jQuery('#filter_date_to').val('');
		jQuery('#filter_id_category').val(0);
		jQuery('form:first').submit();
	});

	jQuery('#accept').click(function(){
		jQuery('#filter_date_from').val(jQuery('#date_from').val());
		jQuery('#filter_date_to').val(jQuery('#date_to').val());
		jQuery('#filter_id_category').val(jQuery('#id_category').val());
		jQuery('form:first').submit();		
	});
})
</script>
<div style="margin-bottom: 50px; float:right; position:relative; top: -20px;">
	<span>Дата: </span>
	
	<span>с <?php echo JHTML::_('calendar', $lists['date_from'], 'date_from', 'date_from', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); ?></span>
	<span> по <?php echo JHTML::_('calendar', $lists['date_to'], 'date_to', 'date_to', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); ?></span>
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
				<th width="title"><?php echo JText::_('Фильм')?></th>
				<th width="250"><?php echo JText::_('кинотеатр')?></th>
				<th width="50"><?php echo JHTML::_('grid.sort', 'Премьера', 'c.premiere', @$lists['order_Dir'], @$lists['order'] ); ?></th>
				<th width="100"><?php echo JHTML::_('grid.sort', 'Публикация', 'c.published', @$lists['order_Dir'], @$lists['order'] ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->items as $item):?>
					<?php 
						$i++;
						$id = $item->id_films;
						$link_edit = JRoute::_("index.php?option={$component}&view={$view}&layout=form&task=edit&id={$id}");

						
						
						
						if( $item->published == 0 ):
							$link_pub = JRoute::_("index.php?option={$component}&view={$view}&controller={$view}&task=publish&cid[]={$id}");
							$link_pub = "<a href=\"{$link_pub}\" alt=\"Опубликовать\"><img src='images/publish_x.png' /></a>";						
						else:
							$link_pub = JRoute::_("index.php?option={$component}&view={$view}&controller={$view}&task=unpublish&cid[]={$id}");
							$link_pub = "<a href=\"{$link_pub}\" alt=\"Не публиковать\"><img src='images/tick.png' /></a>";						
						endif;
						
						if( $item->premiere == 0 ):
							$link_premiere = JRoute::_("index.php?option={$component}&view={$view}&controller={$view}&task=premiere&cid[]={$id}");
							$link_premiere = "<a href=\"{$link_premiere}\" alt=\"Премьера\"><img src='images/publish_x.png' /></a>";						
						else:
							$link_premiere = JRoute::_("index.php?option={$component}&view={$view}&controller={$view}&task=unpremiere&cid[]={$id}");
							$link_premiere = "<a href=\"{$link_premiere}\" alt=\"Не премьера\"><img src='images/tick.png' /></a>";						
						endif;
						
						$more    = $model->getMoreInfo($id);
						
						$cinema   = $model->getCinema($more->idCinema);
						if ($cinema) {
							$aCinema = "<a href='index.php?option=com_films&view=cinema&layout=form&task=edit&id={$cinema->id_cinema}'>{$cinema->title}</a>";
						}
					?>
					<tr class="row<?php echo ($i%2) ? 0 : 1;?>">
						<td><?php echo $id; ?></td>
						<td><input type="checkbox" name="cid[]" id="cb<?php echo $id; ?>" onclick="isChecked(this.checked);" value="<?php echo $id;; ?>" /></td>
                        <td>
                        	<a href="<?php echo $link_edit;?>"><?php echo $item->title;?></a> 
                        	
                        </td>
                        <td align="center"><?php echo $aCinema; ?></td>
                        <td align="center"><?php echo $link_premiere; ?></td>
						<td align="center"><?php echo $link_pub; ?></td>
					</tr>
			<?php endforeach;?>
			<tr>
				<td colspan="7" align="center" style="background-color:#F0F0F0"><?php echo $page->getListFooter(); ?></td>
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
<input type="hidden" id="filter_id_category" name="filter_id_category" value="<?php echo $lists['id_category']; ?>" />
<input type="hidden" id="filter_date_from" name="filter_date_from" value="<?php echo $lists['date_from']; ?>" />
<input type="hidden" id="filter_date_to" name="filter_date_to" value="<?php echo $lists['date_to']; ?>" />
</form>
