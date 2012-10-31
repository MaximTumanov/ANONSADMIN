<?php 
	$model = $this->getModel();
	$date = JRequest::getVar('day');
	$eventsList = $model->getEventsList($date);

	list($day, $month, $year) = explode(' ', JHTML::date($date, JText::_('DATE_FORMAT_LC5')));
	$day_title = "{$day} {$month} {$year}";
	// устанавливаем мета-данные
	$doc = &JFactory::getDocument();
	$doc->setTitle("Anons.dp.ua | События Днепропетровска {$day_title}");
	$doc->setMetaData("keywords", "Anons.dp.ua | События Днепропетровска {$day_title}");
	$doc->setMetaData("description", "Anons.dp.ua | События Днепропетровска {$day_title}");
?>

<div class="left upcoming">
	<div class="wrapp">
		<h1 class="color-orang ttl">События Днепропетровска <?php echo $day_title?></h1>
		
		<div id="places_list">
			<?php 
				foreach ($eventsList as $event):
				list($day, $month, $year, $time) = explode(' ', JHTML::date($event->date, JText::_('DATE_FORMAT_LC5')));
				$href = JRoute::_("index.php?option=com_events&layout=item&id={$event->eventId}&date={$date}&Itemid=" . ITEMID_EVENTS);
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
			<?php endforeach;?>
		</div>
		
		<div id="pag_number"></div>
	</div>
</div>
<script type="text/javascript">
	var use_pagination = true,
		items_per_page = <?php echo ITEMS_PER_PAGE?>;
</script>