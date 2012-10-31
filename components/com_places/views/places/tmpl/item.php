<?php
	$model = $this->getModel();
	$id = JRequest::getInt('id');
	$item = $model->getPlace($id);

	$item->title = ($item->dop_title) ? $item->dop_title : $item->title; 
	
	$item->title = str_replace('\'', '"', $item->title);
	
	// устанавливаем мета-данные
	$doc = &JFactory::getDocument();
	$doc->setTitle("Anons.dp.ua | {$item->title}");
	$doc->setMetaData('keywords', "Anons.dp.ua | {$item->title}");
	$doc->setMetaData('description', strip_tags(JString::cropstr(str_replace('"', '\'', $item->desc), 50)));
	if ($item->image) {
		$doc->setMetaData('image', JURI::base() . "images/sunny/events/places/{$item->image}");
	}
		
	$relatedEvents = $model->getRelatedEvents($id);
	list($g_x, $g_y, $g_zoom) = explode(':', $item->google);
	
	$my_places_ids = array();
	$count_users_like = $model->getCountUsersLike($id);
	
if ($_COOKIE["anons_dp_ua"]) {
	if (!isset($_POST['method']) && $_POST['method'] != 'login') {
		$data = explode('|', $_COOKIE["anons_dp_ua"]);
		$action = 'logout';
		$action_title = 'Выйти';
		$form_title = 'Мой кабинет';
		
		$id_user = (int) substr($data[0], 32);
		
		$db = &JFactory::getDBO();
		$db->setQuery("SELECT GROUP_CONCAT(id_place) as `places` FROM `#__user_places` WHERE `id_user` = '{$id_user}'");
		$my_places_ids = explode(',', $db->loadResult());
	}
}		
?>
<div class="left upcoming item">
	<div class="wrapp">
    	<?php 
    		if ($item->icons):
    			$icons = explode(',', $item->icons);
    			echo '<div class="event_cat">';
    				foreach ($icons as $icon):
    					$image = JHTML::MegaImg('events/category', $icon, '', '', '');
    					echo "<span>{$image}</span>";
    				endforeach;
    			echo '</div>';
    		endif;
    	?>	
            		<h1><p class="event_title"><?php echo $item->title?></p></h1>
            		<?php echo ($item->image) 
            			? JHTML::MegaImg('events/places', $item->image, 'event_img', 'float: left', $item->title, EVENT_IMG_DESC)
            			: JHTML::MegaImg('events', 'no-image.gif', 'event_img', 'float: left');
            		?>
            		<div class="event_info">
            		            			
            			<?php if ($item->address):?>
            			<p class="titl">Адрес:</p> 
            			<p><?php echo $item->address;?> <?php if (isset($g_x) && isset($g_y) && isset($g_zoom)):?>&rarr; <a href="#" class="maps" id="show_google_maps">показать на карте</a><?php endif;?></p>
            			<?php endif;?>
            			
            			<?php if ($item->tel):?>
            			<p class="titl">Телефоны:</p> 
            			<p><?php echo strip_tags($item->tel);?></p>
            			<?php endif;?>

            			<?php if ($item->web):?>
            			<p class="titl">Сайт:</p>
            			<noindex><p><a href="<?php echo $item->web; ?>" target="_blank"><?php echo $item->web?></a></p></noindex>
            			<?php endif;?>  
            			
            			<?php if ($item->email && $item->show_email):?>
            			<p class="titl">Email:</p>
            			<noindex><p><a href="mailto:<?php echo $item->email?>"><?php echo $item->email?></a></p></noindex>
            			<?php endif;?>  
            			         			
            			       			
            		</div>
              		
						<div style="margin: -60px 0 0 0;">
							<span id="social_nets"></span>
	            			<?php if(in_array($id, $my_places_ids)):?>
	            				<span class="place_go button disabled" id="place_go_<?php echo $id?>">Я передумал(а)</span>
	            			<?php else:?>
	            				<span class="place_go button" id="place_go_<?php echo $id?>">Мне нравится</span>
	            			<?php endif;?>
	            			<span class="user_like"><?php echo $count_users_like?></span>
						</div>

            		<div class="clearer"></div>
            		
            		<div class="event_text">
						<?php echo $item->desc?>           		
            		</div>
            		
            		<?php if (isset($g_x) && isset($g_y) && isset($g_zoom)):?>
            			<div id="google_maps" class="hide"></div>
            		<?php endif;?>
            	</div>
          	
          	<?php if ($relatedEvents):?>
          		<div class="related_event_title"><?php echo $item->title;?> -o- ближайшие события</div>
          		<div style="margin-left: 350px">
          		<?php 
          			foreach ($relatedEvents as $event):
          				$eventHref = JRoute::_("index.php?option=com_events&layout=item&catid={$event->catId}&id={$event->id_event}&Itemid={$this->ITEMID_EVENTS}");
          				list($day, $month, $year) = explode(' ', JHTML::date($event->date, JText::_('DATE_FORMAT_LC5')));
          		?>
		          	<div class="related_event">
		            	<div class="r_title"><p><a href="<?php echo $eventHref?>" title="<?php echo $event->title?>"><?php echo $event->title?></a><br/><?php echo $day?> <?php echo $month?> в <?php echo substr($event->time, 0, -3)?></p></div>
		            	<a href="<?php echo $eventHref?>" title="<?php echo $event->title?>">
		            		<?php echo JHTML::MegaImg('events/events', $event->image, null, null, $event->title, EVENT_IMG_RELATED);?>
		            	</a>
		            </div>
          		<?php endforeach;?>
          		<div class="clearer"></div>
          		</div> 
          	<?php endif;?>
</div>

<?php if (isset($g_x) && isset($g_y) && isset($g_zoom)):?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
<script type="text/javascript">
var createMap = function () {
	var latlng = new google.maps.LatLng(<?php echo $g_y?>, <?php echo $g_x?>);

	var content = '<p><b><?php echo str_replace(array('"', '\''), '', $item->title);?></b></p>';
		content += "<p><b>Адрес</b><br><?php echo $item->address?></p>";
		content += "<p><b>Телефон</b><br><?php echo str_replace(',', '</p><p>', $item->tel);?></p>";
	 
	var myOptions = {
		zoom: <?php echo $g_zoom?>,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};	

	var map = new google.maps.Map(document.getElementById("google_maps"), myOptions);	

	var marker = new google.maps.Marker({
	    position: latlng, 
	    map: map,
	    title:"<?php echo str_replace(array('"', '\''), '', $item->title);?>"
	});

	var infowindow = new google.maps.InfoWindow({ 
		content: content,
		size: new google.maps.Size(50, 50),
		position: latlng
	});

	google.maps.event.addListener(marker, 'click', function() {
	    infowindow.open(map, marker);
	});
}
</script>
<?php endif;?>