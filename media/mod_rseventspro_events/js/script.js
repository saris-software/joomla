function rsepro_select_type() {
	var value = jQuery('#jform_params_type').val();
	
	jQuery('#jform_params_from').parents('.control-group').css('display', 'none');
	jQuery('#jform_params_from').parents('li').css('display', 'none');
	jQuery('#jform_params_to').parents('.control-group').css('display', 'none');
	jQuery('#jform_params_to').parents('li').css('display', 'none');
	jQuery('#jform_params_archived').parents('.control-group').css('display', '');
	jQuery('#jform_params_archived').parents('li').css('display', '');
	
	if (value == 1) {
		jQuery('#jform_params_from').parents('.control-group').css('display', '');
		jQuery('#jform_params_from').parents('li').css('display', '');
		jQuery('#jform_params_to').parents('.control-group').css('display', '');
		jQuery('#jform_params_to').parents('li').css('display', '');
	} else if (value == 3) {
		jQuery('#jform_params_from').parents('.control-group').css('display', '');
		jQuery('#jform_params_from').parents('li').css('display', '');
		jQuery('#jform_params_to').parents('.control-group').css('display', '');
		jQuery('#jform_params_to').parents('li').css('display', '');
		jQuery('#jform_params_archived').parents('.control-group').css('display', 'none');
		jQuery('#jform_params_archived').parents('li').css('display', 'none');
	} else if (value == 8) {
		jQuery('#jform_params_from').parents('.control-group').css('display', '');
		jQuery('#jform_params_from').parents('li').css('display', '');
		jQuery('#jform_params_to').parents('.control-group').css('display', '');
		jQuery('#jform_params_to').parents('li').css('display', '');
	} else if (value == 9) {
		jQuery('#jform_params_from').parents('.control-group').css('display', '');
		jQuery('#jform_params_from').parents('li').css('display', '');
		jQuery('#jform_params_to').parents('.control-group').css('display', '');
		jQuery('#jform_params_to').parents('li').css('display', '');
	} else if (value == 10) {
		jQuery('#jform_params_from').parents('.control-group').css('display', '');
		jQuery('#jform_params_from').parents('li').css('display', '');
		jQuery('#jform_params_to').parents('.control-group').css('display', '');
		jQuery('#jform_params_to').parents('li').css('display', '');
	} else if (value == 12) {
		jQuery('#jform_params_from').parents('.control-group').css('display', '');
		jQuery('#jform_params_from').parents('li').css('display', '');
		jQuery('#jform_params_to').parents('.control-group').css('display', '');
		jQuery('#jform_params_to').parents('li').css('display', '');
	}
	
	if (value == 2 || value == 4 || value == 5 || value == 6 || value == 7 || value == 11) {
		jQuery('#jform_params_from').val('');
		jQuery('#jform_params_to').val('');
	}
}