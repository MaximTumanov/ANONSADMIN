<?php
	$model = $this->getModel();
	$text        = JRequest::getVar('text', 0);
	$id_category = JRequest::getInt('category', 0);
	$date_type   = JRequest::getInt('date');
	$date_from   = JRequest::getVar('date_from', 0);
	$date_to     = JRequest::getVar('date_to', 0);
	$title = "Поиск событий в Днепропетровске";
	
	
	// устанавливаем мета-данные
	$doc = &JFactory::getDocument();
	$doc->setTitle("Anons.dp.ua | {$title}");
	$doc->setMetaData('keywords', "Anons.dp.ua | {$title}");
	$doc->setMetaData('description', "Anons.dp.ua | {$title}");	
	
	$eventsList = $model->getEventsFromSearch($text, $id_category, $date_type, $date_from, $date_to);
?>
<div class="left upcoming">
	<div class="wrapp <?php if (!$eventsList) echo "no_result";?>">
		<h1 class="color-orang ttl"><?php echo $title?></h1>
		
		<div id="places_list">
			<?php 
			if ($eventsList):
				foreach ($eventsList as $event):
				list($day, $month, $year, $time) = explode(' ', JHTML::date($event->date, JText::_('DATE_FORMAT_LC5')));
				$href = JRoute::_("index.php?option=com_events&layout=item&id={$event->eventId}&date=" . substr($event->date, 0, 10) . "&Itemid=" . ITEMID_EVENTS);
			?>
				<div class="p_href" href="<?php echo $href;?>" title="<?php echo $event->title?>" alt="<?php echo $event->title?>">
					<div class="place_line" letter="<?php echo JString::strtoupper(JString::substr($event->title, 0, 1));?>">
						<div class="p_img"><?php echo JHTML::MegaImg('events/events', $event->image, '', '', $event->title, EVENT_IMG_ALL);?></div>
						<div class="p_info">
							<h2><?php echo $event->title?><?php echo JString::getWTF($event->wtf);?></h2>
							<p class="date"><span><?php echo $day?></span> <?php echo $month?> <?php echo $year?> <span><?php echo $time?></span></p>
							<?php echo $event->s_desc?>
						</div>
						<div class="p_cat">
							<?php 
								if ($event->icons):
									$icons = explode(',', $event->icons);
									foreach ($icons as $icon):
										$image = JHTML::MegaImg('events/category', $icon, '', '', '');
										echo "<span>{$image}</span>";
									endforeach;
								endif;
							?>
						</div>
						<div class="clearer"></div>
					</div>
				</div>
			<?php 
				endforeach;
			else:
				echo "<div class='no_result'>Упс :( <br /><br />Поиск не дал результатов</div>";
			endif;
			?>
		</div>
		
		<div id="pag_number"></div>
	</div>
</div>
<script type="text/javascript">
	var use_pagination = true,
		items_per_page = <?php echo ITEMS_PER_PAGE?>;
</script>