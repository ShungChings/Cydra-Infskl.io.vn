<?php

class wpdevart_comment_setting{
	public static $list_of_animations=array('bounce','flash','pulse','rubberBand','shake','swing','tada','wobble','bounceIn','bounceInDown','bounceInLeft','bounceInRight','bounceInUp','fadeIn','fadeInDown','fadeInDownBig','fadeInLeft','fadeInLeftBig','fadeInRight','fadeInRightBig','fadeInUp','fadeInUpBig','flip','flipInX','flipInY','lightSpeedIn','rotateIn','rotateInDownLeft','rotateInDownRight','rotateInUpLeft','rotateInUpRight','rollIn','zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp');
	public static $id_for_iframe=0;
	
    /*############  Function for the animation type  ################*/	
	
	public static function get_animations_type_array($animation=''){
		if($animation=='' || $animation=='none')
			return '';
		if($animation=='random'){	
		
			return self::$list_of_animations[array_rand(self::$list_of_animations,1)];
		}
		return $animation;
	}
	
    /*############  Function for generating the Iframe  ################*/		
	
	public static function generete_iframe_by_array($params){
		self::$id_for_iframe++;
		$output_code='';
		//Iframe default parameters
		$defaults=array(		
			"iframe_id"							=> 'wpdevar_comment_'.self::$id_for_iframe,
			"facebook_app_id"					=> '',
			"order_type"						=> 'social',	
			"curent_url"						=> get_permalink(),			
			"title_text"						=> 'Comments',
			"title_text_color"					=> '#000000',
			"title_text_font_size"				=> '19',
			"title_text_font_famely"			=> 'Times New Roman,Times,Serif,Georgia',
			"title_text_position"				=> 'left',
			"width"								=>  '100%',
			"count_of_comments"					=>  '10',
			"locale"							=>  'en_US',	
				
		);
		$params=array_merge($defaults,$params);		
		$comment_box_array_query=array(
			'api_key'  				 			=> $params['facebook_app_id'],
			'href'  					 		=> $params['curent_url'],
			"numposts"							=> $params['count_of_comments'],
			"version"							=> '2.3',
			"sdk"								=> 'joey',						
			"locale"							=> $params['locale'],
		);
		$comment_box_src=add_query_arg($comment_box_array_query,'https://www.facebook.com/plugins/comments.php');
    $allowed_html = [
        'br'     => [],
        'em'     => [],
        'strong' => [],
    ];
		
$output_code.='<div id="'.esc_attr($params['iframe_id']).'" style="width:'.( (strpos($params['width'],'%')===false)?esc_attr($params['width']).'px':esc_attr($params['width'])).';text-align:'.esc_attr($params['title_text_position']).';">
		<span style="padding: 10px;font-size:'.esc_attr($params['title_text_font_size']).'px;font-family:'.esc_attr($params['title_text_font_famely']).';color:'.esc_attr($params['title_text_color']).';">'.wp_kses($params['title_text'],$allowed_html).'</span>
		<div class="fb-comments" data-href="'.esc_url($params['curent_url']).'" data-order-by="'.esc_attr($params['order_type']).'" data-numposts="'.esc_attr($params['count_of_comments']).'" data-width="'.esc_attr($params['width']).'" style="display:block;"></div></div>';
		$output_code.= '<style>#'.esc_attr($params['iframe_id']).' span,#'.esc_attr($params['iframe_id']).' iframe{'.( (strpos($params['width'],'%')===false)?'':'width:'.esc_attr($params['width']).' !important;').'} #'.esc_attr($params['iframe_id']).' iframe{max-height: 100% !important;}</style>';
		return $output_code;
	}

    /*############  Function for generating animation effects ################*/	
	
	public static function generete_animation_select($select_id='',$curent_effect='none'){
	?>
    <select class="pro_select" id="<?php echo esc_attr($select_id); ?>" name="<?php echo esc_attr($select_id); ?>">
   		  <option <?php selected('none',$curent_effect); ?> value="none">none</option>
          <option <?php selected('random',$curent_effect); ?> value="random">random</option>
        <optgroup label="Attention Seekers">
          <option <?php selected('bounce',$curent_effect); ?> value="bounce">bounce</option>
          <option <?php selected('flash',$curent_effect); ?> value="flash">flash</option>
          <option <?php selected('pulse',$curent_effect); ?> value="pulse">pulse</option>
          <option <?php selected('rubberBand',$curent_effect); ?> value="rubberBand">rubberBand</option>
          <option <?php selected('shake',$curent_effect); ?> value="shake">shake</option>
          <option <?php selected('swing',$curent_effect); ?> value="swing">swing</option>
          <option <?php selected('tada',$curent_effect); ?> value="tada">tada</option>
          <option <?php selected('wobble',$curent_effect); ?> value="wobble">wobble</option>
        </optgroup>

        <optgroup label="Bouncing Entrances">
          <option <?php selected('bounceIn',$curent_effect); ?> value="bounceIn">bounceIn</option>
          <option <?php selected('bounceInDown',$curent_effect); ?> value="bounceInDown">bounceInDown</option>
          <option <?php selected('bounceInLeft',$curent_effect); ?> value="bounceInLeft">bounceInLeft</option>
          <option <?php selected('bounceInRight',$curent_effect); ?> value="bounceInRight">bounceInRight</option>
          <option <?php selected('bounceInUp',$curent_effect); ?> value="bounceInUp">bounceInUp</option>
        </optgroup>

        <optgroup label="Fading Entrances">
          <option <?php selected('fadeIn',$curent_effect); ?> value="fadeIn">fadeIn</option>
          <option <?php selected('fadeInDown',$curent_effect); ?> value="fadeInDown">fadeInDown</option>
          <option <?php selected('fadeInDownBig',$curent_effect); ?> value="fadeInDownBig">fadeInDownBig</option>
          <option <?php selected('fadeInLeft',$curent_effect); ?> value="fadeInLeft">fadeInLeft</option>
          <option <?php selected('fadeInLeftBig',$curent_effect); ?> value="fadeInLeftBig">fadeInLeftBig</option>
          <option <?php selected('fadeInRight',$curent_effect); ?> value="fadeInRight">fadeInRight</option>
          <option <?php selected('fadeInRightBig',$curent_effect); ?> value="fadeInRightBig">fadeInRightBig</option>
          <option <?php selected('fadeInUp',$curent_effect); ?> value="fadeInUp">fadeInUp</option>
          <option <?php selected('fadeInUpBig',$curent_effect); ?> value="fadeInUpBig">fadeInUpBig</option>
        </optgroup>

        <optgroup label="Flippers">
          <option <?php selected('flip',$curent_effect); ?> value="flip">flip</option>
          <option <?php selected('flipInX',$curent_effect); ?> value="flipInX">flipInX</option>
          <option <?php selected('flipInY',$curent_effect); ?> value="flipInY">flipInY</option>
        </optgroup>

        <optgroup label="Lightspeed">
          <option <?php selected('lightSpeedIn',$curent_effect); ?> value="lightSpeedIn">lightSpeedIn</option>
        </optgroup>

        <optgroup label="Rotating Entrances">
          <option <?php selected('rotateIn',$curent_effect); ?> value="rotateIn">rotateIn</option>
          <option <?php selected('rotateInDownLeft',$curent_effect); ?> value="rotateInDownLeft">rotateInDownLeft</option>
          <option <?php selected('rotateInDownRight',$curent_effect); ?> value="rotateInDownRight">rotateInDownRight</option>
          <option <?php selected('rotateInUpLeft',$curent_effect); ?> value="rotateInUpLeft">rotateInUpLeft</option>
          <option <?php selected('rotateInUpRight',$curent_effect); ?> value="rotateInUpRight">rotateInUpRight</option>
        </optgroup>

        <optgroup label="Specials">
          
          <option <?php selected('rollIn',$curent_effect); ?> value="rollIn">rollIn</option>        
        </optgroup>

        <optgroup label="Zoom Entrances">
          <option <?php selected('zoomIn',$curent_effect); ?> value="zoomIn">zoomIn</option>
          <option <?php selected('zoomInDown',$curent_effect); ?> value="zoomInDown">zoomInDown</option>
          <option <?php selected('zoomInLeft',$curent_effect); ?> value="zoomInLeft">zoomInLeft</option>
          <option <?php selected('zoomInRight',$curent_effect); ?> value="zoomInRight">zoomInRight</option>
          <option <?php selected('zoomInUp',$curent_effect); ?> value="zoomInUp">zoomInUp</option>
        </optgroup>
      </select>
    <?php 
	}
	
}


 ?>