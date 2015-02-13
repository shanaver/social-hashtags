<?php
/*
 * @author      Bryan Shanaver <bryan[at]fiftyandfifty[dot]org>
 * @edited by Jonathan Kiritharan <jkiritharan[at]gmail[dot]com> in June 2014
 */

//I was having some problems figuring out how to return an error or any data back while testing on wp-admin->settings->social-hashtags alert box
//sending an email worked best in returning code and variables I wanted to see : mail("yourName@mailinator.com", "subject", (string)print_r($variable,true));
if( !class_exists('SOCIAL_HASHTAG_CACHE') ) {
class SOCIAL_HASHTAG_CACHE {
  
  var $api_options = array(

    'instagram' => array(
      'api_scheme' => 'https',
      'api_host' => 'api.instagram.com',
      'api_port' => '',
      'api_endpoint' => 'v1/tags/%string%/media/recent?',
      'auth_type' => 'client_id'
    ),
    'youtube' => array(
      'api_scheme' => 'https',
      'api_host' => 'gdata.youtube.com',
      'api_port' => '',
      'api_endpoint' => 'feeds/api/videos?q=%string%',
      'auth_type' => ''
    ),
    //
    // need to rebuild this for the new OAuth v1.1 API calls...
    'twitter' => array(
      'api_scheme' => 'https',
      'api_host' => 'api.twitter.com',
      'api_port' => '',
      'api_endpoint' => '1.1/search/tweets.json?include_entities=true&q=%string%',
      'auth_type' => ''
    ),
    // 
    // this may still work, but it's a little redundant, so hiding...
    // 'teleportd' => array(
    //   'api_scheme' => 'http',
    //   'api_host' => 'v1.api.teleportd.com',
    //   'api_port' => '8080',
    //   'api_endpoint' => 'search?string=%string%?',
    //   'auth_type' => 'apikey'
    // ),
  );

  var  $social_api_settings = array(
    'api_selected' => '',
    'api_authentication' => '',
    'search_name' => '',
    'string' => '',
    'period' => '',
    'location' => '',
    'cron_interval' => 'manual'
  );
  
  var $teleportd, $instagram, $twitter, $youtube;

  var $debug = false;

  var $global_options     = 'social_hashtag-global';
  var $social_api_options = 'social_hashtag-apis';
  
  function __construct() {
    $this->teleportd = new PLATFORM_TELEPORTD();
    $this->instagram = new PLATFORM_INSTAGRAM();
    $this->twitter = new PLATFORM_TWITTER();
    $this->youtube = new PLATFORM_YOUTUBE();
    $this->add_archive_template();
  }
  
  function choose_platform($name){
    switch( $name ) {
      case "instagram":
        $platform = $this->instagram;
        break;
      case "teleportd":
        $platform = $this->teleportd;
        break;
      case "twitter":
        $platform = $this->twitter;
        break;
      case "youtube":
        $platform = $this->youtube;
        break;
      default:
        //wp_die( __('Missing Platform class') );
    }
    return $platform;    
  }
  
  function add_archive_template(){
    add_filter('archive_template', array(&$this, 'social_hashtag_custom_archive_template'), 10, 2);
  }

  function social_hashtag_custom_archive_template($template) {
    global $wp_query;
    if (is_post_type_archive('social_hashtag')) {
        $template = SOCIAL_HASHTAG_PATH . 'lib/archive-social_hashtag.php';
    }
    return $template;
  }
  
  function get_available_postypes(){
    $args=array(
      'public'   => true
    ); 
    $output = 'names'; // names or objects, note names is the default
    $operator = 'or'; // 'and' or 'or'
    $post_types = get_post_types( $args, $output, $operator );
    return $post_types;
  }
 
  function save_option($id, $value) {
    $option_exists = (get_option($id, null) !== null);
    if ($option_exists) {
      update_option($id, $value);
    } else {
      add_option($id, $value);
    }
  }  
  
  function import_item($photos, $platform, $platform_options, $plugin_options){
    global $wpdb;
    
    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    $wordpress_uploads = wp_upload_dir();
    
    $blacklist = array_filter(explode(',', $plugin_options['blacklisted_users']));
    $whitelist = array_filter(explode(',', $plugin_options['whitelisted_users']));
    
    
    $retrieved = 0;
    $added = 0;
    $blocked = 0;
    
    try {
  
        foreach( $photos as $num => $photo ) {

          if( $plugin_options['max_items'] && $retrieved >= $plugin_options['max_items'] ){ break; }
          
          if( !$platform->parse_response($photo, $platform_options) ){ 
            if($plugin_options['debug_on']){
              social_hashtag_logging($platform->pic_full_title . "\n[not added] parsing error ", 1);
              continue; 
            }
          }
          $retrieved++;
          // Check to see if this user is whitelisted, skip to the next one if not   
          if( !empty($whitelist) ){    
            if(  array_search ( $platform->pic_handle, $whitelist ) === false ){
              continue;
            } 
          }

          // Check to see if this user is blacklisted, skip to the next one if so   
          if( !empty($blacklist) ){  
            if(  array_search ( $platform->pic_handle, $blacklist ) ){  
                continue; 
              }
          }

          // Check to see if this is a retweet, skip to the next on if so  
          if( @$platform_options['skip_retweets'] == 'Yes' ){
            if(  strstr ( $platform->pic_full_title , 'RT ') ){ if($plugin_options['debug_on']){
              social_hashtag_logging($platform->pic_full_title . "\n[not added] Retweet suspected : ".$platform->pic_full_title, 1); }
              continue; 
            }
          }
          // Check to see if we already have this photo, skip to the next one if we do
          $existing_photo = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'social_hashtag_sha' and meta_value = %s", $platform->pic_sha ) );          
          if( count($existing_photo) >= 1 ){ if($plugin_options['debug_on']){
            social_hashtag_logging($platform->pic_full_title . "\n[not added] we already have this one ", 1); }
            continue; 
          }
          else{ if($plugin_options['debug_on']){social_hashtag_logging($platform->pic_full_title, 1);} }

          $added++;
          // if there is a thumnail, process the thumbnail, and attach it
          if( $platform->pic_thumb != '' ) {
            $thumb_imagesize = getimagesize($platform->pic_thumb);
            $img_name = strtolower( preg_replace('/[\s\W]+/','-', $platform->pic_clean_title) );
            $image_file = file_get_contents( $platform->pic_thumb );
            $img_filetype = wp_check_filetype( $platform->pic_thumb, null );
            if( !$img_filetype['ext'] ){ 
              $img_filetype['ext'] = 'jpg';
              $img_filetype['type'] = 'image/jpeg';
            }           
            $img_path = $wordpress_uploads['path'] . "/" . $img_name . "." . $img_filetype['ext'];
            $img_sub_path = $wordpress_uploads['subdir'] . "/" . $img_name . "." . $img_filetype['ext'];
            file_put_contents($img_path , $image_file);
          }else{
            unset($full_imagesize);
          }
          // if there is a large image, process it
          if( $platform->pic_full != '' ) {
            $full_imagesize = getimagesize($platform->pic_full);
            if($platform->pic_post_content){
              $post_content = $platform->pic_post_content;
            }
            else{
              $post_content = "<img src='".$platform->pic_full ."' alt='".$platform->pic_clean_title ."' />"; 
            }
          }else{
            unset($thumb_imagesize);
            $post_content = $platform->pic_full_title;
          }
          $post = array(
           'post_author' => !empty($plugin_options['author_id'])?$plugin_options['author_id']:1,
           'post_date' => $platform->pic_mysqldate ,
           'post_type' => 'social_hashtag',
           'post_title' => $platform->pic_clean_title,
           'post_content' => $post_content,
           'post_status' => ($plugin_options['always_private'] == 'No' ? 'publish' : 'private' ),
          );
          $post_id = wp_insert_post( $post, true );
          add_post_meta($post_id, 'social_hashtag_sha', $platform->pic_sha, true);
          add_post_meta($post_id, 'social_hashtag_platform', $platform->pic_handle_platform, true);
          add_post_meta($post_id, 'social_hashtag_userhandle', $platform->pic_handle, true);
          add_post_meta($post_id, 'social_hashtag_location', $platform->pic_loc, true);
          //figure out how to dynamically add url for user_link
          add_post_meta($post_id, 'social_hashtag_user_link', "https://" . $platform->pic_platform . ".com/" . $platform->pic_handle, true);
          add_post_meta($post_id, 'social_hashtag_post_link', $platform->pic_link, true);
          add_post_meta($post_id, 'social_hashtag_timestamp', $platform->pic_mysqldate);
          if( $platform->vid_embed ) {
            add_post_meta($post_id, 'social_hashtag_vid_embed', $platform->vid_embed, true);
          }
          if( $platform->pic_full && $full_imagesize ) {
            add_post_meta($post_id, 'social_hashtag_full_url', $platform->pic_full, true);
            add_post_meta($post_id, 'social_hashtag_full_imagesize', ('w'.$full_imagesize[0].'xh'.$full_imagesize[1]), true);
          }
          if( $platform->pic_thumb && $thumb_imagesize ) {
            $attachment = array(
             'post_author' => 1,
             'post_date' => $platform->pic_mysqldate ,
             'post_type' => 'attachment',
             'post_title' => $platform->pic_clean_title,
             'post_parent' => $post_id,
             'post_status' => 'inherit',
             'post_mime_type' => $img_filetype['type'],
            );
            $attachment_id = wp_insert_post( $attachment, true );
            add_post_meta($attachment_id, '_wp_attached_file', $img_sub_path, true );
            add_post_meta($post_id, 'social_hashtag_thumb_url', $platform->pic_thumb, true);
            add_post_meta($post_id, 'social_hashtag_thumb_imagesize', ('w'.$thumb_imagesize[0].'xh'.$thumb_imagesize[1]), true);
          }
          $category_ids = array();
          $tag_ids = array();
          
          // link post to the platform 'category'
          $new_category = term_exists( $platform->pic_handle_platform, 'social_hashtag_categories');
          if( $new_category ){
            array_push( $category_ids, $new_category['term_id'] );
          }else{
            $new_term = wp_insert_term( $platform->pic_handle_platform, 'social_hashtag_categories');
            if(!$new_term['errors']){
              array_push( $category_ids, (int)$new_term['term_id'] );
            }
          }
          
          // link post to the api_search_name category
          if( $this->plugin_options['search_name'] ){
            $new_category = term_exists( $this->plugin_options[search_name], 'social_hashtag_categories');
            if( $new_category ){
              array_push( $category_ids, $new_category['term_id'] );
            }else{
              $new_term = wp_insert_term( $this->plugin_options[search_name], 'social_hashtag_categories');
              if(!$new_term['errors']){
                array_push( $category_ids, (int)$new_term['term_id'] );
              }
            }
          }
          // attach these categories to the new post
          if( count($category_ids) ) {
            wp_set_post_terms( $post_id, $category_ids, 'social_hashtag_categories' );
          }
          // attach these tags to the new post
          if( count($platform->pic_tags) ) {
            wp_set_object_terms($post_id, $platform->pic_tags, 'social_hashtag_tags');
          }
          
          // attach these tags to the new post
          if( count($platform->pic_strs) ) {
            wp_set_object_terms($post_id, $platform->pic_strs, 'social_hashtag_tags');
          }
        }

        return $platform->pic_handle_platform . " complete! ". $blocked . " blocked, " . $retrieved . " records retrieved, " . $added . " records added ";

    } catch (Exception $e) {
    }
    
  }
  
  function run_social_hashtag_query($num=0){
    
    $global_options = $this->get_social_hashtag_options(null, 'global');

    $all_api_options = $this->get_social_hashtag_options();
    $platform_options = $all_api_options[$num];
    $platform = $this->choose_platform($platform_options['api_selected']);
    
    $search_url = $this->build_api_search_url($num);
    
    $count_items = 0;

    $results = "";
    
    while( strlen($search_url) > 10 ){
      
      if( !empty($global_options['debug_on']) ){social_hashtag_logging('API url: ' . $search_url, 1);}

      if($platform_options['api_selected'] == "twitter"){
        $json_string=$this->oauth($search_url);
        $json_string = utf8_encode($json_string);
        $json_string=html_entity_decode($json_string);
        $response = json_decode($json_string);
        $photos = $platform->clean_response($response);
      }
      else{
       $json_string = $this->remote_get_contents($search_url);
       
       $response = json_decode($json_string);
       $photos = $platform->clean_response($response);
      }
      
      if( !is_array($photos) ){
        return "No results found";
        break;
      }
      
      if( !empty($global_options['debug_on']) ){social_hashtag_logging('total items returned from API: ' . count($photos), 1);}

      $results .= $this->import_item($photos, $platform, $platform_options, $global_options);
      
      $count_items = count($photos) + $count_items;
      if($count_items > $global_options['max_items'] && $global_options['max_items']){
        if( !empty($global_options['debug_on']) ){social_hashtag_logging('Max results settings hit: ' . $global_options['max_items'], 1);}
        break;
      }
      
      $platform->get_next_page($response, $search_url);
      $search_url = $platform->next_page;
      
    }

    return $results;

  }
  
  function build_api_search_url($option_num=0){
    
    $plugin_options = $this->get_social_hashtag_options();
    $api_settings = $this->get_social_hashtag_options($option_num);
    $api_options = $this->api_options[$api_settings['api_selected']];
  
    $query = $api_options['api_scheme'] . "://" . $api_options['api_host'];                                           //| http://v1.api.social_hashtag.com
    if( $api_options['api_port'] != '' ){ $query .= ":" . $api_options['api_port']; }                                 //| http://v1.api.social_hashtag.com:8080
    $query .= "/" . str_replace("%string%", urlencode($api_settings['string']), $api_options['api_endpoint'] );     //| http://v1.api.social_hashtag.com:8080/search?string=xxxx
    
    if( $api_options['auth_type'] ){
      $query .= "&" . $api_options['auth_type'] . "=" . $api_settings['api_authentication'];                         //| http://v1.api.social_hashtag.com:8080/search?string=xxxx&apikey=xxxxxxxxxx
    }
  
    if( $api_settings['api_selected'] == 'social_hashtag' ) {
      $query.= "&window=50";
      $query.= "&period=" . $api_settings['period'];    
      $query.= "&location=" . urlencode($api_settings['location']); 
    }

    if( $api_settings['api_selected'] == 'instagram' ) {
      //$query.= "&max_tag_id=1334773328821"; 
    }
    
    if( $api_settings['api_selected'] == 'twitter' ) {
      $query.= "&rpp=100";
      $query.= "&result_type=mixed&include_entities=true";
      $query.= "&until=" . urlencode($api_settings['period']); 
      $query.= "&geocode=" . urlencode($api_settings['location']); 
    }
    
    if( $api_settings['api_selected'] == 'youtube' ) {
      $query.= "&max-results=50";
      $query.= "&v=2&alt=jsonc";
    }
    
    return $query;
  }

  function get_social_hashtag_options($option_num=null, $type='api'){
    $options = get_option( ($type=='api'?$this->social_api_options:$this->global_options) );    
    if ( !empty($options[$option_num]) ) {
      return $options[$option_num];
    }
    elseif( is_numeric($option_num) ){
      return $this->social_api_settings;
    }
    else {
      return $options;
    }
  }  
  
  function add_cron_intervals( $schedules ) {
    $schedules['five_minutes'] = array(
      'interval' => 300,
      'display' => __('[social_hashtag-cache] Five Minutes')
    );
    $schedules['ten_minutes'] = array(
      'interval' => 600,
      'display' => __('[social_hashtag-cache] Ten Minutes')
    );
    $schedules['thirty_minutes'] = array(
      'interval' => 1800,
      'display' => __('[social_hashtag-cache] Thirty Minutes')
    );
    return $schedules;
  }
  
  // this function either runs from cron or manually from admin - if it runs without a num, it does all the searches
  function get_social_hashtag_pics($num=null) {
    if( is_numeric($num) ) {
      $this->run_social_hashtag_query($num);
    }
    else{
      $social_api_options = $this->get_social_hashtag_options();
      foreach( $social_api_options as $cache_num => $option_settings ){
        $this->run_social_hashtag_query($cache_num);
      }
    }
  }
  
  function remote_get_contents($url) {
    if (function_exists('curl_get_contents') AND function_exists('curl_init')){
      if($this->debug){print "\n- USING CURL \n";}
      return $this->curl_get_contents($url);
    }
    else{
      if($this->debug){print "\n- USING file_get_contents \n";}
      return file_get_contents($url);
    }
  }
  function oauth($url){
    //These are our constants.
    include_once("oauth.php");
 

$api_base = 'https://api.twitter.com/';
$bearer_token_creds = base64_encode($app_key.':'.$app_token);
 
//Get a bearer token.
$opts = array(
'http'=>array(
'method' => 'POST',
'header' => 'Authorization: Basic '.$bearer_token_creds."\r\n".
'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
'content' => 'grant_type=client_credentials'
)
);
 
$context = stream_context_create($opts);
$json = file_get_contents($api_base.'oauth2/token',false,$context);
 
$result = json_decode($json,true);
 
if (!is_array($result) || !isset($result['token_type']) || !isset($result['access_token'])) {
die("Something went wrong. This isn't a valid array: ".$json);
}
 
if ($result['token_type'] !== "bearer") {
die("Invalid token type. Twitter says we need to make sure this is a bearer.");
}
 
 
//Set our bearer token. Now issued, this won't ever* change unless it's invalidated by a call to /oauth2/invalidate_token.
//*probably - it's not documentated that it'll ever change.
$bearer_token = $result['access_token'];
 
//Try a twitter API request now.
$opts = array(
'http'=>array(
'method' => 'GET',
'header' => 'Authorization: Bearer '.$bearer_token
)
);
 
$context = stream_context_create($opts);
// $json = file_get_contents($api_base.'1.1/search/tweets.json?q=artspracticum%20or%20%23artspracticum&since_id=0',false,$context);

    
      if($this->debug){print "\n- USING file_get_contents \n";}
      return file_get_contents($url,false,$context);
    
  }

  function curl_get_contents($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
  }
  
    
  }
}