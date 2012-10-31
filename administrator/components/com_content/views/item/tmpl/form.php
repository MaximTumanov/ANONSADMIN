<?php 
$id       = JRequest::getVar('id');
$model    = $this->getModel();
$editor   =& JFactory::getEditor();

$user     =& JFactory::getUser();

if($id):
	$item      = $model->getItem($id);
	
	$title      = $item->title;
	$alias      = $item->alias;
	$published  = $item->published;
	$s_desc     = $item->s_desc;
	$desc       = $item->desc;
	$keywords   = $item->keywords;
	$date       = $item->date;
	$image      = $item->image;

	$xrefData    = $model->getXrefData($item->id_item);
	$id_section  = $xrefData->id_section;
	$id_category = $xrefData->id_category;
	
	$id_user     = ($item->id_user) ? $item->id_user : $user->id;
	$isAdmin     = $item->isAdmin;
	
	$name        = $model->getUserName($id_user, $item->isAdmin);
else:
	$title       = '';
	$alias       = '';
	$published   = 1;
	$id_section  = 0;
	$id_category = 0;
	$s_desc      = '';
	$desc        = '';
	$keywords    = '';
	$date        = date("Y-m-d H:i:s"); 
	$image       = '';
	$id_user     = $user->id;
	$isAdmin     = 1;
endif;

// получаем список категорий
$catList     = ContentHelper::selectList($model->getCategoryList(),'id_category','Выбирите категорию', $id_category);
// получаем список разделов
$sectionList = ContentHelper::selectList($model->getSectionList(),'id_section','Выбирите раздел', $id_section);

$dir      = '../images'.DS.'sunny'.DS.'content';
$dir_echo = 'images'.DS.'sunny'.DS.'content';

JHTML::_('behavior.calendar');
?>


<script type="text/javascript">
function submitbutton(pressbutton) {
	var form = document.adminForm;
	if( form.title.value == "" )
	{
		alert('Укажите название!'); 
		form.title.focus(); 
		return false; 
	}
	else if( form.id_section.value == 0 )
	{
		alert('Укажите раздел!'); 
		form.id_section.focus(); 
		return false; 
	}
	else if( form.id_category.value == 0 )
	{
		alert('Укажите категорию!'); 
		form.id_category.focus(); 
		return false; 
	}
	else
	{
		submitform(pressbutton);
	}
}
</script>
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
		  <fieldset class="adminform">
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
				<td class="key"><label for="id_section"><?php echo JText::_('Раздел');?></label>
				</td>
				<td><?php echo $sectionList; ?>
				</td>
			  </tr>

			  <tr>
				<td class="key"><label for="id_category"><?php echo JText::_('Категория');?></label>
				</td>
				<td><?php echo $catList; ?>
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
				<td class="key"><label for="keywords"><?php echo JText::_('Ключевые слова');?></label>
				</td>
				<td><textarea name="keywords" id="keywords" style="height:50px; width:255px"><?php echo $keywords;?></textarea>
				</td>
			  </tr>

             
              <tr>
              	<td class="key"><label for="date"><?php echo JText::_('Дата публикации');?></label></td>
                <td><?php echo JHTML::_('calendar', $date, 'date', 'date', '%Y-%m-%d %H:%M:%S', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19' ,'readonly' => '')); ?></td>
              </tr>
			
			<?php if( $id ): ?>
              <tr>
              	<td class="key"><?php echo JText::_('Автор');?></td>
                <td><?php echo $name->title;?></td>
              </tr>
			<?php endif;?>
             <tr>
              	<td class="key"><label for="image"><?php echo JText::_('Изображение');?></label></td>
                <td><input type="file" name="image" id="image" /></td>
              </tr>
              
              <?php if( $image ):?>
               <tr>
              	<td class="key"><label><?php echo JText::_('Текущее изображение');?></label></td>
                <td><img src="../images/sunny/content/<?php echo $image?>?w=100&h=100&tc&ns" /></td>
              </tr>
              <input type="hidden" name="image_hid" value="<?php echo $image?>" />           	
              <?php endif;?>
                    
			</tbody>
		  </table>
	  </fieldset>

		  <fieldset class="adminform">
		  <table class="admintable">
			<tbody>
			  <tr>
				<td class="key"><label for="s_desc"><?php echo JText::_('Краткое описание');?></label></td>
				<td><?php echo $editor->display( 's_desc', ''.$s_desc.'', '900', '300', '20', '20');?></td>
			  </tr>
			  <tr>
              	<td class="key"><label for="s_desc"><?php echo JText::_('Полное описание');?></label></td>
				<td><?php echo $editor->display( 'desc', ''.$desc.'', '900', '400', '20', '20');?></td>
			  </tr>		
		    </tbody>
		  </table>  
		  </fieldset>
 

<input type="hidden" name="option" value="com_content" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="item" />
<input type="hidden" name="id_user" value="<?php echo $id_user ?>" />
<input type="hidden" name="isAdmin" value="<?php echo $isAdmin ?>" />
</form>
