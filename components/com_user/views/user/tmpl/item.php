<?php
	$model = $this->getModel();
	$catid = JRequest::getInt('catid');
	$id    = JRequest::getInt('id');
	$date  = JRequest::getVar('date');
	$item  = $model->getItem($id, $date);
	
	// устанавливаем мета-данные
	$doc = &JFactory::getDocument();
	$doc->setTitle("Anons.dp.ua | {$item->title}");
	$doc->setMetaData('keywords', "Anons.dp.ua | {$item->title}");
	$doc->setMetaData('description', $item->s_desc);
	$doc->setMetaData('image', JURI::base() . "images/sunny/events/events/{$item->image}");
	$doc->setMetaData('date', JHTML::date($item->date, JText::_('DATE_FORMAT_LC5')));
	
	list($day, $month, $year) = explode(' ', JHTML::date($item->date, JText::_('DATE_FORMAT_LC5')));
	$placeHref = JRoute::_("index.php?option=com_places&layout=item&id={$item->placeId}&Itemid={$this->ITEMID_PLACES}");
	
	$relatedEvents = $model->getRelatedEvents($item->date, $item->id_event);
	
	$item->web = (preg_match("/http/i", $item->web)) ? $item->web : "http://{$item->web}";	
?>
<div class="left upcoming">
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
    	<div class="event_day"><span class="e_day"><?php echo $day?></span><span class="e_month"><?php echo $month?></span></div>
            		<h1><p class="event_title"><?php echo $item->title?></p></h1>
            		<?php echo JHTML::MegaImg('events/events', $item->image, 'event_img', 'float: left', $item->title, EVENT_IMG_DESC);?>
            		<div class="event_info">
            			<p class="time">Начало <span><?php echo $day?> <?php echo $month?> <?php echo $year?></span> в <span><?php echo substr($item->time, 0, -3);?></span></p>
            			<h2><p class="place"><a href="<?php echo $placeHref?>"><?php echo ($item->placeDopTitle) ? $item->placeDopTitle : $item->placeTitle;?></a></p></h2>
            			
            			<?php if ($item->address):?>
            			<p class="titl">Адрес:</p> 
            			<p><?php echo $item->address?> &rarr; <a href="#" id="google_maps_show" data="" class="maps">показать на карте</a></p>
            			<?php endif;?>
            			
            			<?php if ($item->tel):?>
            			<p class="titl">Телефоны:</p> 
            			<p><?php echo str_replace(',', '</p><p>', $item->tel);?></p>
            			<?php endif;?>

            			<?php if ($item->web):?>
            			<p class="titl">Сайт:</p>
            			<noindex><p><a href="<?php echo $item->web; ?>" target="_blank"><?php echo $item->web?></a></p></noindex>
            			<?php endif;?>  
            			
            			<?php if ($item->email && $item->show_email):?>
            			<p class="titl">Email:</p>
            			<noindex><p><a href="mailto:<?php echo $item->email?>"><?php echo $item->email?></a></p></noindex>
            			<?php endif;?>
            			
	            		<div style="margin: 10px 0 0 0;">
	            			<span id="social_nets"></span>
	            			<span class="event_go button" id="event_go_<?php echo $id?>">Я пойду(10)</span>
	            		</div>          			
            			   			
            		</div>
            		
            		<div class="clearer"></div> 
            		
            		<div class="event_text">
						<?php echo $item->desc?>           		
            		</div>
            	</div>
          	
          	<?php if ($relatedEvents):?>
          		<div class="related_event_title">Другие события <?php echo JHTML::date($item->date, JText::_('DATE_FORMAT_LC5'));?></div>
          		<div style="margin-left: 350px">
          		<?php 
          			foreach ($relatedEvents as $event):
          			$eventHref = JRoute::_("index.php?option=com_events&layout=item&catid={$event->catId}&id={$event->id_event}&Itemid={$this->ITEMID_EVENTS}");
          		?>
		          	<div class="related_event">
		            	<div class="r_title"><p><a href="<?php echo $eventHref?>" title="<?php echo $event->title?>"><?php echo $event->title?></a><br/>Начало в <?php echo substr($event->time, 0, -3)?></p></div>
		            	<a href="<?php echo $eventHref?>" title="<?php echo $event->title?>">
		            		<?php echo JHTML::MegaImg('events/events', $event->image, null, null, $event->title, EVENT_IMG_RELATED);?>
		            	</a>
		            </div>
          		<?php endforeach;?>
          		<div class="clearer"></div> 
          		</div> 
          	<?php endif;?>
</div>
