<?php
	$model = $this->getModel();
	$text        = JRequest::getVar('text', 0);
	$id_category = JRequest::getInt('category', 0);
	$date_type   = JRequest::getInt('date');
	$date_from   = JRequest::getVar('date_from', 0);
	$date_to     = JRequest::getVar('date_to', 0);

	// устанавливаем мета-данные
	$doc = &JFactory::getDocument();
	$doc->setTitle('Anons.dp.ua | Поиск мест в Днепропетровске');
	$doc->setMetaData('keywords', 'Anons.dp.ua | Поиск мест в Днепропетровске');
	$doc->setMetaData('description', 'Anons.dp.ua | Поиск мест в Днепропетровске');	
	
	$placesList = $model->getPlacesFromSearch($text, $id_category);
?>
<div class="left upcoming">
	<div class="wrapp">
		<h1 class="color-orang ttl">Поиск мест в Днепропетровске</h1>
		
		<div id="places_list">
			<?php 
			if ($placesList):
				foreach ($placesList as $place):
				$href = JRoute::_("index.php?option=com_places&layout=item&id={$place->placeId}&Itemid=" . ITEMID_PLACES);
			?>
				<div class="p_href" href="<?php echo $href;?>" title="<?php echo $place->title?>" alt="<?php echo $place->title?>">
					<div class="place_line" letter="<?php echo JString::strtoupper(JString::substr($place->title, 0, 1));?>">
						<div class="p_img">
						<?php echo ($place->image) 
								? JHTML::MegaImg('events/places', $place->image, '', '', $place->title, PLACE_IMG_ALL)
								: JHTML::MegaImg('events', 'no-image-small.gif', '', '');
						?>
						</div>
						<div class="p_info">
							<h2><?php echo ($place->dop_title) ? $place->dop_title : $place->title;?></h2>
							<?php if ($place->web):
								echo "<p><noindex><a target='_blank' href='{$place->web}'>{$place->web}</a></noindex></p>";
							endif;?>
							<?php if ($place->email && $place->show_email):
								echo "<p><noindex><a href='mailto:{$place->email}'>{$place->email}</a></noindex></p>";
							endif;?>							
							<?php if ($place->address):
								echo "<p>{$place->address}</p>";
							endif;?>
							<?php if ($place->tel):
								$place->tel = str_replace(',', ', ', $place->tel);
								echo "<p>{$place->tel}</p>";
							endif;?>
							
						</div>
						<div class="clearer"></div>
					</div>
				</div>
			<?php endforeach; ?>
			
			<?php if (count($placesList)):?>
				<div id="pag_number"></div>
			<?php endif;?>
			
			<?php else: ?>
				
							
			<?php endif;?>
		</div>
	</div>
</div>
<script type="text/javascript">
	var use_pagination = true,
		items_per_page = <?php echo ITEMS_PER_PAGE?>,
		MAKE_HIGHLIGHT = true,
		HIGHLIGHT_TEXT = "<?php echo $text?>";
</script>