<?php
/**
 * Plugin Name: Wpdevart Social comments
 * Plugin URI: https://wpdevart.com/wordpress-facebook-comments-plugin
 * Author URI: https://wpdevart.com
 * Description: Social (Facebook) comments plugin will help you to display Facebook Comments box on your website. You can use Facebook Comments on your pages/posts.
 * Version: 2.5.6
 * Author: wpdevart
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

class wpdevart_comment_main{
	// required variables 
	
	private $wpdevart_comment_plugin_url;
	
	private $wpdevart_comment_plugin_path;
	
	private $wpdevart_comment_version;
	
	public $wpdevart_comment_options;
	
    /*############  Construct function  ################*/
	
	function __construct(){
		
		$this->wpdevart_comment_plugin_url  = trailingslashit( plugins_url('', __FILE__ ) );
		$this->wpdevart_comment_plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
		define("wpdevart_comment_support_url","https://wordpress.org/support/plugin/comments-from-facebook");
		if(!class_exists('wpdevart_comment_setting'))
			require_once($this->wpdevart_comment_plugin_path.'includes/library.php');
		$this->wpdevart_comment_version = 10.0;
		$this->call_base_filters();
		$this->install_databese();
		$this->create_admin_menu();	
		$this->wpdevart_comment_front_end();
		
	}

	/*###################### Create admin menu function ##################*/	
	
	public function create_admin_menu(){
		
		require_once($this->wpdevart_comment_plugin_path.'includes/admin_menu.php');
		
		$wpdevart_comment_admin_menu = new wpdevart_comment_admin_menu(array('menu_name' => 'FB comments','databese_parametrs'=>$this->wpdevart_comment_options));
		
		add_action('admin_menu', array($wpdevart_comment_admin_menu,'create_menu'));
		
	}

	/*###################### Database function ##################*/	
	
	public function install_databese(){
		
		require_once($this->wpdevart_comment_plugin_path.'includes/install_database.php');
		
		$wpdevart_comment_install_database = new wpdevart_comment_install_database();
		
		$this->wpdevart_comment_options = $wpdevart_comment_install_database->installed_options;
		
	}
	
	/*###################### Front-end function ##################*/	
	
	public function wpdevart_comment_front_end(){
		
		require_once($this->wpdevart_comment_plugin_path.'includes/front_end.php');
		$wpdevart_comment_front_end = new wpdevart_comment_front_end(array('menu_name' => 'Wpdevart Comment','databese_parametrs'=>$this->wpdevart_comment_options));
		
	}

    /*############  Register Required Scripts function  ################*/
	
	public function registr_requeried_scripts(){
		wp_register_script('comment-box-admin-script',$this->wpdevart_comment_plugin_url.'includes/javascript/admin-wpdevart-comment.js');
		wp_register_style('comment-box-admin-style',$this->wpdevart_comment_plugin_url.'includes/style/admin-style.css');
	}
	
	/*###################### Call base filters function ##################*/
	
	public function call_base_filters(){
		add_action( 'init',  array($this,'registr_requeried_scripts') );
		add_action( 'admin_head',  array($this,'include_requeried_scripts') );
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this,'plugin_activate_sublink') );
	}
	
    /*############  Sub-link function  ################*/	
	
	public function plugin_activate_sublink($links){
		$plugin_submenu_added_link=array();		
		 $added_link = array(
		 '<a target="_blank" style="color: #7052fb; font-weight: bold; font-size: 13px;" href="https://wpdevart.com/wordpress-facebook-comments-plugin">Upgrade to Pro</a>',
		 );
		$plugin_submenu_added_link=array_merge( $plugin_submenu_added_link, $added_link );
		$plugin_submenu_added_link=array_merge( $plugin_submenu_added_link, $links );
		return $plugin_submenu_added_link;
	}
	
    /*############  Include required scripts function  ################*/	
	
  	public function include_requeried_scripts(){
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_style( 'wp-color-picker' );
	}
}
$wpdevart_comment_main = new wpdevart_comment_main();
?>