<?php
/*
Plugin Name: Remove Emoji Styles & Scripts
Plugin URI:  https://www.inthiscode.com/
Description: If you do not want or need Emoji it is best to remove/dequeue Emoji styles and scripts for better performance.
Version:     1.3.1
Author:      InThisCode
Author URI:  http://www.inthiscode.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: ress-itc
*/
defined( 'ABSPATH' ) or die( 'You are lost.' );

// Admin menu
add_action( 'admin_menu', 'ress_itc_add_admin_menu' ); // Add Admin Menu
if(!function_exists('ress_itc_add_admin_menu')){
	function ress_itc_add_admin_menu(  ) { 
		add_options_page( 'Remove Emoji', 'Remove Emoji Styles & Scripts', 'manage_options', 'ress_itc', 'ress_itc_options_page' );
	}
}
function ress_itc_options_page(  ) { 
	?>
	<form action='options.php' method='post'>
		<h1>Remove Emoji Styles & Scripts</h1>
		<?php
		settings_fields( 'ress_itc_pluginPage' );
		do_settings_sections( 'ress_itc_pluginPage' );
		submit_button();
		?>
	</form>
	<?php
}

// settings link
function ress_itc_plugin_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=ress_itc">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'ress_itc_plugin_settings_link' );

// Admin Settings
add_action( 'admin_init', 'ress_itc_settings_init' ); 
function ress_itc_settings_init() {
	register_setting( 'ress_itc_pluginPage', 'ress_itc_settings' );
	
	// Add section
	add_settings_section('ress_itc_pluginPage_section', __( 'Settings', 'ress-itc' ), 'ress_itc_settings_section_callback', 'ress_itc_pluginPage');
	
	// Enable plugin
	add_settings_field('ress_itc_enable', __( 'Enable Plugin', 'ress-itc' ), 'ress_itc_enable_render', 'ress_itc_pluginPage', 'ress_itc_pluginPage_section');	
}

// Callback
function ress_itc_settings_section_callback(  ) { 
	echo __( '', 'ress-itc' );
}

$ress_itc_options = get_option( 'ress_itc_settings' );

// Display enable checkbox
function ress_itc_enable_render() {
	global $ress_itc_options;
	?>
    <input type='checkbox' name='ress_itc_settings[ress_itc_enable]' <?php  if ( isset( $ress_itc_options['ress_itc_enable'] ) && $ress_itc_options['ress_itc_enable'] == '1' ) {echo 'Checked';} ?> value='1'>
    <?php
}

// If checkbox checked
if ( isset( $ress_itc_options['ress_itc_enable'] ) && $ress_itc_options['ress_itc_enable'] == '1' ) {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); 
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' ); 
	remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
}