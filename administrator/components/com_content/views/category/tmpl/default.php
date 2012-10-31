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
			jQuery('form:first').submit();
		});
});
</script>
<form action="index.php?option=com_content&view=category" method="post" name="adminForm">
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
                <th width="250"><?php echo JHTML::_('grid.sort', 'Раздел', 'c.id_section', @$lists['order_Dir'], @$lists['order'] ); ?></th>
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
        <tr>
        	<td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?php echo $this->sectionList;?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
			<?php 
				if($this->items):
					foreach($this->items as $item):
							$i++;
							$link_edit = JRoute::_('index.php?option=com_content&view=category&layout=form&task=edit&id='.$item->id_category);
							if( $item->published == 0 ):
								$link_pub = JRoute::_('index.php?option=com_content&view=category&controller=category&task=publish&cid[]='.$item->id_category);
								$link_pub = "<a href=\"{$link_pub}\" alt=\"Опубликовать\"><img src='images/publish_x.png' /></a>";						
							else:
								$link_pub = JRoute::_('index.php?option=com_content&view=category&controller=category&task=unpublish&cid[]='.$item->id_category);
								$link_pub = "<a href=\"{$link_pub}\" alt=\"Не публиковать\"><img src='images/tick.png' /></a>";						
							endif;
							$childList = $model->getChild($item->id_category);
						?>
						<tr class="row<?php echo ($i%2) ? 0 : 1;?>">
							<td><?php echo $item->id_category; ?></td>
							<td><input type="checkbox" name="cid[]" id="cb<?php echo $item->id_category; ?>" onclick="isChecked(this.checked);" value="<?php echo $item->id_category; ?>" /></td>
							<td><a href="<?php echo $link_edit; ?>"><?php echo $item->title?></a></td>
							<td align="center"><?php echo $item->alias?></td>
							<td align="center"><?php echo $model->getSectionName($item->id_section);?></td>
							<td align="center"><input type="text" value="<?php echo $item->ordering?>" style="text-align:center; width:30px" name="item[<?php echo $item->id_category?>]" /></td>
							<td align="center"><?php echo $link_pub; ?></td>
						</tr>
			<?php 
					if( $childList ):
						foreach( $childList as $child ):
							$i++;
							$link_edit_child = JRoute::_('index.php?option=com_content&view=category&layout=form&task=edit&id='.$child->id_category);
							if( $child->published == 0 ):
								$link_pub_child = JRoute::_('index.php?option=com_content&view=category&controller=category&task=publish&cid[]='.$child->id_category);
								$link_pub_child = "<a href=\"{$link_pub_child}\" alt=\"Опубликовать\"><img src='images/publish_x.png' /></a>";						
							else:
								$link_pub_child = JRoute::_('index.php?option=com_content&view=category&controller=category&task=unpublish&cid[]='.$child->id_category);
								$link_pub_child = "<a href=\"{$link_pub_child}\" alt=\"Не публиковать\"><img src='images/tick.png' /></a>";						
							endif;
			?>
                            <tr class="row<?php echo ($i%2) ? 0 : 1;?>">
                                <td><?php echo $child->id_category; ?></td>
                                <td><input type="checkbox" name="cid[]" id="cb<?php echo $child->id_category; ?>" onclick="isChecked(this.checked);" value="<?php echo $child->id_category; ?>" /></td>
                                <td><a href="<?php echo $link_edit_child; ?>">.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<sup>|_</sup>&nbsp;<?php echo $child->title?></a></td>
                                <td align="center"><?php echo $child->alias?></td>
                                <td align="center"><?php echo $model->getSectionName($child->id_section);?></td>
                                <td align="center"><input type="text" value="<?php echo $child->ordering?>" style="text-align:center; width:30px" name="item[<?php echo $child->id_category?>]" /></td>
                                <td align="center"><?php echo $link_pub_child; ?></td>
                            </tr>            
						
			<?php		endforeach;
					endif;
					endforeach;
				endif;
			?>
			<tr>
				<td colspan="7" align="center" style="background-color:#F0F0F0"><?php echo $page->getListFooter(); ?></td>
			</tr>
		</tbody>		
	</table>
</div>
<input type="hidden" name="option" value="com_content" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="category" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
<input type="hidden" id="filter_id_section" name="filter_id_section" value="<?php echo $lists['id_section']; ?>" />
</form>
