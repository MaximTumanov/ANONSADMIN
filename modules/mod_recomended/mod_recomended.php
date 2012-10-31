<?php 
	$count = $params->get('count');
	$db = JFactory::getDBO();
	$q = "SELECT 
			event.*, 
			place.title as place_title,
			place.dop_title as place_dop_title,
			xref.id_category as catId,
			MIN(dates.date) as `date`
		  FROM `#__events` as `event` 
		  	JOIN `#__events_dates` as `dates` ON dates.id_event = event.id_event
		  	JOIN `#__events_xref` as `xref` ON event.id_event = xref.id_event
		  	JOIN `#__places` as `place` ON xref.id_place = place.id_place
		  WHERE event.vip = '1' AND dates.type = '3' AND dates.date >= DATE(NOW()) AND event.published = '1' 
		    GROUP BY event.id_event ORDER BY RAND() LIMIT 0, {$count}";
	$db->setQuery($q);
	$eventList = $db->loadObjectList();
	$ids = array();
?>
<div class="left recommended">
  <div class="wrp">
	<ul>
		<li>
	    	<h2 class="title fiolet">Рекомендуем посетить</h2>
				<div class="box">
				<?php 
					foreach ($eventList as $event):
					list($day, $month, $year, $time) = explode(' ', JHTML::date($event->date, JText::_('DATE_FORMAT_LC5')));
					$eventHref = JRoute::_("index.php?option=com_events&layout=item&catid={$event->catId}&id={$event->id_event}&Itemid=" . ITEMID_EVENTS);
					$ids[] = $event->id_event;
				?>
				  <div class="left main_event">
					<div class="comment-column clear">
						<div class="comment justify pointer" href="<?php echo $eventHref?>" title="<?php echo $event->title?>">
							<div class="who clear">
						  		<a href="<?php echo $eventHref?>" title="<?php echo $event->title?>"><?php echo JHTML::MegaImg('events/events', $event->image, 'img_small', 'float: left', $event->title, EVENT_IMG_FRONT);?></a>
						 		<p class="date"><span><?php echo $day?></span> <?php echo $month?> <span><?php echo $time?></span></p>
						 		<p class="href"><a class="block" title="<?php echo $event->title?>" href="<?php echo $eventHref?>"><?php echo $event->title?></a></p>
						 		<u><?php echo ($event->place_dop_title) ? "{$event->place_dop_title}" : $event->place_title;?></u>
							  			    	
								<?php echo JString::cropstr($event->s_desc, 30);?>
							</div>			    	
						</div>
					</div>
				  </div>	
				<?php endforeach;?>
				<div id="other_recomended"></div>
				<div class="clearer"></div>
				</div>
	 						
	 			<dl class="dotted">
	 				<dd>
	                  	<i class="icons details"></i>
	                   	<a href="#anons" id="get_all_recomended" ids="<?php echo implode(',', $ids);?>" class="date color-orang">Остальные события</a>
	                </dd>
	            </dl>
		</li>
	</ul>
  </div>
</div> 