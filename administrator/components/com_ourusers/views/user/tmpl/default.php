<?php
	$model      = $this->getModel();
	$page       = $this->page;   
	$lists      = $this->lists;
	$component  = $this->component;
	$view       = $this->view; 
	$i=0;
	JHTML::_('behavior.calendar');
?>
<form action="index.php?option=<?php echo $component?>&view=<?php echo $view?>" method="post" name="adminForm">
<div id="editcell">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%" class="title"><?php echo JText::_('ID')?></th>
				<th width="2%">&nbsp;</th>
				<th width="title"><?php echo JText::_('ФИО')?></th>
				<th width="100"><?php echo JText::_('Логин')?></th>
				<th width="100"><?php echo JText::_('Сеть')?></th>
				<th width="150"><?php echo JText::_('Телефон')?></th>
				<th width="150"><?php echo JText::_('Email')?></th>
				<th width="250"><?php echo JText::_('Организатор')?></th>
				<th width="120"><?php echo JHTML::_('grid.sort', 'Может публиковать', 'c.public', @$lists['order_Dir'], @$lists['order'] ); ?></th>
				<th width="100"><?php echo JHTML::_('grid.sort', 'Без модерации', 'c.vip', @$lists['order_Dir'], @$lists['order'] ); ?></th>
				<th width="60"><?php echo JText::_('Отказано'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->items as $item):?>
					<?php 
						$i++;
						$id = $item->id;
						$link_edit = JRoute::_("index.php?option={$component}&view={$view}&layout=form&task=edit&id={$id}");

						$place = $model->getPlace($item->id_place);

						if( $item->public == 0 || $item->public == 2  ):
							$link_pub = JRoute::_("index.php?option={$component}&view={$view}&controller={$view}&task=publish&cid[]={$id}");
							$link_pub = "<a href=\"{$link_pub}\" alt=\"Опубликовать\"><img src='images/publish_x.png' /></a>";						
						elseif($item->public == 1):
							$link_pub = JRoute::_("index.php?option={$component}&view={$view}&controller={$view}&task=unpublish&cid[]={$id}");
							$link_pub = "<a href=\"{$link_pub}\" alt=\"Не публиковать\"><img src='images/tick.png' /></a>";						
						endif;
						
						if( $item->vip == 0 ):
							$link_vip = JRoute::_("index.php?option={$component}&view={$view}&controller={$view}&task=vip&cid[]={$id}");
							$link_vip = "<a href=\"{$link_vip}\" alt=\"VIP\"><img src='images/publish_x.png' /></a>";						
						else:
							$link_vip = JRoute::_("index.php?option={$component}&view={$view}&controller={$view}&task=unvip&cid[]={$id}");
							$link_vip = "<a href=\"{$link_vip}\" alt=\"Не VIP\"><img src='images/tick.png' /></a>";						
						endif;

						$link_denied = ($item->public == 2) ? "<img src='images/tick.png' />" : '';

					?>
					<tr class="row<?php echo ($i%2) ? 0 : 1;?>">
						<td><?php echo $id; ?></td>
						<td><input type="checkbox" name="cid[]" id="cb<?php echo $id; ?>" onclick="isChecked(this.checked);" value="<?php echo $id;; ?>" /></td>
            <td><a href="<?php echo $link_edit;?>"><?php echo $item->fio;?></a></td>
            <td><?php echo $item->login?></td>
            <td><?php echo $item->network?></td>
            <td><?php echo $item->phone?></td>
            <td><a href="mailto:<?php echo $item->email?>"><?php echo $item->email?></a></td>
            <td><?php echo $place->title?></td>
            <td align="center"><?php echo $link_vip; ?></td>
						<td align="center"><?php echo $link_pub; ?></td>
            <td align="center"><?php echo $link_denied?></td>
					</tr>
			<?php endforeach;?>
			<tr>
				<td colspan="11" align="center" style="background-color:#F0F0F0"><?php echo $page->getListFooter(); ?></td>
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
