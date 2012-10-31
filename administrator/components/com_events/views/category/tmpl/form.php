<?php 
$id = JRequest::getVar('id');
$model = $this->getModel();
$editor   =& JFactory::getEditor();

if($id):
	$item      = $model->getItem($id);
	
	$title     = $item->title;
	$alias     = $item->alias;
	$icon      = $item->icon;
	$published = $item->published;
else:
	$title     = '';
	$alias     = '';
	$logo      = '';
	$published = 1;
endif;

$dir      = '../images'.DS.'sunny'.DS.'events'.DS.'category';
$dir_echo = 'images'.DS.'sunny'.DS.'events'.DS.'category';

?>
<script type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if(!form.title.value) {
		alert('Укажите название!'); 
		form.title.focus(); 
		return false; 
	}
	else {
		submitform(pressbutton);
	}
}
</script>
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
		  <fieldset class="adminform">
		  <legend><?php echo JText::_('Основные параметры');?></legend>
          <?php if( !is_writable($dir) ):?>
              <div style="padding:5px 0 10px 0">
                <?php echo JText::sprintf(PARAMSUNWRITABLE, $dir_echo);?>
              </div>
          <?php endif;?>
		  <table class="admintable">
			<tbody>
            
			  <tr>
				<td class="key"><label for="title"><?php echo JText::_('Название');?></label>
				</td>
				<td><input type="text" value="<?php echo $title?>" maxlength="255" size="60"  name="title" id="title" class="inputbox"> 
				</td>
			  </tr>

			  <tr>
				<td class="key"><label for="alias"><?php echo JText::_('Псевдоним');?></label>
				</td>
				<td><input type="text" value="<?php echo $alias?>" maxlength="255" size="60"  name="alias" id="alias" class="inputbox">
				</td>
			  </tr>
	  
			  <tr>
				<td class="key"><label for="published"><?php echo JText::_('Опубликовано');?></label>
				</td>
				<td>
				<select name="published" id="published">
					<option value="1" <?php if( $published == 1 ) echo 'selected';?> ><?php echo JText::_('Да');?></option>
					<option value="0"  <?php if( $published == 0 ) echo 'selected';?>  ><?php echo JText::_('Нет');?></option>
				</select>		
				</td>
			  </tr> 
              
              <tr>
              	<td class="key"><label for="icon"><?php echo JText::_('Изображение');?></label></td>
                <td><input type="file" name="icon" id="icon" /></td>
              </tr>
              
                <?php if( $icon ):?>
                <tr>
                	<td class="key"><label><?php echo JText::_('Текущее изображение');?></label></td>
                    <td>
                        <img src="../images/sunny/events/category/<?php echo $icon?>" />
                        <input type="hidden" name="iconHid" value="<?php echo $icon?>" />
                    </td>
                </tr>
                <?php endif;?>
       
			</tbody>
		  </table>
	  </fieldset>

<input type="hidden" name="option" value="<?php echo $this->component?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="<?php echo $this->view?>" />
</form>