<?php
	$model = $this->getModel();
	$placesList = $model->getPlacesList();

	// устанавливаем мета-данные
	$doc = &JFactory::getDocument();
	$doc->setTitle('Anons.dp.ua | Места Днепропетровска');
	$doc->setMetaData('keywords', 'Anons.dp.ua, Места Днепропетровска');
	$doc->setMetaData('description', 'Anons.dp.ua | Места Днепропетровска');
	
	$letters = explode(',', $model->getPlacesLetters());
	$arrLet = array(
		'ru' => array(
			'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К', 
			'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 
			'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Э', 'Ю', 'Я'
		),
		'en' => array(
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
			'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
		)
	)
?>
<div class="left upcoming">
	<div class="wrapp">
		<!-- noindex><div id="toggle"><span class="non_act" title="Переключиться на список">список</span> -o- <span class="act" title="Переключиться на карту">карта</span></div></noindex !-->
		<h1 class="color-orang ttl">Места Днепропетровска</h1>
		<ul class="letters">
			<noindex>
			<?php 
				foreach($arrLet['ru'] as $ru):
					echo (in_array($ru, $letters)) ? "<li class='hse l-{$ru}'><a href='#{$ru}'>{$ru}</a></li>" : "<li>{$ru}</li>";
				endforeach;
			?>
			</noindex>
		</ul>
		<div class="clearer"></div>
		<ul class="letters">
			<noindex>
			<?php 
				foreach($arrLet['en'] as $en):
					echo (in_array($en, $letters)) ? "<li class='hse l-{$en}'><a href='#{$en}'>{$en}</a></li>" : "<li>{$en}</li>";
				endforeach;
			?>
			<li class='hse activ '><a href='#ВСЕ'>ВСЕ</a></li>
			</noindex>
		</ul>
		<div class="clearer"></div>
		
		<div id="places_list" class="top-20px">
			<?php 
				foreach ($placesList as $key => $place):
				$href = JRoute::_("index.php?option=com_places&layout=item&id={$place->id_place}&Itemid=" . ITEMID_PLACES);
			?>
				<div class="p_href <?php echo ($key%2) ? '' : 'asm' ;?>" href="<?php echo $href?>">
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
						<div class="p_cat"></div>
						<div class="clearer"></div>
					</div>
				</div>
			<?php endforeach;?>
		</div>
		
		<?php if (count($placesList) > ITEMS_PER_PAGE):?>
			<div id="pag_number"></div>
		<?php endif;?>
	</div>
</div>
<script type="text/javascript">
	var use_pagination = true,
		items_per_page = <?php echo ITEMS_PER_PAGE?>;
</script>