<?php

  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient permissions to access this page.') );
  }

  require_once( SOCIAL_HASHTAG_PATH . '/social_hashtag.php');

  if( empty($social_hashtag_cache) ){
    $social_hashtag_cache = new SOCIAL_HASHTAG_CACHE();
  }

  function social_hashtag_getset_options($social_hashtag_cache){

    // print "<pre>";
    // print_r($_POST);
    // print "</pre>";

    if (!empty($_REQUEST['_wpnonce']) && wp_verify_nonce($_REQUEST['_wpnonce'], "update-options") && !empty($_REQUEST['social_hashtag_global'])) {
      $social_hashtag_cache->save_option( $social_hashtag_cache->social_api_options, $_REQUEST['social_hashtag_cache'] );
      $social_hashtag_cache->save_option( $social_hashtag_cache->global_options, $_REQUEST['social_hashtag_global'] );
    }
    $social_api_options = $social_hashtag_cache->get_social_hashtag_options();

    if( !empty($_REQUEST['delete_api']) ){
      foreach( $social_api_options as $cache_num => $option_settings ){
        if( $_REQUEST['delete_api'] != $cache_num ){
          $rebuild_api_options[] = $option_settings;
        }
      }
      $social_hashtag_cache->save_option( $social_hashtag_cache->social_api_options, $rebuild_api_options );
      $social_api_options = $rebuild_api_options;
    }

    if( !empty($_REQUEST['add_api']) ){
      $social_api_options[] = array_merge( $social_hashtag_cache->social_api_settings, array('api_selected' => $_REQUEST['api_option'],'search_name' => $_REQUEST['api_option']) );
    }

    return $social_api_options;

  }

  $social_api_options = social_hashtag_getset_options($social_hashtag_cache);

  $global_options     = $social_hashtag_cache->get_social_hashtag_options(null, 'global');
  $slug               = !empty($global_options['slug'])?$global_options['slug']:$social_hashtag_cache->cpt_slug;
  $debug_on           = !empty($global_options['debug_on'])?$global_options['debug_on']:'0';
  $author_id          = !empty($global_options['author_id'])?$global_options['author_id']:1;
  $always_private     = !empty($global_options['always_private'])?$global_options['always_private']:'No';
  $max_items          = !empty($global_options['max_items'])?$global_options['max_items']:'50';
  $blacklisted_users  = !empty($global_options['blacklisted_users'])?$global_options['blacklisted_users']:'';
  $whitelisted_users  = !empty($global_options['whitelisted_users'])?$global_options['whitelisted_users']:'';

?>

  <style>
    th{width:600px;}
    .remove-div{text-align:right;margin-right:10px}
  </style>

  <script type="text/javascript">

    function sh_isIE8orIE9() {
      return !!( ( (/msie 8./i).test(navigator.appVersion) || (/msie 9./i).test(navigator.appVersion)  ) && !(/opera/i).test(navigator.userAgent) && window.ActiveXObject && XDomainRequest && !window.msPerformance );
    }

    function sh_make_ajax_request(url, data, request_type){
      var result;
      if ( sh_isIE8orIE9() ) {
        var xdr = new XDomainRequest();
        xdr.open("post", url);
        xdr.onload = function() {
          var dom = new ActiveXObject("Microsoft.XMLDOM");
          dom.async = false;
          dom.loadXML(xdr.responseText);
          var response = JSON.parse(dom.parseError.srcText);
          if(response.success){ sh_handle_response(xdr.responseText, request_type); }
          else{ sh_handle_response(xdr.responseText, request_type, true); }
        };
        xdr.send(jQuery.param(data));
      }
      else {
        result = jQuery.ajax({
          'type'       : 'post',
          'url'        : url,
          'data'       : data,
          'async'      : false,
          'error'      : function(response) { sh_handle_response(response, request_type, true); },
          'success'    : function(response) { sh_handle_response(response, request_type); }
        })
      }
    }

    function sh_display_errors(message){
      if( typeof(message) != 'string' ){
        alert("Error\n\nConnection Error");
      }
      else {
        alert( "Error\n\n" + message );
      }
    }

    function sh_handle_response(response, type, error){
      if(type === undefined){type = 'manual';}
      if(error === undefined){error = false;}
      try{
        var r = JSON.parse(response);
      }
      catch(e){
        var r = response;
      }
      if(error || !r.success){
        if( typeof(r.error) == 'undefined' ){r = {};r.error = {};r.error.message = 'unknown error [1]'}
        else if( r.error.message === undefined ){r.error.message = r.error;}
        sh_display_errors(r.error.message);
      }
      else{
        alert(r.message);
      }
    }

    jQuery("#social_hashtag_form").children(".widgets-holder-wrap").children(".sidebar-name").click(function() {
        jQuery(this).parent().toggleClass("closed")
    });
    function add_an_api(){
      jQuery('#social_hashtag_form #add_api').val('true');
      jQuery('#social_hashtag_form').submit()
    }
    function delete_an_api(num){
      jQuery('#social_hashtag_form #delete_api').val(num);
      jQuery('#social_hashtag_form').submit()
    }
    function test_api(num){
      var api_url = Array();
      jQuery.getJSON(api_url[num] + "&format=json&callback=?", function(data){
        //window.console && console.debug(api_url[num]);
        //window.console && console.debug(data);
        var success = false;
        try{if(data.meta.code == 200){success = true;}}catch(err){}
        try{if(data.status == 'OK'){success = true;}}catch(err){}
        try{if(data.results){success = true;}}catch(err){}
        if(success){
          alert('Success');
        }else{
          alert('Error');
        }
      });
    }
    function run_manually(num){
      if(jQuery('#social_hashtag_form .debug').attr('checked')){debug='true';}else{debug='';}
      data = {
        'action'  : 'social_hashtag_run_manually',
        'num'     : num,
        'debug'   : debug
      };
      sh_make_ajax_request(social_hashtag_ajax.ajaxurl, data);
    }
    jQuery(function() {
      jQuery('.disable_onchange').change(function() {
        jQuery('#test_api').attr("href", "javascript:alert('save changes first')");
        jQuery('#run_manually').attr("href", "javascript:alert('save changes first')");
      });
    });
  </script>

<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div>
<h2>Social Hashtags</h2>
<form action="options-general.php?page=social_hashtag" method="post" id="social_hashtag_form">
  <?php wp_nonce_field('update-options'); ?>
  <input type="hidden" name="delete_api" id="delete_api" />

<?php if( isset($social_api_options[0]) ): ?>

  <h3>Global Settings</h3>
  <p>Here is your <a href="<?php print bloginfo('url') ?>/<?php print $global_options['slug'] ?>" target="_blank">local archive page listing</a></p>

  <table id="all-plugins-table" class="widefat">
    <thead>
      <tr>
        <th class="manage-column" scope="col">All APIs Inherit These Settings</th>
        <th class="manage-column" scope="col"> </th>
      </tr>
    </thead>
    <tbody class="plugins">
      <tr class="active">
        <td class="desc">
          <p><input type="text" name="social_hashtag_global[slug]" value="<?php print $slug ?>" class="regular-text" /></p>
        </td>
        <th scope="row">
          <label for="">Permalink Slug</label><br/>
          <code>Default is 'social'<br>(Be sure to <a href="/wp-admin/options-permalink.php?settings-updated=true">reset your rewrites after changing this)</code>
        </th>

      <tr class="active">
        <td class="desc">
      	  <select name="social_hashtag_global[debug_on]" class="disable_onchange" >
      	    <option value="0" <?php selected( $debug_on, '0' ); ?>>No</option>
      	    <option value="1" <?php selected( $debug_on, '1' ); ?>>Yes</option>
      		</select>
        </td>
    	  <th scope="row">
      		<label for="">Turn debug ON</label><br/>
      		<code>Debugging info will be sent to the log<br/>(requires installing the <a href="http://wordpress.org/plugins/wordpress-logging-service/">WLS plugin</a>)</code>
      	</th>
      </tr>

      <tr class="active">
        <td class="desc">
      	  <select name="social_hashtag_global[always_private]" class="disable_onchange" >
      	    <option value="No" <?php selected( $always_private, 'No' ); ?>>No</option>
      	    <option value="Yes" <?php selected( $always_private, 'Yes' ); ?>>Yes</option>
      		</select>
        </td>
    	  <th scope="row">
      		<label for="">Set new items as private by default</label><br/>
      		<code>You will need to review new items and set them to public</code>
      	</th>
      </tr>
      <tr class="active">
        <td class="desc">
          <p><input type="text" name="social_hashtag_global[max_items]" value="<?php print ( is_numeric($max_items) ? $max_items : '0') ?>" class="regular-text disable_onchange" /></p>
        </td>
    	  <th scope="row">
      		<label for="">Max number of items to get per API</label><br/>
      		<code>Set to 0 for no max - this may take a long time to run since some services only let you grab 50 at a time.</code>
      	</th>
      </tr>

      <tr class="active">
        <td class="desc">
          <select name="social_hashtag_global[author_id]" class="disable_onchange" >
            <?php
              $blogusers = get_users( 'orderby=ID' );
              // Array of WP_User objects.
              foreach ( $blogusers as $user ) {
                echo '<option value="' . $user->ID . '"' . selected( $author_id, $user->ID) . '>' . $user->display_name . '</option>';
              }
            ?>
          </select>
        </td>
        <th scope="row">
          <label for="">Set Author</label><br/>
          <code>Will set the WP user to use as author of social-hashtags posts.</code>
        </th>
      </tr>

      <tr class="active">
        <td class="desc">
          <p><textarea name="social_hashtag_global[blacklisted_users]" cols="80" rows="4"><?php print $blacklisted_users ?></textarea></p>
        </td>
    	  <th scope="row">
      		<label for="">Blacklisted usernames/handles</label><br/>
      		<code>comma separated</code>
      	</th>
      </tr>

      <tr class="active">
        <td class="desc">
          <p><textarea name="social_hashtag_global[whitelisted_users]" cols="80" rows="4"><?php print $whitelisted_users ?></textarea></p>
        </td>
        <th scope="row">
          <label for="">Whitelisted usernames/handles</label><br/>
          <code>comma separated</code>
        </th>
      </tr>

    </tbody>
	</table>

<?php endif; ?>

	<div style="width:100%;height:20px"></div>

  <select name="api_option" style="width:100px">
<?php foreach( $social_hashtag_cache->api_options as $option => $option_settings ): ?>
    <option value="<?php print $option ?>"><?php print $option ?></option>
<?php endforeach; ?>
  </select>

  <input type="hidden" name="add_api" id="add_api" />
  <a href="javascript:add_an_api();" class="button-secondary">Add an API Source</a>

  <div style="width:100%;height:20px"></div>

  <h3>API Settings</h3>

<?php

  if( !empty($social_api_options[0]) ){
    $shortest_cron_interval = null;
    foreach( $social_api_options as $api_num => $api_settings){
      if( !empty($api_settings['api_selected']) ){
        $platform = $social_hashtag_cache->choose_platform($api_settings['api_selected']);
        if(is_object($platform)){
          $platform->admin_form($api_settings, $api_num, $social_hashtag_cache->api_options);
          $shortest_cron_interval = social_hashtag_get_shortest_cron($api_settings['cron_interval'], $shortest_cron_interval);
        }
      }
    }
  }

  if( !empty($shortest_cron_interval) ){
    social_hashtag_activate_cron($shortest_cron_interval);
  }
  else{
    social_hashtag_deactivate_cron();
  }

?>

  <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
  </p>
</form>
</div>
