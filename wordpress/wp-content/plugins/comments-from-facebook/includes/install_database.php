<?php 

/// Class for installing the plugin database

class wpdevart_comment_install_database{
	
	public $installed_options; 
	private $plugin_url;

	function __construct(){
		
		if(isset($params['plugin_url']))
			$this->plugin_url=$params['plugin_url'];
		else
			$this->plugin_url=trailingslashit(dirname(plugins_url('',__FILE__)));

		
		$this->installed_options=array(
			"wpdevart_comments_box"=>array(
								
				"wpdevart_comment_facebook_app_id"					=> '',
				"wpdevart_comments_box_order_type"					=> 'social',
				"wpdevart_comment_title_text"						=> 'Facebook Comments Box',
				"wpdevart_comment_title_text_color"					=> '#000000',
				"wpdevart_comment_title_text_font_size"				=> '20',
				"wpdevart_comment_title_text_font_famely"			=> 'Times New Roman,Times,Serif,Georgia',
				"wpdevart_comment_title_text_position"				=> 'left',
				"wpdevart_comments_box_show_in"						=>  '{"home":true,"post":true,"page":true}',	
				"wpdevart_comments_box_width"						=>  '100%',
				"wpdevart_comments_box_count_of_comments"			=>  '7',	
				"wpdevart_comments_box_locale"						=>  'en_US',
				"wpdevart_comments_box_include_sdk"					=> 'yes',				
			)			
		);
		
		
	}
	
	/*###################### Function for installing the database ##################*/	
	
	public function install_databese(){
		foreach( $this->installed_options as $key => $option ){
			if( get_option($key,FALSE) === FALSE ){
				add_option($key,$option);
			}
		}		
	}
}