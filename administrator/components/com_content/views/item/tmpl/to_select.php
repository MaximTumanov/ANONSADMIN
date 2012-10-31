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
<form action="index.php?option=com_content&view=item&layout=to_select&tmpl=component&object=id" method="post" name="adminForm">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%" class="title">			
					<?php echo JText::_('ID')?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'Название', 'i.title', @$lists['order_Dir'], @$lists['order'] ); ?>
				</th>
                <th width="250"><?php echo JHTML::_('grid.sort', 'Раздел', 'x.id_section', @$lists['order_Dir'], @$lists['order'] ); ?></th>
                <th width="250"><?php echo JHTML::_('grid.sort', 'Категория', 'x.id_category', @$lists['order_Dir'], @$lists['order'] ); ?></th>   
				<th width="100"><?php echo JHTML::_('grid.sort', 'Published', 'i.published', @$lists['order_Dir'], @$lists['order'] ); ?></th>
                <th width="100"><?php echo JText::_('Дата')?></th>
			</tr>
		</thead>
		<tbody>
        <tr>
        	<td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?php echo $this->sectionList;?></td>
            <td><?php echo $this->categoryList;?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
			<?php 
				if($this->items):
					foreach($this->items as $item):
							$i++;
							
							if( $item->published == 0 ):
								$link_pub = "<img src='images/publish_x.png' />";						
							else:
								$link_pub = "<img src='images/tick.png' />";						
							endif;

							if( $item->on_front == 0 ):
								$link_front = "<img src='images/publish_x.png' />";						
							else:
								$link_front = "<img src='images/tick.png' />";						
							endif;
							
							$xrefData          = $model->getXrefData($item->id_item);
							$item->id_section  = $xrefData->id_section;
							$item->id_category = $xrefData->id_category;
							
						?>
						<tr class="row<?php echo ($i%2) ? 0 : 1;?>">
							<td><?php echo $item->id_item; ?></td>
							<td>
                            	<a style="cursor: pointer;" onclick="window.parent.jSelectArticle('<?php echo $item->id_item; ?>', '<?php echo str_replace(array("'", "\""), array("\\'", ""), $item->title); ?>', '<?php echo JRequest::getVar('object'); ?>');">
									<?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>
                            	</a>
                            </td>
							<td align="center"><?php echo $model->getSectionName($item->id_section);?></td>
                            <td align="center"><?php echo $model->getCategoryName($item->id_category);?></td>
                            <td align="center"><?php echo $link_pub; ?></td>
                            <td align="center"><?php echo $item->date?></td>
						</tr>
             <?php
			 		endforeach;
             	endif;
			 ?>
			<tr>
				<td colspan="6" align="center" style="background-color:#F0F0F0"><?php echo $page->getListFooter(); ?></td>
			</tr>
		</tbody>		
	</table>
<input type="hidden" name="option" value="com_content" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="item" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
<input type="hidden" id="filter_id_section" name="filter_id_section" value="<?php echo $lists['id_section']; ?>" />
<input type="hidden" id="filter_id_category" name="filter_id_category" value="<?php echo $lists['id_category']; ?>" />
</form>
