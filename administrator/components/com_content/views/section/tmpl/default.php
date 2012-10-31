<?php
	$model      = $this->getModel();
	$page       = $this->page;   
	$lists      = $this->lists;
	$i=0;
?>

<form action="index.php?option=com_content&view=section" method="post" name="adminForm">
<div id="editcell">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%" class="title">			
					<?php echo JText::_('ID')?>
				</th>
				<th width="2%">&nbsp;</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'Название', 'c.title', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
                <th width="250"><?php echo JText::_('Псевдоним')?></th>
                <th width="80">
					<?php echo JHTML::_('grid.sort', 'Порядок', 'c.ordering', @$lists['order_Dir'], @$lists['order'] ); ?>
                    <a title="Сохранить порядок" href="#" onclick="javascript:hideMainMenu(); submitbutton('saveorder')"><img alt="Сохранить порядок" src="images/filesave.png"></a>
                </th>          
				<th width="100">
					<?php echo JHTML::_('grid.sort', 'Published', 'c.published', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->items as $item):?>
					<?php 
						$i++;
						$link_edit = JRoute::_('index.php?option=com_content&view=section&layout=form&task=edit&id='.$item->id_section);
						if( $item->published == 0 ):
							$link_pub = JRoute::_('index.php?option=com_content&view=section&controller=section&task=publish&cid[]='.$item->id_section);
							$link_pub = "<a href=\"{$link_pub}\" alt=\"Опубликовать\"><img src='images/publish_x.png' /></a>";						
						else:
							$link_pub = JRoute::_('index.php?option=com_content&view=section&controller=section&task=unpublish&cid[]='.$item->id_section);
							$link_pub = "<a href=\"{$link_pub}\" alt=\"Не публиковать\"><img src='images/tick.png' /></a>";						
						endif;
					?>
					<tr class="row<?php echo ($i%2) ? 0 : 1;?>">
						<td><?php echo $item->id_section; ?></td>
						<td><input type="checkbox" name="cid[]" id="cb<?php echo $item->id_section; ?>" onclick="isChecked(this.checked);" value="<?php echo $item->id_section; ?>" /></td>
						<td><a href="<?php echo $link_edit; ?>"><?php echo $item->title?></a></td>
                        <td align="center"><?php echo $item->alias?></td>
                        <td align="center"><input type="text" value="<?php echo $item->ordering?>" style="text-align:center; width:30px" name="item[<?php echo $item->id_section?>]" /></td>
						<td align="center"><?php echo $link_pub; ?></td>
					</tr>
			<?php endforeach;?>
			<tr>
				<td colspan="6" align="center" style="background-color:#F0F0F0"><?php echo $page->getListFooter(); ?></td>
			</tr>
		</tbody>		
	</table>
</div>
<input type="hidden" name="option" value="com_content" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="section" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
</form>
