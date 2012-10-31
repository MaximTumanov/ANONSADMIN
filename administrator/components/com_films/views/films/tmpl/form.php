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
	$image     = $item->image;	
	$s_desc    = $item->s_desc;
	$desc      = $item->desc;
	$premiere  = $item->premiere;
	$video     = $item->video;
	$date_first     = $item->date_first;
	$date_last     = $item->date_last;
	$genre    = $item->genre;
	$duration     = $item->duration;
	$director     = $item->director;
	$country     = $item->country;
	$actors     = $item->actors;
	$_3d     = $item->_3d;
	$k_title 	 = $item->k_title;
	$k_desc 	 = $item->k_desc;
	$k_keyw    = $item->k_keyw;
	$original_name = $item->original_name;
	
	$published = $item->published;
		
	$id_cinema  = $model->getIdCinema($id);
	$time_at = $model->getTimeAtFilm($id);

} else {
	$title     = '';
	$alias     = '';
	$s_desc    = '';
	$desc      = '';
	$image     = '';
	
	$time_at = array();

	$video      = '';
	$genre      = '';
	$duration      = '';
	$director      = '';
	$country      = '';
	$actors      = '';
	$_3d      = 0;
	
	$date_first      = date("Y-m-d");
	$date_last      = date("Y-m-d");
	
	$k_title      = '';
	$k_desc      = '';
	$k_keyw      = '';
	
	$premiere       = 0;
	$published = 1;
	
	$id_cinema  = 0;
	$original_name = '';	

}


$premieres = array('0' => 'Обычное', '1' => 'Премьера');
$_3ds = array('0' => 'Обычное', '1' => '3D');

$cinemaList  = FilmsHelper::selectList($model->getCinemaList(), 'id_cinema', 'Выбирите кинотеатр', $id_cinema);

$pubList    = FilmsHelper::selectYN($published, 'published');

$premiereList    = FilmsHelper::selectLists($premieres, 'premiere', '0', $premiere);
$_3dList    = FilmsHelper::selectLists($_3ds, '_3d', '0', $_3d);


$dir      = '../images'.DS.'sunny'.DS.'films'.DS.'films';
$dir_echo = 'images'.DS.'sunny'.DS.'films'.DS.'films';
JHTML::_('behavior.calendar');
?>
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
<input type="hidden" name="option" value="<?php echo $this->component?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="<?php echo $this->view?>" />
<?php
	echo $tabs->startPane("contentelements");
	echo $tabs->startPanel(JText::_('Основная информация'),"1");
?>

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
	   		<td class="key"><label for="original_name"><?php echo JText::_('Ориг. название');?></label></td>
	   		<td><input type="text" value="<?php echo $original_name?>" size="60" disabled readonly name="original_name" id="original_name" class="inputbox"></td>
	   	</tr>
			
	   	<tr>
	   		<td class="key"><label for="id_cinema"><?php echo JText::_('Кинотеатр');?></label></td>
	   		<td><?php echo $cinemaList?></td>
	   	</tr>
			     	

	   	<tr>
	   		<td class="key"><label for="premiere"><?php echo JText::_('Премьера');?></label></td>
	   		<td>
	   			<?php echo $premiereList?>
	   			
	   		</td>
	   	</tr>
			   	<tr>
	   		<td class="key"><label for="_3d"><?php echo JText::_('3D');?></label></td>
	   		<td>
	   			<?php echo $_3dList?>
	   			
	   		</td>
	   	</tr>


	  	<tr>
	   		<td class="key"><label for="date_first"><?php echo JText::_('Дата начала');?></label></td>
	   		<td><?php echo JHTML::_('calendar', $date_first, 'date_first', 'date_first', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); ?></td>
	   	</tr>
	  	<tr>
	   		<td class="key"><label for="date_last"><?php echo JText::_('Дата окончания');?></label></td>
	   		<td><?php echo JHTML::_('calendar', $date_last, 'date_last', 'date_last', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); ?></td>
	   	</tr>		
	   	
		
		     	<tr>
	 		<td class="key"><label for="video"><?php echo JText::_('Видео');?></label></td>
	 		<td><textarea style="width: 262px; height: 100px" name="video" id="video"><?php echo $video?></textarea></td>
	 	</tr>
		
	   	<tr>
	   		<td class="key"><label for="published"><?php echo JText::_('Опубликовано');?></label></td>
	   		<td><?php echo $pubList?></td>
	   	</tr>	
	   	
	
	 	<tr>
	 		<td class="key"><label for="s_desc"><?php echo JText::_('Краткое описание');?></label></td>
	 		<td><textarea style="width: 262px; height: 100px" name="s_desc" id="s_desc"><?php echo $s_desc?></textarea></td>
	 	</tr>

		<tr>
	 		<td class="key"><label for="genre"><?php echo JText::_('Жанр');?></label></td>
	 		<td><textarea style="width: 262px; height: 100px" name="genre" id="genre"><?php echo $genre?></textarea></td>
	 	</tr>
		
		<tr>
	 		<td class="key"><label for="duration"><?php echo JText::_('Продолжительность');?></label></td>
	 		<td><textarea style="width: 262px; height: 100px" name="duration" id="duration"><?php echo $duration?></textarea></td>
	 	</tr>
		
				<tr>
	 		<td class="key"><label for="director"><?php echo JText::_('Режисер');?></label></td>
	 		<td><textarea style="width: 262px; height: 100px" name="director" id="director"><?php echo $director?></textarea></td>
	 	</tr>
				<tr>
	 		<td class="key"><label for="country"><?php echo JText::_('Страна');?></label></td>
	 		<td><textarea style="width: 262px; height: 100px" name="country" id="country"><?php echo $country?></textarea></td>
	 	</tr>
		<tr>
	 		<td class="key"><label for="actors"><?php echo JText::_('Актеры');?></label></td>
	 		<td><textarea style="width: 262px; height: 100px" name="actors" id="actors"><?php echo $actors?></textarea></td>
	 	</tr>
		
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
	                        <img style="max-width: 200px" src="../images/sunny/films/films/<?php echo $image?>"/>
	                        <input type="hidden" name="imageHid" value="<?php echo $image?>" />
	                    </td>
	                </tr>
	                <?php endif;?>
	    
	    <tr>
			<td colspan="3" style="padding-top:30px"><?php echo $editor->display('desc', ''.$desc.'', '900', '400', '20', '20');?></td>
		</tr>		     	 				     	
     </table>
</fieldset>

<?php 
	echo $tabs->endPanel(); 
	echo $tabs->startPanel(JText::_('Время сеанса'),"2");
?>

<fieldset class="adminform">
	<table class="admintable">

	 	<tr>
	 		<td class="key"><label><?php echo JText::_('Пример');?></label></td>
	 		<td><input type="text" value="09:15" disabled redonly /></td>
	 	</tr>

		<?php for($i=0; $i < 15; $i++):?>

	 	<tr>
	 		<td class="key"><label><?php echo JText::_('Время');?></label></td>
	 		<td><input type="text" name="time_at[]" value="<?php echo (isset($time_at[$i]) && $time_at[$i] ) ? $time_at[$i] : ''?>" /></td>
	 	</tr>		

	 	<?php endfor;?>

	</table>
</fieldset>
<?php 
	echo $tabs->endPanel(); 
	echo $tabs->endPane();
?> 



</form>
  <script type="text/javascript">
  		jQuery(function (){
  			jQuery('input[type=radio]').change(function () {
				var id = jQuery(this).attr('id').replace('type-', '');
				jQuery('[id*=tabss-]').hide();
				jQuery('#tabss-' + id).show();
			});
  	  	})
  	  	
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if(!form.title.value) {
				alert('Укажите название!'); 
				form.title.focus(); 
				return false; 2011-08-28

			} else if (form.id_cinema.value == 0) {
				alert('Укажите кинотеатр!'); 
				form.id_cinema.focus(); 
				return false; 
			} else {
				jQuery('.current dd').eq(1).show();
				jQuery('[id*=tabss-]').each(function () {
					if (!jQuery(this).is(':visible')) {
						jQuery(this).html('');
					}
				});
				submitform(pressbutton);
			}
		}
		
		window.addEvent('domready', function(){ var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false}); });
  </script> 