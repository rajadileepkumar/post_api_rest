<?php 
/**
*Plugin Name:Post API WITH WP_REST
*Author:Raja Dileep Kumar
*Author URI:https://www.utthunga.com
*Plugin URI:https://www.utthunga.com
*Description:To Get Blog Posts from Any Website
*/
//include( dirname( dirname( __FILE__ ) ) . '/admin-page-framework/library/apf/admin-page-framework.php' );
// if (!class_exists('AdminPageFramework')) {
//     require_once(ABSPATH . 'wp-admin/admin-page-framework/library/apf/admin-page-framework.php');
// }

Class API_Plugin_Activation{

	public function __construct(){
		add_action('admin_menu',array($this,'api_plugin_menu'));
		add_action( 'wp_ajax_nopriv_saveAPIURL', array($this,'saveAPIURL')); //insert log record
		add_action( 'wp_ajax_saveAPIURL', array($this,'saveAPIURL'));//insert log record
		add_shortcode('api_blog_shortcode',array($this,'api_blog_shortcode'));
	}

	public function api_plugin_menu(){
		add_menu_page('Api Post Plugin','Api Post Plugin','manage_options','api_plugin_menu',array($this,'api_plugin_menu_page'),'',null);		
		//add_menu_page( 'Active Collab', 'Active Collab', 'active_menu_access', 'a_collab_menu_slug', array($this,'a_collab_menu_page'), '', null );
		add_action('admin_enqueue_scripts',array($this,'api_plugin_load_scripts'));

		
	}

	public function api_plugin_load_scripts(){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'jquery-ui-css', plugins_url('assets/css/jquery-ui.css', __FILE__));
		wp_enqueue_script('jquery-ui-js',plugins_url('assets/js/jquery-ui.js',__FILE__),array(),true,false);
		wp_register_script('main-js',plugins_url('assets/js/main.js', __FILE__),array(),true,false);
		wp_enqueue_script('main-js');
		
		wp_localize_script( 'main-js', 'ajax_object',
		                array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
	}
	public function api_plugin_menu_page(){
		?>
			<div class="wrap">
				<h2>Settings Page</h2>
				<div class="e-message"><p></p></div>
				<div id="tabs">
				  <ul>
				    <li><a href="#tabs-1">Settings Page</a></li>
				    <li><a href="#tabs-2">Shortcode</a></li>				   
				  </ul>
				  <div id="tabs-1">
				  	<form action="" method="post" id="apiSettings">
				    	<table class="form-table">
				    		<tbody>
				    			<tr>
				    				<th>API Settings URL</th>
				    				<td>
										<input type="url" name="aSettingsURL" id="aSettingsURL" placeholder="http://www.example.com/wp-json/wp/v2/posts?_embed" class="regular-text" required value="<?php echo get_option('api_url_setting'); ?>">
									</td>
				    			</tr>
				    		</tbody>
				    	</table>
				    	<p>
							<input type="submit" name="saveApiUrl" id="saveApiUrl" value="Save" class="button button-primary">
						</p>
				    </form>
				  </div>
				  <div id="tabs-2">
				   	<pre>
				   		Template Shortcode :echo do_shortcode('[api_blog_shortcode]');
					   	Page Shortcode:[api_blog_shortcode];
				   	</pre>
				  </div>
				</div>
			</div><!--wrap-->
		<?php
	}

	public function saveAPIURL(){
		global $wpdb;
		$apiURL = sanitize_text_field($_POST['apiURL']);
		$optionName = 'api_url_setting';
		$option_exists = (get_option($optionName, null) !== null);
		if($option_exists){
			update_option( $optionName, $apiURL, 'yes');
			echo 'Updated Successfully';
		}
		else{
			add_option($optionName, $apiURL, '', 'yes'); //insert settings	
			echo 'Inserted Successfully';
		}
		die();
	}

	public function api_blog_shortcode(){
		$url = (!empty(get_option('api_url_setting')))?get_option('api_url_setting'):site_url().'/wp-json/wp/v2/posts?_embed';
   		$response = wp_remote_get( esc_url_raw( $url ) );
		if ( is_wp_error( $response ) ) {
			return false;
		} 
		/* Will result in $api_response being an array of data,
		parsed from the JSON response of the API listed above */
		$api_response = json_decode( wp_remote_retrieve_body( $response ), true );
		foreach ($api_response as $key => $value) {
			echo $value['title']['rendered']."<br/>";
			echo $value['excerpt']['rendered']."<br/>";
			?>
				<img class="img-width" src="<?php echo $value['_embedded']['wp:featuredmedia'][0]['source_url']; ?>">
			<?php
		}
	}
}
$obj = new API_Plugin_Activation();
?>