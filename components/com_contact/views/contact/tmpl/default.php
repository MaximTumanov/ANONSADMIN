<?php
	$model = $this->getModel('Contact');
	$item  = $model->getItem();
?>
<script type="text/javascript">
	$(document).ready(function() {
		GUnload();
        var map = new GMap2(document.getElementById("google_maps"));
        var center = new GLatLng(<?php echo $item->g_y?>, <?php echo $item->g_x?>);
        map.setCenter(center, <?php echo $item->g_zoom?>);
        map.addControl(new GLargeMapControl());

        var center = map.getCenter();
        var marker = new GMarker(center, {draggable: false});
		
		GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml("<div class='gmMarker'><?php echo $item->text?></div>") }); 
		
		map.addOverlay(marker);

		if ($.cookie('email') && $.cookie('name')) {
			$('#message_send #email').removeClass('italic').val($.cookie('email'));
			$('#message_send #name').removeClass('italic').val($.cookie('name'));
		}
		
	});
</script>

<div class="content_left contakt">
	<h1><?php echo JText::_('CONTACTS_TITLE')?></h1>
	<div class="contakt_items">
		<div class="contakt_icon"><img alt="" src="<?php echo $this->baseurl?>/templates/template/images/icon_phone.png"/></div>
		<div class="contakt_icon_right">
			<div class="title"><?php echo JText::_('CONTACTS_TEL')?></div>
				<?php echo $item->tel?>
			</div>
		<div class="clearer"></div>
	</div>
	<div class="contakt_items">
		<div class="contakt_icon"><img alt="" src="<?php echo $this->baseurl?>/templates/template/images/icon_home.png"/></div>
		<div class="contakt_icon_right">
			<div class="title"><?php echo JText::_('CONTACTS_ADDRESS')?></div>
				<?php echo $item->adress?>
		</div>
		<div class="clearer"></div>
	</div>
	<div class="contakt_items">
		<div class="contakt_icon"><img alt="" src="<?php echo $this->baseurl?>/templates/template/images/icon_email_big.png"/></div>
		<div class="contakt_icon_right">
			<div class="title"><?php echo JText::_('CONTACTS_EMAIL')?></div>
				<a href="mailto:<?php echo $item->email?>"><?php echo $item->email?></a>
			</div>
		<div class="clearer"></div>
	</div>
</div>

<div class="content_right contakt_map">
	<div class="fotogalery_slider">
		<div class="fotogalery_slider_top"></div>
		<div class="fotogalery_slider_descript">
			<h2><?php echo JText::_('CONTACTS_MAP')?></h2>
			<img alt="" src="<?php echo $this->baseurl?>/templates/template/images/icon_map.png" class="icon_map"/>
		</div>
		<div id="google_maps"></div>
		<div class="fotogalery_slider_bottom"></div>
	</div>
</div>

<div class="clearer"></div>

<div class="form_contact">
	<h1><?php echo JText::_('CONTACTS_FEEDBACK')?></h1>
	<form action="index2.php?option=com_contact&task=sendMessage" method="post" id="message_send">
		<p>
			<input type="text" class="italic input" value="<?php echo JText::_('CONTACTS_NAME')?>" id="name" name="name" defaultTxt="<?php echo JText::_('CONTACTS_NAME')?>" defaultClass="italic" />
			<input tyle="text" class="italic input" value="e-mail" id="email" name="email" defaultTxt="e-mail" defaultClass="italic"/>
		</p>
		<p><textarea class="italic textarea" id="text" name="text" defaultTxt="<?php echo JText::_('CONTACTS_TEXT')?>" defaultClass="italic"><?php echo JText::_('CONTACTS_TEXT')?></textarea></p>
		<p>
			<input type="submit" name="" id="message_send_but" value="<?php echo JText::_('CONTACTS_SEND')?>" class="send_submit">
			<span class="message_text"></span>
		</p>
		<input type="hidden" name="admin_email" value="<?php echo $item->email?>" />
	</form>
</div>

<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=<?php echo $item->g_key?>" type="text/javascript"></script>
