<?php
	$model  = $this->getModel();
	$item   = $model->getItem();
	$email  = $item->email;
	$g_key  = $item->g_key;
	$text   = $item->text;
	$tel    = $item->tel;
	$adress = $item->adress;
?>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=<?php echo $g_key?>" type="text/javascript"></script>
<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0" type="text/javascript"></script>
<!--<script src="http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js" type="text/javascript"></script>
<style type="text/css">
@import url("http://www.google.com/uds/css/gsearch.css");
@import url("http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css");
</style>-->
<script type="text/javascript">
	jQuery(document).ready(function() {
		GUnload();
        var map = new GMap2(document.getElementById("google_maps"));
        var center = new GLatLng(<?php echo $item->g_y?>, <?php echo $item->g_x?>);
        map.setCenter(center, <?php echo $item->g_zoom?>);
        map.addControl(new GLargeMapControl());
        //map.addControl(new GMapTypeControl(), new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(10,20)));
		//map.addControl(new google.maps.LocalSearch(), new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10,20)));

        var center = map.getCenter();
        var marker = new GMarker(center, {draggable: true});
        jQuery('#g_x').val(marker.getPoint().x);
        jQuery('#g_y').val(marker.getPoint().y);
        jQuery('#g_zoom').val(map.getZoom());
          GEvent.addListener(marker, "dragstart", function() {
              map.closeInfoWindow();
          });
          GEvent.addListener(marker, "dragend", function() {
              jQuery('#g_x').val(this.getPoint().x);
              jQuery('#g_y').val(this.getPoint().y);
              jQuery('#g_zoom').val(map.getZoom());
          });

        map.addOverlay(marker);
});
</script>
<?php if( !$item->g_key ) echo '<p style="color:#c00000">Необходимо указать Google API-Key. <a href="http://code.google.com/apis/maps/signup.html" target="_blank">Подробнее &rarr;</a></p>';?>
<form action="index.php?option=com_contact&view=contact" method="post" name="adminForm">
  <fieldset class="adminform">
  <table width="100%">
  	<tr>
  		<td width="50%" valign="top">
		    <table class="admintable">
		      <tbody>
		      
	
		        <tr>
		          <td class="key"><label for="g_key"><?php echo JText::_('API-Key');?></label></td>
		          <td><input type="text" value="<?php echo $g_key?>" maxlength="255" size="60"  name="g_key" id="g_key" class="inputbox"></td>
		        </tr>
		
		        <tr>
		          <td class="key"><label for="g_x"><?php echo JText::_('x');?></label></td>
		          <td><input type="text" value="<?php echo $g_x?>" maxlength="255" size="60"  name="g_x" id="g_x" class="inputbox" readonly="readonly"></td>
		        </tr>
		 
		        <tr>
		          <td class="key"><label for="g_y"><?php echo JText::_('y');?></label></td>
		          <td><input type="text" value="<?php echo $g_y?>" maxlength="255" size="60"  name="g_y" id="g_y" class="inputbox" readonly="readonly"></td>
		        </tr>
		
		        <tr>
		          <td class="key"><label for="g_zoom"><?php echo JText::_('Приближение по умолчанию');?></label></td>
		          <td><input type="text" value="<?php echo $g_zoom?>" maxlength="255" size="60"  name="g_zoom" id="g_zoom" class="inputbox" readonly="readonly"></td>
		        </tr>
		
		        <tr>
		          <td class="key"><label for="text"><?php echo JText::_('Текст для маркера');?></label></td>
		          <td><textarea id="text" name="text" style="width:258px; height:90px"><?php echo $text;?></textarea></td>
		        </tr>
		
		      </tbody>
		    </table>
		  </td>
		  <td width="50%" valign="top">
		  	<table class="admintable">
		  	
	        <tr>
	          <td class="key"><label for="email"><?php echo JText::_('Email');?></label></td>
	          <td><input type="text" value="<?php echo $email?>" name="email" id="email" size="60" class="inputbox"></td>
	        </tr>

	        <tr>
	          <td class="key"><label for="tel"><?php echo JText::_('Телефоны');?></label></td>
	          <td><textarea id="tel" name="tel" style="width:258px; height:70px"><?php echo $tel;?></textarea></td>
	        </tr>

	        <tr>
	          <td class="key"><label for="adress"><?php echo JText::_('Адреса');?></label></td>
	          <td><textarea id="adress" name="adress" style="width:258px; height:90px"><?php echo $adress;?></textarea></td>
	        </tr>		  	
		  	
		  	</table>
		  </td>
	</tr>
  </table>
  </fieldset>
  <input type="hidden" name="option" value="com_contact" />
  <input type="hidden" name="boxchecked" value="0" />
  <input type="hidden" name="controller" value="contact" />
  <input type="hidden" name="task" value="" />
</form>
<div id="google_maps" style="width: 100%; height: 600px;"></div>