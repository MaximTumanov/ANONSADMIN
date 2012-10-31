<?php
$id = JRequest::getVar('id');
$model = $this->getModel();
$editor   =& JFactory::getEditor();

jimport('joomla.html.pane');
$tabs = & JPane::getInstance('tabs');

if($id){
	$item      = $model->getItem($id);
	
	$title     = $item->title;
	$alias     = $item->alias;
	$s_desc    = $item->s_desc;
	$desc      = $item->desc;
	$date      = $item->date;
	$k_title 	 = $item->k_title;
	$k_desc 	 = $item->k_desc;
	$k_keyw    = $item->k_keyw;
	$image     = $item->image;
	$images_folder = $item->images_folder;
	
	$published = $item->published;
	
} else {
	$title     = '';
	$alias     = '';
	$s_desc    = '';
	$desc      = '';
	$published = 1;
	$type      = 1;
	$date      = date('Y-m-d');

	$k_title 	 = '';
	$k_desc 	 = '';
	$k_keyw    = '';
	$image     = '';
	$images_folder = '';
}

$pubList    = BlogHelper::selectYN($published, 'published');
$tree = JFolder::folders('../images/blog_images');
sort($tree);

$tree_arr = array();
foreach ($tree as $key => $value) {
	$obj = new stdClass;
	$obj->value = $value;
	$obj->text = $value;
	$tree_arr[] = $obj;
}

$imagesFolderList = BlogHelper::selectList($tree_arr, 'images_folder', 'Выберите папку', $images_folder);

$dir      = '../images'.DS.'sunny'.DS.'blog';
$dir_echo = 'images'.DS.'sunny'.DS.'blog';
JHTML::_('behavior.calendar');
?>
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">

<fieldset class="adminform">
     <?php if( !is_writable($dir) ):?>
     	<div style="padding:5px 0 10px 0">
            <?php echo JText::sprintf(PARAMSUNWRITABLE, $dir_echo);?>
        </div>
     <?php endif;?>


     <table class="admintable">
		<tr>
			<td class="key"><label for="title"><?php echo JText::_('Название');?></label></td>
			<td><input type="text" value="<?php echo $title?>" size="60"  name="title" id="title" class="inputbox"></td>
	   	</tr>
			     	
	  	<tr>
	   		<td class="key"><label for="alias"><?php echo JText::_('Псевдоним');?></label></td>
	   		<td><input type="text" value="<?php echo $alias?>" size="60"  name="alias" id="alias" class="inputbox"></td>
	   	</tr> 	
	   	
	   	<tr>
	   		<td class="key"><label for="date"><?php echo JText::_('Дата');?></label></td>
	   		<td><?php echo JHTML::_('calendar', substr($date, 0, 11), 'date', 'date', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?></td>
	   	</tr>

	   	<tr>
	   		<td class="key"><label for="published"><?php echo JText::_('Опубликовано');?></label></td>
	   		<td><?php echo $pubList?></td>
	   	</tr>	
	   	
	
	 	<!--tr>
	 		<td class="key"><label for="s_desc"><?php echo JText::_('Краткое описание');?></label></td>
	 		<td><textarea style="width: 262px; height: 100px" name="s_desc" id="s_desc"><?php echo $s_desc?></textarea></td>
	 	</tr-->


	 	<tr>
	 		<td class="key"><label for="k_title"><?php echo JText::_('Мета тег - title');?></label></td>
	 		<td><textarea style="width: 262px; height: 100px" name="k_title" id="k_title"><?php echo $k_title?></textarea></td>
	 	</tr>


	 	<tr>
	 		<td class="key"><label for="k_desc"><?php echo JText::_('Мета тег - description');?></label></td>
	 		<td><textarea style="width: 262px; height: 100px" name="k_desc" id="k_desc"><?php echo $k_desc?></textarea></td>
	 	</tr>


	 	<tr>
	 		<td class="key"><label for="k_keyw"><?php echo JText::_('Мета тег - keywords');?></label></td>
	 		<td><textarea style="width: 262px; height: 100px" name="k_keyw" id="k_keyw"><?php echo $k_keyw?></textarea></td>
	 	</tr>	 		 	

		<tr>
			<td class="key"><label for="image"><?php echo JText::_('Изображение');?></label></td>
			<td><input type="file" name="image" id="image" /></td>
		</tr>
    <?php if($image):?>
    <tr>
    	<td class="key"><label><?php echo JText::_('Текущее изображение');?></label></td>
        <td>
            <img src="../images/sunny/blog/<?php echo $image?>?w=110&h=160&tc&ns"/>
            <input type="hidden" name="imageHid" value="<?php echo $image?>" />
        </td>
    </tr>
    <?php endif;?>

		<tr>
			<td class="key"><label for="images_folder"><?php echo JText::_('Доп. изображения');?></label></td>
			<td><?php echo $imagesFolderList;?></td>
		</tr>	  

    <tr>
			<td colspan="3" style="padding-top:30px"><?php echo $editor->display('desc', ''.$desc.'', '900', '400', '20', '20');?></td>
		</tr>		     	 				     	
  </table>
</fieldset>

<input type="hidden" name="option" value="<?php echo $this->component?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="<?php echo $this->view?>" />

</form>