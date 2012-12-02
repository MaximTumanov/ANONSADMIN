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
	$address   = $item->address;
	$image     = $item->image;	
	$s_desc    = $item->s_desc;
	$desc      = $item->desc;
	$type      = $item->type; // 1 - одна дата, 2 - еженедельное, 3 - ежемесячное, 4 - произвольные даты, 5 - период дат
	$wtf       = $item->wtf;
	$k_title 	 = $item->k_title;
	$k_desc 	 = $item->k_desc;
	$k_keyw    = $item->k_keyw;
	$price     = $item->price;
	
	$vip       = $item->vip;
	$published = $item->published;
	$vk_video = $item->vk_video;
	
	$id_category = $model->getIdCategory($id);
	
	$id_place  = $model->getIdPlace($id);
	if ($type != 4) {
		$eDate = $model->getEventDate($id);
		list($hour, $minute) = explode(':', $eDate->time);
		$date = $eDate->date;
		
		if ($type == 2 || $type == 3 || $type == 5) {
			$eDate = $model->getEventDate($id, true);
			list($hour, $minute) = explode(':', $eDate->time);
			$day = $eDate->day;
			
			if ($eDate->dateFrom) {
				$date_from = $eDate->dateFrom;
			}
			
			if ($eDate->dateTo) {
				$date_to = $eDate->dateTo;
			}
		}
	} else {
		
	}
	
} else {
	$title     = '';
	$alias     = '';
	$address   = '';
	$s_desc    = '';
	$desc      = '';
	$image     = '';
	$vip       = 0;
	$published = 1;
	$type      = 1;
	$date      = date("Y-m-d");
	$id_place  = 0;
	$minute    = '00';
	$hour      = '00';
	$day       = 0;
	$wtf = 0;
	$id_category = 0;

	$k_title 	 = '';
	$k_desc 	 = '';
	$k_keyw    = '';
	$price     = '';
	$vk_video = '';
}

$hours = array(
	'00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05',
	'06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11',
	'12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19',
	'20' => '20', '21' => '21', '22' => '22', '23' => '23'
);

$datess = array(
	'00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05',
	'06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11',
	'12' => '12', '13' => '14', '15' => '16', '17' => '17', '18' => '18', '19' => '19',
	'20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25',
    '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31'
);

$minutes = array(
	'00' => '00', '05' => '05', '10' => '10', '15' => '15', '20' => '20', '25' => '25',
	'30' => '30', '35' => '35', '40' => '40', '45' => '45', '50' => '50', '55' => '55'
);

$days = array(
	'0' => 'Воскресенье', '1' => 'Понедельник', '2' => 'Вторник',
	'3' => 'Среда', '4' => 'Четверг', '5' => 'Пятница', '6' => 'Суббота'
);

$wtfs = array('0' => 'Обычное', '1' => 'Премьера', '2' => 'Гастроли');

$placeList  = OurusersHelper::selectList($model->getPlaceList(), 'id_place', 'Выбирите организатора', $id_place);
$catList    = OurusersHelper::selectListMulti($model->getCategory(), 'id_category', $id_category);

$hoursList  = OurusersHelper::selectLists($hours, 'hour', '00', $hour);
$minutsList = OurusersHelper::selectLists($minutes, 'minut', '00', $minute);
$daysList   = OurusersHelper::selectLists($days, 'day', '0', $day);
$datesList  = OurusersHelper::selectLists($datess, 'day', '0', $day);

$vipList    = OurusersHelper::selectYN($vip, 'vip');
$pubList    = OurusersHelper::selectYN($published, 'published');
$wtfList    = OurusersHelper::selectLists($wtfs, 'wtf', '0', $wtf);

$dir      = '../images'.DS.'sunny'.DS.'events'.DS.'events';
$dir_echo = 'images'.DS.'sunny'.DS.'events'.DS.'events';
JHTML::_('behavior.calendar');
?>
<style type="text/css">
	#check_status{margin-left: 10px}
	.no{color: red;}
	.ok{color: green;}
</style>
<form action="index.php" method="post" name="adminForm" enctype="multipart/form-data">

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
			<td><input type="text" value='<?php echo $title?>' size="60"  name="title" id="title" class="inputbox">&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" id="check_title">проверить!</a> <span id="check_status"></span></td>
			<td rowspan="7" align="left" valign="top">
				<div style="margin-bottom: 10px;">Категория
				<?php echo JHTML::_('tooltip',  'Можно выбрать несколько категорий, зажав Ctrl!'); ?>
				</div>
				<?php echo $catList;?>
			</td>
	   	</tr>
			     	
	  	<tr>
	   		<td class="key"><label for="alias"><?php echo JText::_('Псевдоним');?></label></td>
	   		<td><input type="text" value="<?php echo $alias?>" size="60"  name="alias" id="alias" class="inputbox"></td>
	   	</tr>

	  	<tr>
	   		<td class="key"><label for="vk_video"><?php echo JText::_('Видео VK');?></label></td>
	   		<td>
	   			<input type="text" value="<?php echo $vk_video?>" size="60"  name="vk_video" id="vk_video" class="inputbox">
	   			<?php echo JHTML::_('tooltip',  'Например, http://player.vimeo.com/video/51482700'); ?>
	   		</td>
	   	</tr>

	  	<tr>
	   		<td class="key"><label for="price"><?php echo JText::_('Цена');?></label></td>
	   		<td>
	   			<p><input type="radio" name="price_radio" checked data-value="" class="set_price" /> - ввод цены</p>
	   			<p><input type="radio" name="price_radio" data-value="Уточняйте дополнительно" class="set_price" /> - уточняйте дополнительно</p>
	   			<p><input type="radio" name="price_radio" data-value="Вход свободный" class="set_price" /> - вход свободный</p>
	   			<input type="text" value="<?php echo $price?>" size="20"  name="price" id="price" class="inputbox">
	   			<?php echo JHTML::_('tooltip',  'Диапазон цен указуйте церез дефис, например, 50-100'); ?>
	   		</td>
	   	</tr>
			
	   	<tr>
	   		<td class="key"><label for="id_place"><?php echo JText::_('Организатор');?></label></td>
	   		<td><?php echo $placeList?></td>
	   	</tr>
			     	
	   	<tr>
	   		<td class="key"><label for="address"><?php echo JText::_('Место проведения');?></label></td>
	   		<td>
	   			<input type="text" value="<?php echo $address?>" size="60"  name="address" id="address" class="inputbox">
	   			<?php echo JHTML::_('tooltip',  'Оставьте пустым, если место проведения совпадает с адресом организатора!'); ?>
	   		</td>
	   	</tr>      	

	   	<tr>
	   		<td class="key"><label for="wtf"><?php echo JText::_('Тип события');?></label></td>
	   		<td><?php echo $wtfList?></td>
	   	</tr>	

	   	<tr>
	   		<td class="key"><label for="vip"><?php echo JText::_('VIP');?></label></td>
	   		<td>
	   			<?php echo $vipList?>
	   			<?php echo JHTML::_('tooltip',  'Vip событие отображается в специальном блоке!'); ?>
	   		</td>
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
	                        <img src="../images/sunny/events/events/<?php echo $image?>?w=300&h430&tc&ns"/>
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
	echo $tabs->startPanel(JText::_('Дата провеления'),"2");
?>
<fieldset class="adminform">
	<table class="admintable">
		<tr>
			<td><input type="radio" name="type" id="type-1" value="1" <?php if ($type == 1) echo 'checked';?> /> <label for="type-1">одна дата</label></td>
			<td><input type="radio" name="type" id="type-5" value="5" <?php if ($type == 5) echo 'checked';?> /> <label for="type-5">период</label></td>
			<td><input type="radio" name="type" id="type-2" value="2" <?php if ($type == 2) echo 'checked';?> /> <label for="type-2">еженедельное событие</label></td>
			<td><input type="radio" name="type" id="type-3" value="3" <?php if ($type == 3) echo 'checked';?> /> <label for="type-3">ежемесячное событие</label></td>
			<td><input type="radio" name="type" id="type-4" value="4" <?php if ($type == 4) echo 'checked';?> /> <label for="type-4">произвольные даты</label></td>
			<td><input type="radio" name="type" id="type-6" value="6" <?php if ($type == 6) echo 'checked';?> /> <label for="type-6">Дата уточняеться</label></td>
		</tr>
		<tr>
			<td colspan="6">
				<div id="tabss-1" <?php echo ($type == 1) ? '' : "style='display:none'" ;?>>
					<?php echo JHTML::_('calendar', substr($date, 0, 11), 'date', 'date-1', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
					<?php echo $hoursList; ?> : <?php echo $minutsList?>
				</div>
				<div id="tabss-5" <?php echo ($type == 5) ? '' : "style='display:none'" ;?>>
					<p><span>Начало:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php echo JHTML::_('calendar', substr($date_from, 0, 11), 'date_from', 'date_from-5', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?></p>
					<p><span>Окончание:&nbsp;</span> <?php echo JHTML::_('calendar', substr($date_to, 0, 11), 'date_to', 'date_to', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?></p>
					<p><span>Время проведения:</span> <?php echo $hoursList; ?> : <?php echo $minutsList?></p>
				</div>
				<div id="tabss-2" <?php echo ($type == 2) ? '' : "style='display:none'" ;?>>
					<p><span>Начало:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php echo JHTML::_('calendar', substr($date_from, 0, 11), 'date_from', 'date_from-2', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?></p>
					<p><span>Окончание:&nbsp;</span> <?php echo JHTML::_('calendar', substr($date_to, 0, 11), 'date_to', 'date_to-2', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?></p>
					<p><span>День недели:</span> <?php echo $daysList; ?></p>
					<p><span>Время проведения:</span> <?php echo $hoursList; ?> : <?php echo $minutsList?></p>
				</div>
				<div id="tabss-3" <?php echo ($type == 3) ? '' : "style='display:none'" ;?>>
					<p><span>Начало:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php echo JHTML::_('calendar', substr($date_from, 0, 11), 'date_from', 'date_from-3', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?></p>
					<p><span>Окончание:&nbsp;</span> <?php echo JHTML::_('calendar', substr($date_to, 0, 11), 'date_to', 'date_to-3', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?></p>
					<p><span>Дата события:</span> <?php echo $datesList; ?></p>
					<p><span>Время проведения:</span> <?php echo $hoursList; ?> : <?php echo $minutsList?></p>
				</div>
				<div id="tabss-4" <?php echo ($type == 4) ? '' : "style='display:none'" ;?>>В разработке!</div>
				<div id="tabss-6" <?php echo ($type == 6) ? '' : "style='display:none'" ;?>>
					<?php echo JHTML::_('calendar', substr($date, 0, 11), 'date', 'date-6', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
					<?php echo $hoursList; ?> : <?php echo $minutsList?>
				</div>
			</td>
		</tr>
	</table>
</fieldset>
<?php 
	echo $tabs->endPanel(); 
	echo $tabs->endPane();
?> 

<input type="hidden" name="option" value="<?php echo $this->component?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="<?php echo $this->view?>" />

</form>
  <script type="text/javascript">
  		jQuery(function (){

  			jQuery('#check_title').click(function(event){
  				event.preventDefault();
  				var title = jQuery('#title').val()
  					,	div = jQuery('#check_status');
  				jQuery.ajax({
  					url: "ajax.php?option=com_events&task=check&controller=event",
  					type: "POST",
  					dataType: "json",
  					data: {title: title},
  					success: function(data){
  						if (data) {
  							div.removeClass('ok').addClass('no').text('Уже существует');
  						} else {
  							div.removeClass('no').addClass('ok').text('Не найден');
  						}
  					}
  				})
  			});

  			var default_price = jQuery('#price').val();

  			jQuery('.set_price').click(function(){
  				var el = jQuery(this)
  					,	val = el.data('value')
  					,	input = jQuery('#price');

  				input.val(val == '' ? default_price : val);
  			});

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
			} else if (!form.id_category.value) {
				alert('Укажите категорию событий!'); 
				return false;
			} else if (form.id_place.value == 0) {
				alert('Укажите организатора!'); 
				form.id_place.focus(); 
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