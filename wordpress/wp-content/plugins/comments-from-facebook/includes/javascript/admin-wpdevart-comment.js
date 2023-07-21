jQuery(document).ready(function(e) {
    jQuery('.pro_select,.pro_input,.disabled_picker').click(function(){alert("If you want to use this feature upgrade to Facebook Comments Pro")});
	  jQuery('.pro_checkbox').mousedown(function(){alert("If you want to use this feature upgrade to Facebook Comments Pro")})
});
/*ADMIN CUSTOMIZE SETTINGS OPEN OR HIDE*/
function get_array_of_opened_elements(){
	var kk=0;
	var array_of_activ_elements=new Array();
	jQuery('#wpdevart_comment_page .main_parametrs_group_div').each(function(index, element) {		
        if(!jQuery(this).hasClass('closed_params')){			
			array_of_activ_elements[kk]=jQuery('#wpdevart_comment_page .main_parametrs_group_div').index(this);
			kk++;
		}
    });
	return array_of_activ_elements;
}

jQuery(document).ready(function(e) {
	/*SET CLOR PICKERS*/
	jQuery('.color_option').wpColorPicker()	

	var askofen=0;
	/*############ Other section Save click ################*/
	jQuery(".save_section_parametrs").click(function(){		
		
		jQuery('.wpdevart_comment_hidden_parametr').each(function(index, element) {
		   generete_input_values(this)
		});
		var wpdevart_comment_curent_section=jQuery(this).attr('id');
		jQuery.each( wpdevart_comment_all_parametrs[wpdevart_comment_curent_section], function( key, value ) {
		   wpdevart_comment_all_parametrs[wpdevart_comment_curent_section][key] =jQuery('#'+key).val() 
		});
		var wpdevart_comment_date_for_post=wpdevart_comment_all_parametrs;
		wpdevart_comment_all_parametrs[wpdevart_comment_curent_section]['curent_page']=wpdevart_comment_curent_section;
		wpdevart_comment_all_parametrs[wpdevart_comment_curent_section]['wpdevart_comment_options_nonce']=jQuery('#wpdevart_comment_options_nonce').val();
		
		
		jQuery('#'+wpdevart_comment_curent_section).addClass('padding_loading');
		jQuery('#'+wpdevart_comment_curent_section).prop('disabled', true);		
		jQuery('#'+wpdevart_comment_curent_section+' .saving_in_progress').css('display','inline-block');
		
		askofen++;
		jQuery.ajax({
					type:'POST',
					url: wpdevart_comment_ajaxurl+'?action=wpdevart_comment_page_save',
					data: wpdevart_comment_all_parametrs[wpdevart_comment_curent_section],
				}).done(function(date) {
					jQuery('#'+wpdevart_comment_curent_section+' .saving_in_progress').css('display','none');
					if(date==wpdevart_comment_parametrs_sucsses_saved){							
						jQuery('#'+wpdevart_comment_curent_section+' .sucsses_save').css('display','inline-block');
						setTimeout(function(){wpdevart_comment_clickable=1;jQuery('#'+wpdevart_comment_curent_section+' .sucsses_save').hide('fast');jQuery('#'+wpdevart_comment_curent_section+'.save_section_parametrs').removeClass('padding_loading');jQuery('#'+wpdevart_comment_curent_section).prop('disabled', false);},1800);
						askofen--;
					}
					else{
						jQuery('#'+wpdevart_comment_curent_section+' .error_in_saving').css('display','inline-block');
						jQuery('#'+wpdevart_comment_curent_section).parent().find('.error_massage').eq(0).html(date);
						
					}
		});
	});

});


function generete_input_values(hidden_element){
	var element_array = {};
	jQuery(hidden_element).parent().find('input[type=checkbox]').each(function(index, element) {						
		element_array[jQuery(this).val()]=jQuery(this).prop('checked');
	});
	jQuery(hidden_element).val(JSON.stringify(element_array));
}