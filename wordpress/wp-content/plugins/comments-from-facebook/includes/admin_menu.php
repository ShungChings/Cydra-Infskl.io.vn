<?php 

    /*############  Admin Menu Class ################*/

class wpdevart_comment_admin_menu{
	
	private $menu_name;
	private $databese_parametrs;
	private $plugin_url;
	private $text_parametrs;

	/*###################### Construct parameters function ##################*/	
	
	function __construct($param){
		
		$this->menu_name=$param['menu_name'];
		$this->databese_parametrs=$param['databese_parametrs'];
		if(isset($params['plugin_url']))
			$this->plugin_url=$params['plugin_url'];
		else
			$this->plugin_url=trailingslashit(dirname(plugins_url('',__FILE__)));
		add_action( 'wp_ajax_wpdevart_comment_page_save', array($this,'save_in_databese') );
		add_action( 'add_meta_boxes', array($this,'wpdevart_comment_add_meta_box') );
		add_action( 'save_post', array($this,'wpdevar_save_post') );
		$this->text_parametrs['parametrs_sucsses_saved'] = "Saved";
		$this->text_parametrs['error_in_saving'] = "Error saving";
		$this->text_parametrs['authorize_problem']  = "Authorize problem";
	}

	/*###################### The Meta Box function ##################*/	
	
	public function wpdevart_comment_add_meta_box() {

		$post_types = array( 'post', 'page' );

		foreach ( $post_types as $post_type ) {
	
			add_meta_box('myplugin_sectionid',	'Disable WpDevArt Facebook comment on this page/post', array($this,'generete_html_for_wpdevart_comment_box'), $post_type );
		}
	}
	
    /*############  Function for generating HTML  ################*/
	
	public function generete_html_for_wpdevart_comment_box($post){
		// Add field that we can check later.
		wp_nonce_field( 'wpdevar_save_post', 'wpdevart_facebook_meta_box_nonce' );
		/*
		 * Use get_post_meta() to retrieve the existing value
		 * From database, use the value for the form.
		 */
		$value = get_post_meta( $post->ID, '_disabel_wpdevart_facebook_comment', true );
		echo '<label for="wpdevart_disable_field">';
		echo  'Wpdevart Facebook comment &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		echo '</label> ';
		echo '<select id="wpdevart_disable_field" name="wpdevart_disable_field"><option value="enable">Enable</option><option '.(($value=='disable')?'selected="selected"':'').' value="disable">Disable</option></select>';
	}
	
	/*###################### Function for saving the post ##################*/	
	
	function wpdevar_save_post( $post_id ) {
		if ( ! isset( $_POST['wpdevart_facebook_meta_box_nonce'] ) ) {	return;	}
		if ( ! wp_verify_nonce( $_POST['wpdevart_facebook_meta_box_nonce'], 'wpdevar_save_post' ) ) {	return;	}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {	return;	}
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		if ( ! isset( $_POST['wpdevart_disable_field'] ) ) {
			return;
		}
		$my_data = sanitize_text_field( $_POST['wpdevart_disable_field'] );
		update_post_meta( $post_id, '_disabel_wpdevart_facebook_comment', $my_data );
	}

	/*############################ Function for adding the plugin admin menu pages ###################################*/

	public function create_menu(){
		global $submenu;
		$sub_men_cap=str_replace( ' ', '-', $this->menu_name);
		$main_page 	 	  = add_menu_page( esc_attr($this->menu_name), esc_attr($this->menu_name), 'manage_options', esc_attr(str_replace( ' ', '-', $this->menu_name)), array($this, 'main_menu_function'),esc_url($this->plugin_url.'images/facebook_menu_icon.png'));
		$page_wpdevart_comment	  =	add_submenu_page(esc_attr($this->menu_name),  esc_attr($this->menu_name),  esc_attr($this->menu_name), 'manage_options', esc_attr(str_replace( ' ', '-', $this->menu_name)), array($this, 'main_menu_function'));
		$page_wpdevart_comment	  = add_submenu_page( esc_attr(str_replace( ' ', '-', $this->menu_name)), 'Featured Plugins', 'Featured Plugins', 'manage_options', 'wpdevart-comment-featured-plugins', array($this, 'featured_plugins'));
		$page_hire				  = add_submenu_page( esc_attr(str_replace( ' ', '-', $this->menu_name)), 'Hire an Expert', '<span style="color:#00ff66" >Hire an Expert</span>', 'manage_options', 'wpdevart-comment-hire-expert', array($this, 'hire_expert'));

		add_action('admin_print_styles-' .$main_page, array($this,'menu_requeried_scripts'));
		add_action('admin_print_styles-' .$page_wpdevart_comment, array($this,'menu_requeried_scripts'));
		add_action('admin_print_styles-' .$page_hire, array($this,'menu_hire_expert_requeried_scripts'));

		if(isset($submenu[$sub_men_cap]))
			add_submenu_page( $sub_men_cap, "Support or Any Ideas?", "<span style='color:#00ff66' >Support or Any Ideas?</span>", 'manage_options',"wpdevart_fbcomments_any_ideas",array($this, 'any_ideas'),155);
		if(isset($submenu[$sub_men_cap]))
			$submenu[$sub_men_cap][3][2]=wpdevart_comment_support_url;
	}
	
	/*###################### Any Ideas function ##################*/		
	
	public function any_ideas(){
		
	}
	
	/*###################### The required scripts function ##################*/	
	
	public function menu_requeried_scripts(){
		wp_enqueue_script('wp-color-picker');		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'comment-box-admin-script' );
		wp_localize_script('comment-box-admin-script','wpdevart_comment_all_parametrs',$this->databese_parametrs);
		wp_enqueue_style('comment-box-admin-style');
	}
	
	public function menu_hire_expert_requeried_scripts(){	
		wp_enqueue_style("wpdevart_lightbox_hire_expert",$this->plugin_url.'includes/style/hire_expert.css');
	}
	
	/*###################### Function for generating parameters  ##################*/		
	
	private function generete_parametrs($page_name){
		$page_parametrs=array();
		if(isset($this->databese_parametrs[$page_name])){
			foreach($this->databese_parametrs[$page_name] as $key => $value){
				$page_parametrs[$key]=get_option($key,$value);
			}
			return $page_parametrs;
		}
		return NULL;
		
	}
	
	/*###################### The database function ##################*/	
	
	public function save_in_databese(){
		$kk=1;	
		if(isset($_POST['wpdevart_comment_options_nonce']) && wp_verify_nonce( $_POST['wpdevart_comment_options_nonce'],'wpdevart_comment_options_nonce')){
			foreach($this->databese_parametrs[$_POST['curent_page']] as $key => $value){
				if(isset($_POST[$key]))
					update_option($key,sanitize_text_field($_POST[$key]));
				else{
					$kk=0;
					printf($this->text_parametrs['error_in_saving'],esc_html($key));
				}
			}	
		}
		else{		
			die(esc_html($this->text_parametrs['authorize_problem']));
		}
		if($kk==0){
			exit;
		}
		die(esc_html($this->text_parametrs['parametrs_sucsses_saved']));
	}
	
	/*###################### The main menu function ##################*/		
	
	public function main_menu_function(){
		?>
        <script>
        var wpdevart_comment_ajaxurl="<?php echo admin_url( 'admin-ajax.php'); ?>";
		var wpdevart_comment_plugin_url="<?php echo esc_url($this->plugin_url); ?>";
		var wpdevart_comment_parametrs_sucsses_saved="<?php echo esc_html($this->text_parametrs['parametrs_sucsses_saved']); ?>";
		//var wpdevart_comment_all_parametrs = <?php /* echo json_encode($this->databese_parametrs); */?>;
        </script>
		<div class="wpdevart_plugins_header div-for-clear">
			<div class="wpdevart_plugins_get_pro div-for-clear">
				<div class="wpdevart_plugins_get_pro_info">
					<h3>WpDevArt Facebook Comments Premium</h3>
					<p>Powerful and Customizable Facebook Comments</p>
				</div>
					<a target="blank" href="https://wpdevart.com/wordpress-facebook-comments-plugin/" class="wpdevart_upgrade">Upgrade</a>
			</div>
			<a target="blank" href="<?php echo wpdevart_comment_support_url; ?>" class="wpdevart_support">Have any Questions? Get quick a support!</a>
		</div>  
      
	<br>
     
        <div class="wp-table right_margin">
        
        
            <div class="left_sections">
				<?php
                $this->generete_wpdevart_main_section($this->generete_parametrs('wpdevart_comments_box'));	
                ?>
            </div>
            <div class="right_sections">
                <div class="main_parametrs_group_div">
                    <div class="head_panel_div">                    
                    	<span class="title_parametrs_group">Facebook Comments plugin user manual</span>       
                    </div>
                    <div class="inside_information_div">
                        <table class="wp-list-table widefat fixed posts section_parametrs_table">                            
                            <tbody> 
                                <tr>
                                    <td>
                                        <div class="pea_admin_box">
                                        
                                            <p>Here's the short user manual that should help you to insert Facebook Comments Box into your website.</p>
                                           <p style="font-weight:bolder"><span style="color:#6b56f9;">APP ID</span> - you can create your App Id on this page - <a style="color:#0073aa" target="_blank" href="https://developers.facebook.com/apps">https://developers.facebook.com/apps.</a>
Also, here is another tutorial(from other source) of creating App Id, you can check it - <a style="color:#0073aa" target="_blank" href="https://wpdevart.com/how-to-get-an-app-id-and-secret-key-from-facebook/">https://wpdevart.com/how-to-get-an-app-id-and-secret-key-from-facebook/</a>.</p>
                                            <p>If you select the option "Display comments on" - Home, Post, Page, then the Facebook Comments box will be added on every page/post of your website. </p> 
                                            <p>Also, you can insert the Facebook Comments box manually in any page/post or even in PHP code using the plugin shortcode. You can disable comments on single pages or posts as well by using the disable option(find the p below posts/pages).</p>
                                            
                                            <p><strong>Here's an example of using the Facebook comments shortcode in posts, pages:</strong></p>
                                            <p><code>[wpdevart_facebook_comment curent_url="http://yourdomain.com/page-url" title_text="Facebook Comment" order_type="social" title_text_color="#000000" title_text_font_size="22" title_text_font_famely="monospace" title_text_position="left" width="100%" bg_color="#d4d4d4" animation_effect="random"  count_of_comments="2" ]</code></p>
                                            
                                            <p><strong>Here is an example of using the Facebook comments box shortcode in PHP code:</strong></p>
                                            <p><code>&lt;?php echo do_shortcode('[wpdevart_facebook_comment curent_url="http://yourdomain.com/page-url" order_type="social" title_text="Facebook Comment" title_text_color="#000000" title_text_font_size="22" title_text_font_famely="monospace" title_text_position="left" width="100%" bg_color="#d4d4d4" animation_effect="random"  count_of_comments="3" ]'); ?&gt;</code></p>
                                            
                                            <p><strong>Here are explanation of Facebook comments shortcode attributes.</strong></p>
                                            
                                            <p><strong>Curent_url</strong> - Type the page URL where you need to display the Facebook comments box </p>
                                            <p><strong>Title_text</strong> - Type here the Facebook comments box title</p>                                            
                                            <p><strong>Order_type</strong> - Choose the order type of the comments. The order to use when displaying comments. Can be "social", "reverse_time", or "time". </p>
                                            <p><strong>Title_text_color</strong> - Select the title text color of the Facebook comments box</p>
                                            <p><strong>Title_text_font_size</strong> - Type the title font-size(px) of the Facebook comments box</p>
                                            <p><strong>Title_text_font_famely</strong> - Select the title font family of the Facebook comments box</p>
                                            <p><strong>Title_text_position</strong> - Select the Facebook comments box title position</p>
                                            <p><strong>Width</strong> - Type here the Facebook comments box width(px)</p>
                                            <p><strong>Bg_color</strong> <span class="pro_feature"> (pro)</span> - Select the Facebook comments box background color</p>	
                                            <p><strong>Animation_effect</strong> <span class="pro_feature"> (pro)</span> - Select the animation effect for the Facebook comments box</p>											
                                            <p><strong>Count_of_comments</strong> - Type here the number of Facebook comments to display</p>

                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>            
                </div> 
            </div>
        </div>       
       <?php
	  wp_nonce_field('wpdevart_comment_options_nonce','wpdevart_comment_options_nonce');
	}
	
	
	/*#########################  Admin main section function #################################*/
	
	public function generete_wpdevart_main_section($page_parametrs){

		?>
		<div class="main_parametrs_group_div " >
			<div class="head_panel_div">
				<span class="title_parametrs_group">Comment box settings</span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody> 
                
                
                 	<tr>
						<td>
							APP ID <span style="color:#6b55f9; font-weight:bold;">Important</span>  <span title="Type here the Facebook App ID. Check how to get the APP ID from the user manual from the right side" class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="wpdevart_comment_facebook_app_id"   id="wpdevart_comment_facebook_app_id" value="<?php echo esc_attr($page_parametrs['wpdevart_comment_facebook_app_id']); ?>">
						</td>                
					</tr>
               		<tr>
						<td>
							 Title <span title="Type here the Facebook comments box title" class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="wpdevart_comment_title_text" id="wpdevart_comment_title_text" value="<?php echo esc_attr($page_parametrs['wpdevart_comment_title_text']); ?>">
						</td>                
					</tr>
                    
                     <tr >
                        <td>
                           Order Type <span title="Select the comments order type" class="desription_class">?</span>
                        </td>
                        <td>
 							<select id="wpdevart_comments_box_order_type">
                            	<option <?php selected($page_parametrs['wpdevart_comments_box_order_type'],'light') ?> value="social" >Social</option>
                                <option <?php selected($page_parametrs['wpdevart_comments_box_order_type'],'reverse_time') ?> value="reverse_time">Newest</option>
                                <option <?php selected($page_parametrs['wpdevart_comments_box_order_type'],'time') ?> value="time">Oldest</option>
                            </select>                       
                        </td>                
                    </tr>
                     <tr >
                        <td>
                           Title text color <span title="Set the Facebook comments box title color" class="desription_class">?</span>
                        </td>
                        <td>
                            <input type="text" class="color_option" id="wpdevart_comment_title_text_color" name="wpdevart_comment_title_text_color"  value="<?php echo esc_attr($page_parametrs['wpdevart_comment_title_text_color']); ?>"/>
                         </td>                
                    </tr>
                    <tr>
						<td>
							Title font-size <span title="Type the Facebook comments box title font-size(px)" class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="wpdevart_comment_title_text_font_size" id="wpdevart_comment_title_text_font_size" value="<?php echo esc_attr($page_parametrs['wpdevart_comment_title_text_font_size']); ?>">Px
						</td>                
					</tr>
                    <tr>
						<td>
							Title font family <span title="Select the Facebook comments title font family" class="desription_class">?</span>
						</td>
						<td>
							<?php $this->create_select_element_for_font('wpdevart_comment_title_text_font_famely',$page_parametrs['wpdevart_comment_title_text_font_famely']) ?>
						</td>                
					</tr>
                    <tr >
                        <td>
                           Title position <span title="Select the Facebook comments title position" class="desription_class">?</span>
                        </td>
                        <td>
                            <select id="wpdevart_comment_title_text_position">
                            	<option value="left" <?php selected($page_parametrs['wpdevart_comment_title_text_position'],'left') ?>>Left</option>
                                <option value="center" <?php selected($page_parametrs['wpdevart_comment_title_text_position'],'center') ?>>Center</option>
                                <option value="right" <?php selected($page_parametrs['wpdevart_comment_title_text_position'],'right') ?>>Right</option>
                            </select>
                         </td>                
                    </tr>
                	<tr>
						<td>
							Display comments on<span title="Set where to display the Facebook comments box" class="desription_class">?</span>
						</td>
						<td>
                        <?php $jsone_wpdevart_comments_box_show_in= json_decode(stripslashes($page_parametrs['wpdevart_comments_box_show_in']), true);?>  
                        	<input id="wpdevart_comment_show_in_home" type="checkbox" value="home" class="" size="" <?php checked($jsone_wpdevart_comments_box_show_in['home'],true) ?>><small>Home</small><br>                              
                            <input id="wpdevart_comment_show_in_post" type="checkbox" value="post" class="" size="" <?php checked($jsone_wpdevart_comments_box_show_in['post'],true) ?>><small>Post</small><br>
                            <input id="wpdevart_comment_show_in_page" type="checkbox" value="page" class="" size="" <?php checked($jsone_wpdevart_comments_box_show_in['page'],true) ?>><small>Page</small><br>
                            <input type="hidden" id="wpdevart_comments_box_show_in" class="wpdevart_comment_hidden_parametr" value='<?php echo esc_attr(stripslashes($page_parametrs['wpdevart_comments_box_show_in'])); ?>'>
                           
                        </td>                
					</tr> 
                    <tr >
                        <td>
                           Position <span class="pro_feature"> (pro)</span>  <span title="Select the Facebook comments box position(before or after the WordPress standard comments box)" class="desription_class">?</span>
                        </td>
                        <td>
                             <select class="pro_select" id="wpdevart_comments_box_vertical_position">
                            	<option value="bottom" selected="selected">Bottom</option>
                                <option value="top">Top</option>
                            </select>
                         </td>                
                    </tr>
                    
                    <tr>
						<td>
							Comments box width <span title="Type here the Facebook comments box width(px)" class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="wpdevart_comments_box_width" id="wpdevart_comments_box_width" value="<?php echo esc_attr($page_parametrs['wpdevart_comments_box_width']); ?>">
						</td>                
					</tr>
                     <tr>
						<td>
							Number of comments <span title="Type here the number of comments to display" class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="wpdevart_comments_box_count_of_comments" id="wpdevart_comments_box_count_of_comments" value="<?php echo esc_attr($page_parametrs['wpdevart_comments_box_count_of_comments']); ?>">
						</td>                
					</tr>
                    <tr >
                        <td>
                           Background color <span class="pro_feature"> (pro)</span> <span title="Set the background color of the Facebook comments box" class="desription_class">?</span>
                        </td>
                        <td>
                          <div class="wp-picker-container disabled_picker">
                          	<button type="button" class="button wp-color-result" aria-expanded="false" style="background-color: rgb(0, 0, 0);"><span class="wp-color-result-text">Select Color</span></button>
                          </div>
                         </td>                
                    </tr>  
                	 <tr>
						<td>
							Animation effect <span class="pro_feature"> (pro)</span>  <span title="Select the animation effect for the Facebook comments box" class="desription_class">?</span>
						</td>
						<td>
							<?php  wpdevart_comment_setting::generete_animation_select('animation_effect','none') ?>
						</td>                
					</tr>
                
                     <tr>
						<td>
							Default language <span title="Type here the default language code(en_US,de_DE...) of the Facebook comments box. If you left blank the field, it will display the default language." class="desription_class">?</span>
						</td>
						<td>
							<input type="text" name="wpdevart_comments_box_locale"   id="wpdevart_comments_box_locale" value="<?php echo esc_attr($page_parametrs['wpdevart_comments_box_locale']); ?>">(en_US,de_DE...)
						</td>                
					</tr>
					<tr>
						<td>
							Include SDK code <span style="color:red" title="Important - if you don't use any other FB plugin, then choose to include the SDK code, otherwise, the plugin will not work. If you choose to not include the SDK code, then our plugin will remove that part of the code." class="desription_class">?</span>
						</td>
						<td>
							 <select id="wpdevart_comments_box_include_sdk">
                            	<option <?php echo selected( $page_parametrs['wpdevart_comments_box_include_sdk'], 'yes' ) ?> value="yes">Yes</option>
                                <option <?php echo selected( $page_parametrs['wpdevart_comments_box_include_sdk'], 'no' ) ?> value="no">No</option>
                            </select>
						</td>                
					</tr>
                   
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="wpdevart_comments_box" class="save_section_parametrs button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
			</div>     
		</div>        
		<?php	
	}
	
	/*###################### Featured plugins function ##################*/	
	
	public function featured_plugins(){
		$plugins_array=array(
			'gallery_album'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/gallery-album-icon.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-gallery-plugin',
						'title'			=>	'WordPress Gallery plugin',
						'description'	=>	'Gallery plugin is a useful tool that will help you to create Galleries and Albums. Try our nice Gallery views and awesome animations.'
						),		
			'countdown-extended'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/icon-128x128.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-countdown-extended-version/',
						'title'			=>	'WordPress Countdown Extended',
						'description'	=>	'Countdown extended is a fresh and extended version of the countdown timer. You can easily create and add countdown timers to your website.'
						),						
			'coming_soon'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/coming_soon.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-coming-soon-plugin/',
						'title'			=>	'Coming soon and Maintenance mode',
						'description'	=>	'Coming soon and Maintenance mode plugin is an awesome tool to show your visitors that you are working on your website to make it better.'
						),
			'Contact forms'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/contact_forms.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-contact-form-plugin/',
						'title'			=>	'Contact Form Builder',
						'description'	=>	'Contact Form Builder plugin is a handy tool for creating different types of contact forms on your WordPress websites.'
						),	
			'Booking Calendar'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/Booking_calendar_featured.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-booking-calendar-plugin/',
						'title'			=>	'WordPress Booking Calendar',
						'description'	=>	'WordPress Booking Calendar plugin is an awesome tool to create a booking system for your website. Create booking calendars in a few minutes.'
						),
			'Pricing Table'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/Pricing-table.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-pricing-table-plugin/',
						'title'			=>	'WordPress Pricing Table',
						'description'	=>	'WordPress Pricing Table plugin is a nice tool for creating beautiful pricing tables. Use WpDevArt pricing table themes and create tables just in a few minutes.'
						),	
			'chart'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/chart-featured.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-organization-chart-plugin/',
						'title'			=>	'WordPress Organization Chart',
						'description'	=>	'WordPress organization chart plugin is a great tool for adding organizational charts to your WordPress websites.'
						),						
			'youtube'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/youtube.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-youtube-embed-plugin',
						'title'			=>	'WordPress YouTube Embed',
						'description'	=>	'YouTube Embed plugin is a convenient tool for adding videos to your website. Use YouTube Embed plugin for adding YouTube videos in posts/pages, widgets.'
						),
			'countdown'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/countdown.jpg',
						'site_url'		=>	'http://wpdevart.com/wordpress-countdown-plugin/',
						'title'			=>	'WordPress Countdown plugin',
						'description'	=>	'WordPress Countdown plugin is a nice tool for creating countdown timers for your website posts/pages and widgets.'
						),
			'lightbox'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/lightbox.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-lightbox-plugin',
						'title'			=>	'WordPress Lightbox plugin',
						'description'	=>	'WordPress Lightbox Popup is a high customizable and responsive plugin for displaying images and videos in the popup.'
						),
			'facebook'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/facebook.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-facebook-like-box-plugin',
						'title'			=>	'Social Like Box',
						'description'	=>	'Facebook like box plugin will help you to display Facebook like box on your website, just add Facebook Like box widget to the sidebar or insert it into posts/pages and use it.'
						),
			'vertical_menu'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/vertical-menu.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-vertical-menu-plugin/',
						'title'			=>	'WordPress Vertical Menu',
						'description'	=>	'WordPress Vertical Menu is a handy tool for adding nice vertical menus. You can add icons for your website vertical menus using our plugin.'
						),						
			'duplicate_page'=>array(
						'image_url'		=>	$this->plugin_url.'images/featured_plugins/featured-duplicate.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-duplicate-page-plugin-easily-clone-posts-and-pages/',
						'title'			=>	'WordPress Duplicate page',
						'description'	=>	'Duplicate Page or Post is a great tool that allows duplicating pages and posts. Now you can do it with one click.'
						),						
						
			
		);
		?>
        <style>
         .featured_plugin_main{
			background-color: #ffffff;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			float: left;
			margin-right: 30px;
			margin-bottom: 30px;
			width: calc((100% - 90px)/3);
			border-radius: 15px;
			box-shadow: 1px 1px 7px rgba(0,0,0,0.04);
			padding: 20px 25px;
			text-align: center;
			-webkit-transition:-webkit-transform 0.3s;
			-moz-transition:-moz-transform 0.3s;
			transition:transform 0.3s;   
			-webkit-transform: translateY(0);
			-moz-transform: translateY0);
			transform: translateY(0);
			min-height: 344px;
		 }
		.featured_plugin_main:hover{
			-webkit-transform: translateY(-2px);
			-moz-transform: translateY(-2px);
			transform: translateY(-2px);
		 }
		.featured_plugin_image{
			max-width: 128px;
			margin: 0 auto;
		}
		.blue_button{
    display: inline-block;
    font-size: 15px;
    text-decoration: none;
    border-radius: 5px;
    color: #ffffff;
    font-weight: 400;
    opacity: 1;
    -webkit-transition: opacity 0.3s;
    -moz-transition: opacity 0.3s;
    transition: opacity 0.3s;
    background-color: #7052fb;
    padding: 10px 22px;
    text-transform: uppercase;
		}
		.blue_button:hover,
		.blue_button:focus {
			color:#ffffff;
			box-shadow: none;
			outline: none;
		}
		.featured_plugin_image img{
			max-width: 100%;
		}
		.featured_plugin_image a{
		  display: inline-block;
		}
		.featured_plugin_information{	

		}
		.featured_plugin_title{
	color: #7052fb;
	font-size: 18px;
	display: inline-block;
		}
		.featured_plugin_title a{
	text-decoration:none;
	font-size: 19px;
    line-height: 22px;
	color: #7052fb;
					
		}
		.featured_plugin_title h4{
			margin: 0px;
			margin-top: 20px;		
			min-height: 44px;	
		}
		.featured_plugin_description{
			font-size: 14px;
				min-height: 63px;
		}
		@media screen and (max-width: 1460px){
			.featured_plugin_main {
				margin-right: 20px;
				margin-bottom: 20px;
				width: calc((100% - 60px)/3);
				padding: 20px 10px;
			}
			.featured_plugin_description {
				font-size: 13px;
				min-height: 63px;
			}
		}
		@media screen and (max-width: 1279px){
			.featured_plugin_main {
				width: calc((100% - 60px)/2);
				padding: 20px 20px;
				min-height: 363px;
			}	
		}
		@media screen and (max-width: 768px){
			.featured_plugin_main {
				width: calc(100% - 30px);
				padding: 20px 20px;
				min-height: auto;
				margin: 0 auto 20px;
				float: none;
			}	
			.featured_plugin_title h4{
				min-height: auto;
			}	
			.featured_plugin_description{
				min-height: auto;
					font-size: 14px;
			}	
		}

        </style>
      
		<h1 style="text-align: center;font-size: 50px;font-weight: 700;color: #2b2350;margin: 20px auto 25px;line-height: 1.2;">Featured Plugins</h1>
		<?php foreach($plugins_array as $key=>$plugin) { ?>
		<div class="featured_plugin_main">
			<div class="featured_plugin_image"><a target="_blank" href="<?php echo esc_url($plugin['site_url']); ?>"><img src="<?php echo esc_url($plugin['image_url']); ?>"></a></div>
			<div class="featured_plugin_information">
				<div class="featured_plugin_title"><h4><a target="_blank" href="<?php echo esc_url($plugin['site_url']); ?>"><?php echo esc_html($plugin['title']); ?></a></h4></div>
				<p class="featured_plugin_description"><?php echo esc_html($plugin['description']) ?></p>
				<a target="_blank" href="<?php echo esc_url($plugin['site_url']); ?>" class="blue_button">Check The Plugin</a>
			</div>
			<div style="clear:both"></div>                
		</div>
		<?php } 
	
	}
	
	/*######################################### Fonts(select fonts) Function #######################################*/

	private function create_select_element_for_font($select_id='',$curent_font='none'){
	?>
   <select id="<?php echo esc_attr($select_id); ?>" name="<?php echo esc_attr($select_id); ?>">
   
        <option <?php selected('Arial,Helvetica Neue,Helvetica,sans-serif',$curent_font); ?> value="Arial,Helvetica Neue,Helvetica,sans-serif">Arial *</option>
        <option <?php selected('Arial Black,Arial Bold,Arial,sans-serif',$curent_font); ?> value="Arial Black,Arial Bold,Arial,sans-serif">Arial Black *</option>
        <option <?php selected('Arial Narrow,Arial,Helvetica Neue,Helvetica,sans-serif',$curent_font); ?> value="Arial Narrow,Arial,Helvetica Neue,Helvetica,sans-serif">Arial Narrow *</option>
        <option <?php selected('Courier,Verdana,sans-serif',$curent_font); ?> value="Courier,Verdana,sans-serif">Courier *</option>
        <option <?php selected('Georgia,Times New Roman,Times,serif',$curent_font); ?> value="Georgia,Times New Roman,Times,serif">Georgia *</option>
        <option <?php selected('Times New Roman,Times,Georgia,serif',$curent_font); ?> value="Times New Roman,Times,Georgia,serif">Times New Roman *</option>
        <option <?php selected('Trebuchet MS,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Arial,sans-serif',$curent_font); ?> value="Trebuchet MS,Lucida Grande,Lucida Sans Unicode,Lucida Sans,Arial,sans-serif">Trebuchet MS *</option>
        <option <?php selected('Verdana,sans-serif',$curent_font); ?> value="Verdana,sans-serif">Verdana *</option>
        <option <?php selected('American Typewriter,Georgia,serif',$curent_font); ?> value="American Typewriter,Georgia,serif">American Typewriter</option>
        <option <?php selected('Andale Mono,Consolas,Monaco,Courier,Courier New,Verdana,sans-serif',$curent_font); ?> value="Andale Mono,Consolas,Monaco,Courier,Courier New,Verdana,sans-serif">Andale Mono</option>
        <option <?php selected('Baskerville,Times New Roman,Times,serif',$curent_font); ?> value="Baskerville,Times New Roman,Times,serif">Baskerville</option>
        <option <?php selected('Bookman Old Style,Georgia,Times New Roman,Times,serif',$curent_font); ?> value="Bookman Old Style,Georgia,Times New Roman,Times,serif">Bookman Old Style</option>
        <option <?php selected('Calibri,Helvetica Neue,Helvetica,Arial,Verdana,sans-serif',$curent_font); ?> value="Calibri,Helvetica Neue,Helvetica,Arial,Verdana,sans-serif">Calibri</option>
        <option <?php selected('Cambria,Georgia,Times New Roman,Times,serif',$curent_font); ?> value="Cambria,Georgia,Times New Roman,Times,serif">Cambria</option>
        <option <?php selected('Candara,Verdana,sans-serif',$curent_font); ?> value="Candara,Verdana,sans-serif">Candara</option>
        <option <?php selected('Century Gothic,Apple Gothic,Verdana,sans-serif',$curent_font); ?> value="Century Gothic,Apple Gothic,Verdana,sans-serif">Century Gothic</option>
        <option <?php selected('Century Schoolbook,Georgia,Times New Roman,Times,serif',$curent_font); ?> value="Century Schoolbook,Georgia,Times New Roman,Times,serif">Century Schoolbook</option>
        <option <?php selected('Consolas,Andale Mono,Monaco,Courier,Courier New,Verdana,sans-serif',$curent_font); ?> value="Consolas,Andale Mono,Monaco,Courier,Courier New,Verdana,sans-serif">Consolas</option>
        <option <?php selected('Constantia,Georgia,Times New Roman,Times,serif',$curent_font); ?> value="Constantia,Georgia,Times New Roman,Times,serif">Constantia</option>
        <option <?php selected('Corbel,Lucida Grande,Lucida Sans Unicode,Arial,sans-serif',$curent_font); ?> value="Corbel,Lucida Grande,Lucida Sans Unicode,Arial,sans-serif">Corbel</option>
        <option <?php selected('Franklin Gothic Medium,Arial,sans-serif',$curent_font); ?> value="Franklin Gothic Medium,Arial,sans-serif">Franklin Gothic Medium</option>
        <option <?php selected('Garamond,Hoefler Text,Times New Roman,Times,serif',$curent_font); ?> value="Garamond,Hoefler Text,Times New Roman,Times,serif">Garamond</option>
        <option <?php selected('Gill Sans MT,Gill Sans,Calibri,Trebuchet MS,sans-serif',$curent_font); ?> value="Gill Sans MT,Gill Sans,Calibri,Trebuchet MS,sans-serif">Gill Sans MT</option>
        <option <?php selected('Helvetica Neue,Helvetica,Arial,sans-serif',$curent_font); ?> value="Helvetica Neue,Helvetica,Arial,sans-serif">Helvetica Neue</option>
        <option <?php selected('Hoefler Text,Garamond,Times New Roman,Times,sans-serif',$curent_font); ?> value="Hoefler Text,Garamond,Times New Roman,Times,sans-serif">Hoefler Text</option>
        <option <?php selected('Lucida Bright,Cambria,Georgia,Times New Roman,Times,serif',$curent_font); ?> value="Lucida Bright,Cambria,Georgia,Times New Roman,Times,serif">Lucida Bright</option>
        <option <?php selected('Lucida Grande,Lucida Sans,Lucida Sans Unicode,sans-serif',$curent_font); ?> value="Lucida Grande,Lucida Sans,Lucida Sans Unicode,sans-serif">Lucida Grande</option>
        <option <?php selected('monospace',$curent_font); ?> value="monospace">monospace</option>
        <option <?php selected('Palatino Linotype,Palatino,Georgia,Times New Roman,Times,serif',$curent_font); ?> value="Palatino Linotype,Palatino,Georgia,Times New Roman,Times,serif">Palatino Linotype</option>
        <option <?php selected('Tahoma,Geneva,Verdana,sans-serif',$curent_font); ?> value="Tahoma,Geneva,Verdana,sans-serif">Tahoma</option>
        <option <?php selected('Rockwell, Arial Black, Arial Bold, Arial, sans-serif',$curent_font); ?> value="Rockwell, Arial Black, Arial Bold, Arial, sans-serif">Rockwell</option>
    </select>
    <?php
	}
	public function hire_expert(){
		$plugins_array=array(
			'custom_site_dev'=>array(
				'image_url'		=>	$this->plugin_url.'images/hire_expert/1.png',
				'title'			=>	'Custom WordPress Development',
				'description'	=>	'Hire a WordPress developer and he will do any custom development you need for you WordPress website.'
			),
			'custom_plug_dev'=>array(
				'image_url'		=>	$this->plugin_url.'images/hire_expert/2.png',
				'title'			=>	'WordPress Plugin Development',
				'description'	=>	'Our developers can create any WordPress plugin. They can also customize any plugin and add any functionality you need.'
			),
			'custom_theme_dev'=>array(
				'image_url'		=>	$this->plugin_url.'images/hire_expert/3.png',
				'title'			=>	'WordPress Theme Development',
				'description'	=>	'If you need a unique theme or any customization for a ready-made theme, our developers are ready to do it.'
			),
			'custom_theme_inst'=>array(
				'image_url'		=>	$this->plugin_url.'images/hire_expert/4.png',
				'title'			=>	'WordPress Theme Installation and Customization',
				'description'	=>	'If you need to install and customize a theme, just let us know, our specialists will customize it.'
			),
			'gen_wp_speed'=>array(
				'image_url'		=>	$this->plugin_url.'images/hire_expert/5.png',
				'title'			=>	'General WordPress Support',
				'description'	=>	'Our developers can provide general support. If you have any problems with your site, then our experts are ready to help.'
			),
			'speed_op'=>array(
				'image_url'		=>	$this->plugin_url.'images/hire_expert/6.png',
				'title'			=>	'WordPress Speed Optimization',
				'description'	=>	'Hire an expert from WpDevArt and let him take care of your website speed optimization.'
			),
			'mig_serv'=>array(
				'image_url'		=>	$this->plugin_url.'images/hire_expert/7.png',
				'title'			=>	'WordPress Migration Services',
				'description'	=>	'Our specialists can migrate websites from any platform to WordPress.'
			),
			'page_seo'=>array(
				'image_url'		=>	$this->plugin_url.'images/hire_expert/8.png',
				'title'			=>	'WordPress SEO',
				'description'	=>	'Hire SEO specialists and they will take care of the search engine optimization of your site.'
			)
		);
		
		echo '<h1 class="wpdev_hire_exp_h1"> Hire an Expert </h1>';
		echo '<div class="hire_expert_main">';		
		foreach($plugins_array as $key=>$plugin) {
			echo '<div class="wpdevart_hire_main"><a target="_blank" class="wpdev_hire_buklet" href="https://wpdevart.com/hire-wordpress-developer-dedicated-experts-are-ready-to-help/">';
			echo '<div class="wpdevart_hire_image"><img src="'.esc_url($plugin["image_url"]).'"></div>';
			echo '<div class="wpdevart_hire_information">';
			echo '<div class="wpdevart_hire_title">'.esc_html($plugin["title"]).'</div>';			
			echo '<p class="wpdevart_hire_description">'.esc_html($plugin["description"]).'</p>';
			echo '</div></a></div>';		
		} 
		echo '<div><a target="_blank" class="wpdev_hire_button" href="https://wpdevart.com/hire-wordpress-developer-dedicated-experts-are-ready-to-help/">Hire an Expert</a></div>';
		echo '</div>';	
	}
	
}