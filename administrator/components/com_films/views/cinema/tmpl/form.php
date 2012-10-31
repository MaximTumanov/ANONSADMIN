<?php 
$id = JRequest::getVar('id');
$model = $this->getModel();
$editor   =& JFactory::getEditor();

jimport('joomla.html.pane');
$tabs = & JPane::getInstance('tabs');

if($id):
	$item      = $model->getItem($id);
	
	$title     = str_replace('"', '\'', $item->title);
	$alias     = $item->alias;
	$icon      = $item->image;
	$address   = $item->address;
	$tel       = $item->tel;
	$email     = $item->email;
	$desc      = $item->desc;
	$published = $item->published;

	$k_title   = $item->s_desc;
	$k_desc    = $item->desc;
	$k_keyw	   = $item->published;
	
	$google    = explode(':', $item->google);
	$g_x       = ($google[0]) ? $google[0] : 48.45857956168727;
	$g_y       = ($google[1]) ? $google[1] : 35.045013427734375;
	$g_zoom    = ($google[2]) ? $google[2] : 13;
	
	$films    = $model->getFilms($films);
	
	
else:
	$title     = '';
	$alias     = '';
	$icon      = '';
	$address   = '';
	$tel       = '';
	$email     = '';
	$desc      = '';
	$published = '';

	$k_title   = '';
	$k_desc    = '';
	$k_keyw	   = '';

	$g_x       = 48.45857956168727;
	$g_y       = 35.045013427734375;
	$g_zoom    = 13;	
	
	$films = false;
	
endif;



$dir      = '../images'.DS.'sunny'.DS.'films'.DS.'cinema';
$dir_echo = 'images'.DS.'sunny'.DS.'films'.DS.'cinema,';

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

<?php
	echo $tabs->startPane("contentelements");
	echo $tabs->startPanel(JText::_('Основная информация'),"1");
?>
<style>
#eFiltr label, #eFiltr input[type=checkbox]{
	cursor: pointer;
}
</style>
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">
<input type="hidden" name="option" value="<?php echo $this->component?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="<?php echo $this->view?>" />
<input type="hidden" name="g_x" id="g_x" value="<?php echo $g_x?>" />
<input type="hidden" name="g_y" id="g_y" value="<?php echo $g_y?>" />
<input type="hidden" name="g_zoom" id="g_zoom" value="<?php echo $g_zoom?>" />
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
				<td class="key"><label for="alias"><?php echo JText::_('Псевдоним');?></label></td>
				<td><input type="text" value="<?php echo $alias?>" maxlength="255" size="60"  name="alias" id="alias" class="inputbox">
				</td>
			  </tr>
			 
			  <tr>
				<td class="key"><label for="address"><?php echo JText::_('Адрес');?></label>
				</td>
				<td><input type="text" value="<?php echo $address?>" maxlength="255" size="60"  name="address" id="address" class="inputbox">
				</td>
			  </tr>
			  
			  <tr>
				<td class="key"><label for="tel"><?php echo JText::_('Телефон');?></label>
				</td>
				<td><input type="text" value="<?php echo $tel?>" maxlength="255" size="60"  name="tel" id="tel" class="inputbox">
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
              	<td class="key"><label for="image"><?php echo JText::_('Изображение');?></label></td>
                <td><input type="file" name="image" id="image" /></td>
              </tr>
              
                <?php if( $icon ):?>
                <tr>
                	<td class="key"><label><?php echo JText::_('Текущее изображение');?></label></td>
                    <td>
                        <img style="max-width: 370px" src="../images/sunny/films/cinema/<?php echo $icon?>?w=200&ns" />
                        <input type="hidden" name="imageHid" value="<?php echo $icon?>" />
                    </td>
                </tr>
                <?php endif;?>
      			
       			<tr>
       				<td colspan="2" style="padding-top: 30px"><?php echo $editor->display( 'desc', ''.$desc.'', '900', '400', '20', '20');?></td>
       			</tr>
       			
			</tbody>
		  </table>

<?php 
	echo $tabs->endPanel();
	echo $tabs->startPanel(JText::_('Месторасположение'),"2");
?>

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=AIzaSyCnYwlHkVDRGzc2Q6QLRSTxqBe5vmd05QY" type="text/javascript"></script>
<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0" type="text/javascript"></script>
<!--<script src="http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js" type="text/javascript"></script>
<style type="text/css">
@import url("http://www.google.com/uds/css/gsearch.css");
@import url("http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css");
</style>-->
<script type="text/javascript">
	jQuery(document).ready(function() {
		GUnload();
        var map = new GMap2(document.getElementById("google_maps"));
        var center = new GLatLng(48.45857956168727, 35.045013427734375);
        map.setCenter(center, 12);
        map.addControl(new GLargeMapControl());
        //map.addControl(new GMapTypeControl(), new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(10,20)));
		//map.addControl(new google.maps.LocalSearch(), new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10,20)));

        var center = map.getCenter();
        var marker = new GMarker(center, {draggable: true});
        jQuery('#g_x').val(marker.getPoint().x);
        jQuery('#g_y').val(marker.getPoint().y);
        jQuery('#g_zoom').val(map.getZoom());
          GEvent.addListener(marker, "dragstart", function() {
              map.closeInfoWindow();
          });
          GEvent.addListener(marker, "dragend", function() {
              jQuery('#g_x, #_g_x').val(this.getPoint().x);
              jQuery('#g_y, #_g_y').val(this.getPoint().y);
              jQuery('#g_zoom, #_g_zoom').val(map.getZoom());
          });

        map.addOverlay(marker);
});
</script>

<table class="admintable">  
		
		        <tr>
		          <td class="key"><label for="g_x"><?php echo JText::_('x');?></label></td>
		          <td><input type="text" value="<?php echo $g_x?>" maxlength="255" size="60"  name="_g_x" id="_g_x" class="inputbox" readonly="readonly"></td>
		        </tr>
		 
		        <tr>
		          <td class="key"><label for="g_y"><?php echo JText::_('y');?></label></td>
		          <td><input type="text" value="<?php echo $g_y?>" maxlength="255" size="60"  name="_g_y" id="_g_y" class="inputbox" readonly="readonly"></td>
		        </tr>
		
		        <tr>
		          <td class="key"><label for="g_zoom"><?php echo JText::_('Приближение');?></label></td>
		          <td><input type="text" value="<?php echo $g_zoom?>" maxlength="255" size="60"  name="_g_zoom" id="_g_zoom" class="inputbox" readonly="readonly"></td>
		        </tr>
		
  </table>
<div id="google_maps" style="width: 100%; height: 600px;"></div>
<?php 
	echo $tabs->endPanel();
	if ($id):
	echo $tabs->startPanel(JText::_('Фильмы'),"3");
?>

	<table class="admintable">
		<tr>
			<td colspan="3" id="eFiltr">Фильтр фильмов: 
				<input type="checkbox" checked="checked" id="eAll" class="all" /> - <label for="eAll">все</label>
				<input type="checkbox" class="published" id="ePublished" /> - <label for="ePublished">опубликованные</label>
			</td>
		</tr>
		<?php
		if ($films):
			$days = array(
				'0' => 'Воскресенье', '1' => 'Понедельник', '2' => 'Вторник',
				'3' => 'Среда', '4' => 'Четверг', '5' => 'Пятница', '6' => 'Суббота'
			);		
			foreach ($films as $film):

				$href = JRoute::_("index.php?option=com_films&view=films&layout=form&task=edit&id={$film->id_films}");
				
				$pub = ($film->published) 
					? "<img title='Опубликвано' src='images/tick.png' />" 
					: "<img title='Неопубликвано' src='images/publish_x.png' />";
				
				$ePublished = ($film->published) ? 'published' : '';	
				$eType = '';
				

						$time = substr($dates[0]->time, 0, 5);
				
				echo "<tr class='filter {$ePublished}'><td>{$pub}</td><td><a href='{$href}'>{$film->title}</a></td><td style='color: #333;'>{$time}</td></tr>";
			endforeach;
		endif;
		?>
	</table>

<?php 
	echo $tabs->endPanel(); 
	endif;
	echo $tabs->endPane();
?> 

</form>

<script type="text/javascript">
jQuery(function(){
	jQuery('#eFiltr input[type=checkbox]').click(function (){
		var f = jQuery(this).attr('class'),
			tr = jQuery('.filter');
		if (f == 'all') {
			tr.show();
			jQuery('#eFiltr input[type=checkbox]').removeAttr('checked');
			jQuery(this).attr('checked', 'checked');
		} else {
			jQuery('#eFiltr .all').removeAttr('checked');
			tr.hide();
			updateTable();
		}
	});
})

function updateTable() {
	var tr = jQuery('.filter');
	jQuery('#eFiltr input[type=checkbox]').each(function (){
		if (jQuery(this).is(':checked')) {
			var showed = jQuery(this).attr('class');
			jQuery('tr.' + showed).show();
		}
	});
}

</script>