jQuery(function(){
	jQuery('#addFile').click(function(){
		var i       = jQuery('table.files').size();
		var options = jQuery('#paramsbanner_1').html();

		var table = '<table cellspacing="1" width="100%" class="paramlist admintable files">';
			table += '<tr><td width="40%" class="paramlist_key">Баннер #' + (i+2) + '</td>';
			table += '<td class="paramlist_value">';
			table += '<select class="inputbox" id="paramsbanner_3" name="params[banner_' + (i+2) + ']">';
			table += options;
			table + '</select></td></tr></tbody></table>';
		jQuery('div.jpane-slider').css({height: 25*(i+1) + 150});
		jQuery('#dynamic').append(table);
	});
});