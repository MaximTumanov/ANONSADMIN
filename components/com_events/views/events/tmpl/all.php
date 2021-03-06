<?php 
	$model = $this->getModel();
	$eventsList = $model->getEventsList();
	$title = "Ближайшие события Днепропетровска";
	
	// устанавливаем мета-данные
	$doc = &JFactory::getDocument();
	$doc->setTitle("Anons.dp.ua | {$title}");
	$doc->setMetaData('keywords', "Anons.dp.ua | {$title}");
	$doc->setMetaData('description', "Anons.dp.ua | {$title}");
?>

<div class="left upcoming">
	<div class="wrapp">
		<h1 class="color-orang ttl"><?php echo $title?></h1>
		
		<div id="places_list">
			<?php 
				foreach ($eventsList as $key => $event):
				list($day, $month, $year, $time) = explode(' ', JHTML::date($event->date, JText::_('DATE_FORMAT_LC5')));
				$href = JRoute::_("index.php?option=com_events&layout=item&id={$event->eventId}&Itemid=" . ITEMID_EVENTS);
			?>
				<div class="p_href <?php echo ($key%2) ? '' : 'asm' ;?>" href="<?php echo $href;?>" title="<?php echo $event->title?>" alt="<?php echo $event->title?>">
					<div class="place_line" letter="<?php echo JString::strtoupper(JString::substr($event->title, 0, 1));?>">
						<div class="p_img"><?php echo JHTML::MegaImg('events/events', $event->image, '', '', $event->title, EVENT_IMG_ALL);?></div>
						<div class="p_info">
							<h2><?php echo $event->title?><?php echo JString::getWTF($event->wtf);?></h2>
							<p class="date"><span><?php echo $day?></span> <?php echo $month?> <span><?php echo $time?></span></p>
							<div class="ghj"><?php echo JString::cropstr($event->s_desc, 25)?></div>
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
		<?php if (count($eventsList) > ITEMS_PER_PAGE):?>
			<div id="pag_number"></div>
		<?php endif;?>
	</div>
</div>
<script type="text/javascript">
	var use_pagination = true,
		items_per_page = <?php echo ITEMS_PER_PAGE?>;
</script>