<?php
/*
Plugin Name: Social Hashtag
Description: Grabs images & videos matching any hashtag from social APIs like instagram & youtube.  Stores thumbnails & details locally for each one in a custom post type so you have full control over the content on your site.  This allows you to categorize, make private/public, etc and include them any way that you like on your pages.
Version: 2.3.0
Author: Bryan Shanaver
Author URI: http://fiftyandfifty.org
*/
?>
<?php

define('SOCIAL_HASHTAG_VERSION', '2.3.0');

define('SOCIAL_HASHTAG_URL', plugin_dir_url( __FILE__ ));
define('SOCIAL_HASHTAG_PATH', plugin_dir_path(__FILE__) );
define('SOCIAL_HASHTAG_BASENAME', plugin_basename(__FILE__));

require_once( SOCIAL_HASHTAG_PATH . '/lib/social_hashtag.class.php');
require_once( SOCIAL_HASHTAG_PATH . '/lib/platforms.class.php');
require_once( SOCIAL_HASHTAG_PATH . '/lib/posttypes.php');
require_once( SOCIAL_HASHTAG_PATH . '/lib/shortcodes.php');

// initialize plugin & listen for requests
add_action('init', 'social_hashtag_cache_init', 10);
function social_hashtag_cache_init() {
  global $social_hashtag_cache;
  $social_hashtag_cache = new SOCIAL_HASHTAG_CACHE();
}

// admin styles & scripts
function social_hashtag_admin_scripts_styles(){
  wp_register_style( 'social_hashtag-style', SOCIAL_HASHTAG_URL . 'lib/social_hashtag.css' );
  wp_enqueue_style( 'social_hashtag-style' );
  wp_localize_script( 'jquery', 'social_hashtag_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
  wp_enqueue_script( 'jquery' );
}
add_action('admin_init', 'social_hashtag_admin_scripts_styles');

// front end styles & scripts
function social_hashtag_front_scripts_styles(){
  wp_register_style( 'social_hashtag-style', SOCIAL_HASHTAG_URL . 'lib/social_hashtag.css' );
  wp_enqueue_style( 'social_hashtag-style' );
}
add_action('wp_enqueue_scripts', 'social_hashtag_front_scripts_styles');

// menu page
function social_hashtag_add_menu_page(){
  function social_hashtag_menu_page(){
    $options_page_url = SOCIAL_HASHTAG_PATH . '/social_hashtag-options.php';
    if(file_exists($options_page_url)){
      include_once($options_page_url);
    }
    else{
      print "missing options page!";
    }
  };
  add_submenu_page( 'options-general.php', 'Social Hashtag Settings', 'Social Hashtag', 'switch_themes', 'social_hashtag', 'social_hashtag_menu_page' );
};
add_action( 'admin_menu', 'social_hashtag_add_menu_page' );

// Add settings link on plugin page
function social_hashtag_plugin_settings_link($links) {
  $settings_link = '<a href="options-general.php?page=social_hashtag">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}
add_filter("plugin_action_links_" . SOCIAL_HASHTAG_BASENAME, 'social_hashtag_plugin_settings_link' );


/*

Cron functions

*/

// set up custom cron schedules
add_filter( 'cron_schedules', 'social_hashtag_cron_schedules');
function social_hashtag_cron_schedules(){
  return array(
    'every_fifteen_minutes' => array(
      'interval' => 60 * 15,
      'display' => 'Four Times Hourly'
    ),
    'every_thirty_minutes' => array(
      'interval' => 60 * 30,
      'display' => 'Twice Hourly'
    ),
  );
}

// function for grabbing all APIs - run from cron
function social_hashtag_grab_apis() {
  social_hashtag_logging('Start - cron', 2);
  $social_hashtag_cache = new SOCIAL_HASHTAG_CACHE();
  $social_hashtag_cache->get_social_hashtag_pics();
  social_hashtag_logging('End - cron task finished', 2);
}
add_action('social_hashtag_cron', 'social_hashtag_grab_apis');


// function for adding the cron
function social_hashtag_activate_cron($cron) {
  if( wp_get_schedule('social_hashtag_cron') ){
    wp_clear_scheduled_hook('social_hashtag_cron');
  }
  if($cron == '15'){
    wp_schedule_event(time(), 'every_fifteen_minutes', 'social_hashtag_cron');
  }
  elseif($cron == '30'){
    wp_schedule_event(time(), 'every_thirty_minutes', 'social_hashtag_cron');
  }
  else{
    wp_schedule_event(time(), 'hourly', 'social_hashtag_cron');
  }
}

// function for removing the cron
function social_hashtag_deactivate_cron() {
  if( wp_get_schedule('social_hashtag_cron') ){
    wp_clear_scheduled_hook('social_hashtag_cron');
  }
}

// utility function used by the cron stuff
function social_hashtag_get_shortest_cron($new_cron_interval, $shortest_cron_interval){
  $explode = explode("cron", $new_cron_interval, 2);
  if( count($explode) == 2 ){
    if( (int)$explode[1] < (int)$shortest_cron_interval || empty($shortest_cron_interval)){
      return $explode[1];
    }
    else{
      return $shortest_cron_interval;
    }
  }
  else{
    return $shortest_cron_interval;
  }
}

// when plugin is activated, check for crons that need to be reset
register_activation_hook(__FILE__,'social_hashtag_activate');
function social_hashtag_activate() {
  $social_hashtag_cache = new SOCIAL_HASHTAG_CACHE();
  $social_api_options = $social_hashtag_cache->get_social_hashtag_options();
  $shortest_cron_interval = null;
  foreach( $social_api_options as $api_num => $api_settings){
    if( !empty($api_settings['api_selected']) ){
      $platform = $social_hashtag_cache->choose_platform($api_settings['api_selected']);
      if(is_object($platform)){
        $shortest_cron_interval = social_hashtag_get_shortest_cron($api_settings['cron_interval'], $shortest_cron_interval);
      }
    }
  }
  if( !empty($shortest_cron_interval) ){
    social_hashtag_activate_cron($shortest_cron_interval);
  }
  else{
    social_hashtag_deactivate_cron();
  }
}

// remove crons on deactivation of plugin
register_deactivation_hook(__FILE__,'social_hashtag_deactivate');
function social_hashtag_deactivate(){
  social_hashtag_deactivate_cron();
}

/**
 * Fixes the attachment url (so it doesn't look in the local uploads directory)
 *
 * @package WordPress
 */

add_filter('wp_get_attachment_url', 'social_hashtag_get_attachment_url', 9, 2);
function social_hashtag_get_attachment_url($url, $postID)
{
  $social_hashtag_url = get_post_meta($postID, 'social_hashtag_thumb_url', true);

  if( !empty($social_hashtag_url) ){
    return $social_hashtag_url;
  }
  else{
    return $url;
  }
}


/*

Ajax Methods

*/

function social_hashtag_run_request(){
  social_hashtag_logging('Start - run manually', 2);
  $social_hashtag_cache = new SOCIAL_HASHTAG_CACHE();
  if( !empty($_REQUEST['debug']) ){ $social_hashtag_cache->debug = true; }
  $results = $social_hashtag_cache->run_social_hashtag_query( $_REQUEST['num'] );
  print json_encode(array('success' => true, 'message' => $results ));
  social_hashtag_logging('End - run manually: ' . $results, 2);
  die();
}
add_action( 'wp_ajax_social_hashtag_run_manually', 'social_hashtag_run_request' );


function social_hashtag_logging($message, $severity=0){
  if( !defined('WLS_VERSION') ){
    return;
  }
  $category = 'Social Hashtag Plugin';
  if( !wls_is_registered( $category ) ){
    wls_register( $category, 'Logging for the Social Hashtag Plugin' );
  }
  wls_simple_log( $category, $message, $severity);
}

