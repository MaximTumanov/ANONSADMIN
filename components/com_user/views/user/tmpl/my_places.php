<?php 
	if ($_COOKIE["anons_dp_ua"]) {

		$model = $this->getModel();
		$data = $_COOKIE["anons_dp_ua"];
		$data = explode('|', $data);
		$id_user = (int) substr($data[0], 32);
		
		$placesList = $model->getUserPlaces($id_user);

	// устанавливаем мета-данные
	$title = "Мои любимые места";
	$doc = &JFactory::getDocument();
	$doc->setTitle("Anons.dp.ua | Личный кабинет | {$title}");
	
?>
<div class="left upcoming">
	<div class="wrapp">
		<!-- noindex><div id="toggle"><span class="non_act" title="Переключиться на список">список</span> -o- <span class="act" title="Переключиться на карту">карта</span></div></noindex !-->
		<h1 class="color-orang ttl">Мои любимые места Днепропетровска</h1>
		
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

<?php } else { ?>
<div class="left upcoming">
	<div class="wrapp" style="height: 510px">
		<h1 class="color-orang ttl">Необходимо авторизироваться!</h1>
	</div>
</div>
<?php }?>