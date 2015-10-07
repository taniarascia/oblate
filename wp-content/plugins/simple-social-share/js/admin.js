function hide_show_tr_select_animation(){
	var radio_ss_style = jQuery('input:radio[name="s3_options[ss-select-style]"]');
	if ( radio_ss_style.is(':checked') && ( radio_ss_style.filter(':checked').val() == 'horizontal-with-count' || radio_ss_style.filter(':checked').val() == 'small-buttons' ) ) {
        jQuery("tr.tr-select-animation").hide();
    }
    else{
    	jQuery("tr.tr-select-animation").show();	
    }
}

jQuery(document).ready(function(){

	hide_show_tr_select_animation();
	jQuery('input:radio[name="s3_options[ss-select-style]"]').change(function(){
        /*if ( $(this).is(':checked') && ( $(this).val() == 'horizontal-with-count' || $(this).val() == 'small-buttons' ) ) {
	        jQuery("tr.tr-select-animation").hide();
	    }
	    else{
	    	jQuery("tr.tr-select-animation").show();	
	    }*/
	    hide_show_tr_select_animation();
    });

});


