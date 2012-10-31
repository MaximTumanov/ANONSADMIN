<?php
	$model      = $this->getModel();
	$page       = $this->page;   
	$lists      = $this->lists;
	$i=0;
?>
<script type="text/javascript">
jQuery('document').ready(function(){
		jQuery('#id_section').change(function(){
			jQuery('#filter_id_section').val(jQuery(this).val());
			jQuery('#filter_id_category').val('');
			jQuery('form:first').submit();
		});
		jQuery('#id_category').change(function(){
			jQuery('#filter_id_category').val(jQuery(this).val());
			jQuery('#filter_id_section').val('');
			jQuery('form:first').submit();
		});
});
</script>
<form action="index.php?option=com_content&view=item" method="post" name="adminForm">
<div id="editcell">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%" class="title">			
					<?php echo JText::_('ID')?>
				</th>
				<th width="2%">&nbsp;</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'Название', 'i.title', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
                <th width="250"><?php echo JText::_('Псевдоним')?></th>
                <th width="250"><?php echo JHTML::_('grid.sort', 'Раздел', 'x.id_section', @$lists['order_Dir'], @$lists['order'] ); ?></th>
                <th width="250"><?php echo JHTML::_('grid.sort', 'Категория', 'x.id_category', @$lists['order_Dir'], @$lists['order'] ); ?></th>
                <th width="80">
					<?php echo JHTML::_('grid.sort', 'Порядок', 'i.ordering', @$lists['order_Dir'], @$lists['order'] ); ?>
                    <a title="Сохранить порядок" href="#" onclick="javascript:hideMainMenu(); submitbutton('saveorder')"><img alt="Сохранить порядок" src="images/filesave.png"></a>
                </th> 
				<th width="100"><?php echo JHTML::_('grid.sort', 'На главной', 'i.on_front', @$lists['order_Dir'], @$lists['order'] ); ?></th>        
				<th width="100"><?php echo JHTML::_('grid.sort', 'Опубликовано', 'i.published', @$lists['order_Dir'], @$lists['order'] ); ?></th>
                <th width="150"><?php echo JHTML::_('grid.sort', 'Дата', 'i.date', @$lists['order_Dir'], @$lists['order'] ); ?></th>
                <th width="150"><?php echo JText::_('Автор')?></th>
			</tr>
		</thead>
		<tbody>
        <tr>
        	<td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?php echo $this->sectionList;?></td>
            <td><?php echo $this->categoryList;?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
			<?php 
				if($this->items):
					foreach($this->items as $item):
							$i++;
							$link_edit = JRoute::_('index.php?option=com_content&view=item&layout=form&task=edit&id='.$item->id_item);
							
							if( $item->published == 0 ):
								$link_pub = JRoute::_('index.php?option=com_content&view=item&controller=item&task=publish&cid[]='.$item->id_item);
								$link_pub = "<a href=\"{$link_pub}\" alt=\"Опубликовать\"><img src='images/publish_x.png' /></a>";						
							else:
								$link_pub = JRoute::_('index.php?option=com_content&view=item&controller=item&task=unpublish&cid[]='.$item->id_item);
								$link_pub = "<a href=\"{$link_pub}\" alt=\"Не публиковать\"><img src='images/tick.png' /></a>";						
							endif;

							if( $item->on_front == 0 ):
								$link_front = JRoute::_('index.php?option=com_content&view=item&controller=item&task=onfront&id='.$item->id_item);
								$link_front = "<a href=\"{$link_front}\" alt=\"Опубликовать\"><img src='images/publish_x.png' /></a>";						
							else:
								$link_front = JRoute::_('index.php?option=com_content&view=item&controller=item&task=unonfront&id='.$item->id_item);
								$link_front = "<a href=\"{$link_front}\" alt=\"Не публиковать\"><img src='images/tick.png' /></a>";						
							endif;
							
							$xrefData          = $model->getXrefData($item->id_item);
							$item->id_section  = $xrefData->id_section;
							$item->id_category = $xrefData->id_category;
							$user              = $model->getUserName($item->id_user, $item->isAdmin);
							
						?>
						<tr class="row<?php echo ($i%2) ? 0 : 1;?>">
							<td><?php echo $item->id_item; ?></td>
							<td><input type="checkbox" name="cid[]" id="cb<?php echo $item->id_item; ?>" onclick="isChecked(this.checked);" value="<?php echo $item->id_item; ?>" /></td>
							<td><a href="<?php echo $link_edit; ?>"><?php echo $item->title?></a></td>
							<td align="center"><?php echo $item->alias?></td>
							<td align="center"><?php echo $model->getSectionName($item->id_section);?></td>
                            <td align="center"><?php echo $model->getCategoryName($item->id_category);?></td>
							<td align="center"><input type="text" value="<?php echo $item->ordering?>" style="text-align:center; width:30px" name="item[<?php echo $item->id_item?>]" /></td>
							<td align="center"><?php echo $link_front; ?></td>
                            <td align="center"><?php echo $link_pub; ?></td>
                            <td align="center"><?php echo $item->date?></td>
                            <td align="center"><?php echo $user->title;?></td>
						</tr>
             <?php
			 		endforeach;
             	endif;
			 ?>
			<tr>
				<td colspan="11" align="center" style="background-color:#F0F0F0"><?php echo $page->getListFooter(); ?></td>
			</tr>
		</tbody>		
	</table>
</div>
<input type="hidden" name="option" value="com_content" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="item" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
<input type="hidden" id="filter_id_section" name="filter_id_section" value="<?php echo $lists['id_section']; ?>" />
<input type="hidden" id="filter_id_category" name="filter_id_category" value="<?php echo $lists['id_category']; ?>" />
</form>
