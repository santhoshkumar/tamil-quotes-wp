<?php
/*
* Plugin Name: Tamil Quotes
* Plugin URI: https://github.com/santhoshkumar/tamil-quotes-wp
* Description: Display Random Tamil SMS kavithai and Quotes on your Wordpress Site.
* Version: 1.0
* Author: Santhosh veer
* Author URI: https://sanweb.info/
* License: GPLv3
* License URI: http://www.gnu.org/licenses/gpl.html
*/

## register admin script
add_action( 'admin_enqueue_scripts', 'tmsql_enqueue_color_picker' );
function tmsql_enqueue_color_picker() {
wp_enqueue_style('wp-color-picker');
wp_enqueue_script('tml-color-picker', plugin_dir_url( __FILE__ ) . 'assets/js/color.js', array( 'wp-color-picker' ), false);
}

## Google Tamil Font with Preload
if(get_option("tmlquotes_enable_font") == 1){
function tmsql_google_fonts() {
  wp_register_style( 'tms-google-fonts', 'https://fonts.googleapis.com/css2?family=Baloo+Thambi+2:wght@400;500;600;700;800&display=swap', false, null );
  wp_enqueue_style('tms-google-fonts');
}
add_action( 'wp_enqueue_scripts', 'tmsql_google_fonts' );

if (!function_exists('add_tamil_fonts_cdn_attributes')) {
  function add_tamil_fonts_cdn_attributes( $html, $handle ) {
    if ( 'tms-google-fonts' === $handle ) {
        $optimize_media = array("rel='stylesheet'", "media='all'", "id='$handle-css'");
        $optimize_sheet = array("rel='preload' as='style', onload=\"this.rel='stylesheet'\"");
        return str_replace( $optimize_media, $optimize_sheet, $html );
    }
    return $html;
    }
  }
add_filter( 'style_loader_tag', 'add_tamil_fonts_cdn_attributes', 10, 2 );
}

## CSS for Quotes
add_action('wp_head','tmlquotes_css');
function tmlquotes_css() {
$bgcolor = get_option('tmlquotes_bg_color');
$bgtext = get_option('tmlquotes_text_color');
if(get_option("tmlquotes_enable_font") == 1){
$tamil_font = "'Baloo Thambi 2', cursive";
} else {
$tamil_font = '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif';
}
$output="<style>
#hello-quotes {       
  background-color: $bgcolor;
  color: $bgtext;
  padding: 30px;
  margin: 15px;
  display:inline-block;
  overflow:hidden;
  border-radius:12px;
  font-family: $tamil_font;
  font-weight: 600;
  font-size: 14px;
  letter-spacing: .03em;
  word-wrap: break-word;
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased !important;
  -moz-font-smoothing: antialiased !important;
  text-rendering: optimizelegibility !important;
}
</style>";
echo $output;
}

## plugin open registration
function activate_tmlquotes() {
  add_option('tmlquotes_bg_color', '#7158e2');
  add_option('tmlquotes_text_color', '#ffffff');
}

## Regsister user input
function admin_init_tmlquotes() {
  register_setting('tmlquotes_mskc_topt', 'tmlquotes_bg_color');
  register_setting('tmlquotes_mskc_topt', 'tmlquotes_text_color');
  register_setting('tmlquotes_mskc_topt', 'tmlquotes_enable_font');
}

## Setup Admin Page
function admin_menu_tmlquotes() {
  add_options_page('Tamil Quotes', 'Tamil Quotes', 'manage_options', 'tmlquotes_mskc_topt', 'options_page_tmlquotes');
}

## init Admin
if (is_admin()) {
  add_action('admin_init', 'admin_init_tmlquotes');
  add_action('admin_menu', 'admin_menu_tmlquotes');

}

## Options Page
function options_page_tmlquotes() {
  include( plugin_dir_path( __FILE__ ) .'options.php');
}

## plugin register hooks
register_activation_hook(__FILE__, 'activate_tmlquotes');

## Shortcode to Display Tamil Quotes
function wpb_tmlquotes_shortcode(){

  ## Load javascript File when Shortcode has been Used
  wp_enqueue_script( 'tamil-quotes', plugins_url( '/assets/js/tmquotes.js' , __FILE__ ) );

  ## Generate Quotes
  $tamil_quotes = '<div class="ta_quotes" id="hello-quotes"></div>';

  ## Print Quotes via Shortcode
  return $tamil_quotes;
}
add_shortcode('tamilquotes', 'wpb_tmlquotes_shortcode');

## Settings Page link
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'tmlquotes_optge_links' );

function tmlquotes_optge_links ( $tmlquotesclinks ) {
 $mytmlquoteslinks = array(
 '<a href="' . admin_url( 'options-general.php?page=tmlquotes_mskc_topt' ) . '">Plugin Settings</a>',
 );
return array_merge( $tmlquotesclinks, $mytmlquoteslinks );
}
