<?php
/*
 * @author      Bryan Shanaver <bryan[at]fiftyandfifty[dot]org>
 */

class PLATFORM_BASE {
  
  var $pic_sha;
  var $pic_mysqldate;
  var $pic_handle;
  var $pic_handle_platform;
  var $pic_handle_avatar;
  var $pic_username;
  var $pic_thumb;
  var $pic_full;
  var $pic_loc;
  var $pic_platform;
  var $pic_strs;
  var $pic_tags;
  var $pic_link;
  
  var $pic_full_title;
  var $pic_clean_title;

  var $pic_post_content;

  var $vid_embed;
  
  var $next_page;
  
  function unset_variables(){
    unset($this->pic_thumb);
    unset($this->pic_full);
    unset($this->pic_tags);
    unset($this->pic_loc);
  }

  function containsTLD($string) {
    preg_match(
      "/(AC($|\/)|\.AD($|\/)|\.AE($|\/)|\.AERO($|\/)|\.AF($|\/)|\.AG($|\/)|\.AI($|\/)|\.AL($|\/)|\.AM($|\/)|\.AN($|\/)|\.AO($|\/)|\.AQ($|\/)|\.AR($|\/)|\.ARPA($|\/)|\.AS($|\/)|\.ASIA($|\/)|\.AT($|\/)|\.AU($|\/)|\.AW($|\/)|\.AX($|\/)|\.AZ($|\/)|\.BA($|\/)|\.BB($|\/)|\.BD($|\/)|\.BE($|\/)|\.BF($|\/)|\.BG($|\/)|\.BH($|\/)|\.BI($|\/)|\.BIZ($|\/)|\.BJ($|\/)|\.BM($|\/)|\.BN($|\/)|\.BO($|\/)|\.BR($|\/)|\.BS($|\/)|\.BT($|\/)|\.BV($|\/)|\.BW($|\/)|\.BY($|\/)|\.BZ($|\/)|\.CA($|\/)|\.CAT($|\/)|\.CC($|\/)|\.CD($|\/)|\.CF($|\/)|\.CG($|\/)|\.CH($|\/)|\.CI($|\/)|\.CK($|\/)|\.CL($|\/)|\.CM($|\/)|\.CN($|\/)|\.CO($|\/)|\.COM($|\/)|\.COOP($|\/)|\.CR($|\/)|\.CU($|\/)|\.CV($|\/)|\.CX($|\/)|\.CY($|\/)|\.CZ($|\/)|\.DE($|\/)|\.DJ($|\/)|\.DK($|\/)|\.DM($|\/)|\.DO($|\/)|\.DZ($|\/)|\.EC($|\/)|\.EDU($|\/)|\.EE($|\/)|\.EG($|\/)|\.ER($|\/)|\.ES($|\/)|\.ET($|\/)|\.EU($|\/)|\.FI($|\/)|\.FJ($|\/)|\.FK($|\/)|\.FM($|\/)|\.FO($|\/)|\.FR($|\/)|\.GA($|\/)|\.GB($|\/)|\.GD($|\/)|\.GE($|\/)|\.GF($|\/)|\.GG($|\/)|\.GH($|\/)|\.GI($|\/)|\.GL($|\/)|\.GM($|\/)|\.GN($|\/)|\.GOV($|\/)|\.GP($|\/)|\.GQ($|\/)|\.GR($|\/)|\.GS($|\/)|\.GT($|\/)|\.GU($|\/)|\.GW($|\/)|\.GY($|\/)|\.HK($|\/)|\.HM($|\/)|\.HN($|\/)|\.HR($|\/)|\.HT($|\/)|\.HU($|\/)|\.ID($|\/)|\.IE($|\/)|\.IL($|\/)|\.IM($|\/)|\.IN($|\/)|\.INFO($|\/)|\.INT($|\/)|\.IO($|\/)|\.IQ($|\/)|\.IR($|\/)|\.IS($|\/)|\.IT($|\/)|\.JE($|\/)|\.JM($|\/)|\.JO($|\/)|\.JOBS($|\/)|\.JP($|\/)|\.KE($|\/)|\.KG($|\/)|\.KH($|\/)|\.KI($|\/)|\.KM($|\/)|\.KN($|\/)|\.KP($|\/)|\.KR($|\/)|\.KW($|\/)|\.KY($|\/)|\.KZ($|\/)|\.LA($|\/)|\.LB($|\/)|\.LC($|\/)|\.LI($|\/)|\.LK($|\/)|\.LR($|\/)|\.LS($|\/)|\.LT($|\/)|\.LU($|\/)|\.LV($|\/)|\.LY($|\/)|\.MA($|\/)|\.MC($|\/)|\.MD($|\/)|\.ME($|\/)|\.MG($|\/)|\.MH($|\/)|\.MIL($|\/)|\.MK($|\/)|\.ML($|\/)|\.MM($|\/)|\.MN($|\/)|\.MO($|\/)|\.MOBI($|\/)|\.MP($|\/)|\.MQ($|\/)|\.MR($|\/)|\.MS($|\/)|\.MT($|\/)|\.MU($|\/)|\.MUSEUM($|\/)|\.MV($|\/)|\.MW($|\/)|\.MX($|\/)|\.MY($|\/)|\.MZ($|\/)|\.NA($|\/)|\.NAME($|\/)|\.NC($|\/)|\.NE($|\/)|\.NET($|\/)|\.NF($|\/)|\.NG($|\/)|\.NI($|\/)|\.NL($|\/)|\.NO($|\/)|\.NP($|\/)|\.NR($|\/)|\.NU($|\/)|\.NZ($|\/)|\.OM($|\/)|\.ORG($|\/)|\.PA($|\/)|\.PE($|\/)|\.PF($|\/)|\.PG($|\/)|\.PH($|\/)|\.PK($|\/)|\.PL($|\/)|\.PM($|\/)|\.PN($|\/)|\.PR($|\/)|\.PRO($|\/)|\.PS($|\/)|\.PT($|\/)|\.PW($|\/)|\.PY($|\/)|\.QA($|\/)|\.RE($|\/)|\.RO($|\/)|\.RS($|\/)|\.RU($|\/)|\.RW($|\/)|\.SA($|\/)|\.SB($|\/)|\.SC($|\/)|\.SD($|\/)|\.SE($|\/)|\.SG($|\/)|\.SH($|\/)|\.SI($|\/)|\.SJ($|\/)|\.SK($|\/)|\.SL($|\/)|\.SM($|\/)|\.SN($|\/)|\.SO($|\/)|\.SR($|\/)|\.ST($|\/)|\.SU($|\/)|\.SV($|\/)|\.SY($|\/)|\.SZ($|\/)|\.TC($|\/)|\.TD($|\/)|\.TEL($|\/)|\.TF($|\/)|\.TG($|\/)|\.TH($|\/)|\.TJ($|\/)|\.TK($|\/)|\.TL($|\/)|\.TM($|\/)|\.TN($|\/)|\.TO($|\/)|\.TP($|\/)|\.TR($|\/)|\.TRAVEL($|\/)|\.TT($|\/)|\.TV($|\/)|\.TW($|\/)|\.TZ($|\/)|\.UA($|\/)|\.UG($|\/)|\.UK($|\/)|\.US($|\/)|\.UY($|\/)|\.UZ($|\/)|\.VA($|\/)|\.VC($|\/)|\.VE($|\/)|\.VG($|\/)|\.VI($|\/)|\.VN($|\/)|\.VU($|\/)|\.WF($|\/)|\.WS($|\/)|\.XN--0ZWM56D($|\/)|\.XN--11B5BS3A9AJ6G($|\/)|\.XN--80AKHBYKNJ4F($|\/)|\.XN--9T4B11YI5A($|\/)|\.XN--DEBA0AD($|\/)|\.XN--G6W251D($|\/)|\.XN--HGBK6AJ7F53BBA($|\/)|\.XN--HLCJ6AYA9ESC7A($|\/)|\.XN--JXALPDLP($|\/)|\.XN--KGBECHTV($|\/)|\.XN--ZCKZAH($|\/)|\.YE($|\/)|\.YT($|\/)|\.YU($|\/)|\.ZA($|\/)|\.ZM($|\/)|\.ZW)/i",
      $string,
      $M);
    $has_tld = (count($M) > 0) ? true : false;
    return $has_tld;
  }

function cleaner($url) {
  $U = explode(' ',$url);

  $W =array();
  foreach ($U as $k => $u) {
    if (stristr($u,".")) { //only preg_match if there is a dot    
      if ($this->containsTLD($u) === true) {
      unset($U[$k]);
      return $this->cleaner( implode(' ',$U));
    }      
    }
  }
  return implode(' ',$U);
}

  function removeEmoji($text) {

    $clean_text = "";

    // Match Emoticons
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clean_text = preg_replace($regexEmoticons, '', $text);

    // Match Miscellaneous Symbols and Pictographs
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clean_text = preg_replace($regexSymbols, '', $clean_text);

    // Match Transport And Map Symbols
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clean_text = preg_replace($regexTransport, '', $clean_text);

    return $clean_text;
  }
  
  function get_cron_intervals(){
    return array(
      'manual'            => 'manual',
      'every hour'        => 'cron60',
      'every 30 minutes'  => 'cron30',
      'every 15 minutes'  => 'cron15'
    );
  }


}

class PLATFORM_TWITTER Extends PLATFORM_BASE {  
  function parse_response($response_object, $plugin_options){
    $this->unset_variables();
    
    if( is_array($response_object->entities->media) ){
      $this->pic_thumb            = $response_object->entities->media[0]->media_url . ":thumb";
      $this->pic_full             = $response_object->entities->media[0]->media_url;
    }
    else{
      $no_pic = $this->removeEmoji($response_object->text);
    }
    
    if( $plugin_options['only_with_pics'] == 'Yes' && $no_pic ){
      return false;
    }
    
    // remove hash tags from title and create tags with them
    $pattern = "/\#([a-z1-9^\S])+/";
    preg_match_all($pattern, $response_object->text, $hashtags_in_title);

    if($plugin_options['exclude_hashtags'] == 'Yes'){
        $clean_title = $this->cleaner(preg_replace($pattern, "", $response_object->text));
    } else {
        $clean_title = $this->cleaner($response_object->text);
    }
    if($plugin_options['remove_emoji'] == 'Yes'){
        $clean_title = $this->removeEmoji($clean_title);
    }
    
    
    $this->pic_strs             = str_replace("#", "", $hashtags_in_title[0]);
    $this->pic_mysqldate        = date( 'Y-m-d H:i:s', strtotime($response_object->created_at) );
    $this->pic_handle           = $response_object->user->screen_name;
    $this->pic_username         = $response_object->user->name;
    $this->pic_sha              = $response_object->id;
    $this->pic_handle_avatar    = $response_object->user->profile_image_url;
    $this->pic_handle_platform  = 'twitter';
    $this->pic_platform         = 'twitter';
    $this->pic_link             = 'https://twitter.com/' . $response_object->user->screen_name . '/status/' . $response_object->id_str;

    if( $response_object->geo != '' ){
      $this->pic_loc              = implode(",", $response_object->geo);
    }
    if($response_object->entities->hashtags[0]){
      $this->pic_tags = array();
      foreach($response_object->entities->hashtags as $tag){
        array_push($this->pic_tags, $tag->text);
      }      
    }

    $this->pic_full_title         = $this->removeEmoji($response_object->text);
    $this->pic_clean_title        = $no_pic ? $no_pic : ($clean_title ? $clean_title : $this->pic_handle . ' using ' . $this->pic_handle_platform);


    return true;
  }
  function clean_response($response_object){
    return $response_object->statuses;
  }
  function get_next_page($response_object, $last_page){
    if(stristr($last_page, "page=")){
      $split = split("&", $last_page);
      foreach($split as $num => $url_piece){
        if(substr($url_piece, 0, 4) == 'page'){
          $split2 = split("=", $url_piece);
          $newurl .= 'page=' . ($split2[1] + 1) . '&';
        }else{
          $newurl .= $url_piece . '&';
        }
      }       
    }
    else{
     $newurl = $last_page . '&page=2';
    }
    $this->next_page = $newurl;
  }
  function admin_form($cache_settings, $cache_num, $api_options){
?>
    <div class="widgets-holder-wrap">
      <div class="sidebar-name">
  		  <div class="sidebar-name-arrow"><br></div>
  		  <h3><?php print ($cache_settings['search_name'] ? $cache_settings['search_name'] : $cache_settings['api_selected']) ?> <span id="removing-widget">Deactivate <span></span></span></h3>
  		</div>
      <div class="widget-holder">
        <table id="all-plugins-table" class="widefat">
          <tbody class="plugins">
            <tr>
            	<td>
            	  <label for="">API: </label>
            	  <select name="social_hashtag_cache[<?php print $cache_num ?>][api_selected]" class="disable_onchange" >
            	  <?php foreach( $api_options as $api => $array): ?>
            	    <option value="<?php print $api ?>" <?php selected( $cache_settings['api_selected'], $api ); ?>><?php print $api ?></option>
            	  <?php endforeach; ?>
            		</select> 
                <div style="float:right;text-align:right">
            	    <label for="">Key</label>
                  <input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][api_authentication_key]" value="<?php print $cache_settings['api_authentication_key'] ?>" class="regular-text disable_onchange" id="social_hashtag_api" /><br/>
                  <label for="">Token</label>
                  <input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][api_authentication_token]" value="<?php print $cache_settings['api_authentication_token'] ?>" class="regular-text disable_onchange" id="social_hashtag_api" />
                </div>
            	</td>
          	  <th scope="row">
                <label for="">Authorization</label><br/>
                <code>Twitter requires that you <a target="_blank" href="https://dev.twitter.com/apps">create an 'application'</a><br/>(it's free and takes 2 minutes)</code>
            	</th>
            </tr>
            <tr class="active">
              <td class="desc">
                <p><input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][search_name]" value="<?php print ($cache_settings['search_name'] ? $cache_settings['search_name'] : $cache_settings['api_selected']) ?>" class="regular-text disable_onchange" /></p>
              </td>
          	  <th scope="row">
            		<label for="">Search Name</label><br/>
            		<code>Name this search combination, and we'll create a category for them</code>
            	</th>
            </tr>
            <tr class="active">
              <td class="desc">
            	  <select name="social_hashtag_cache[<?php print $cache_num ?>][only_with_pics]" class="disable_onchange" >
            	    <option value="No" <?php selected( $cache_settings['only_with_pics'], 'No' ); ?>>No</option>
            	    <option value="Yes" <?php selected( $cache_settings['only_with_pics'], 'Yes' ); ?>>Yes</option>
            		</select> 
              </td>
          	  <th scope="row">
            		<label for="">Only Grab Tweets with Pics</label><br/>
            		<code>Skip text-only tweets</code>
            	</th>
            </tr>

            <tr class="active">
              <td class="desc">
                <select name="social_hashtag_cache[<?php print $cache_num ?>][exclude_hashtags]" class="disable_onchange" >
                  <option value="No" <?php selected( $cache_settings['exclude_hashtags'], 'No' ); ?>>No</option>
                  <option value="Yes" <?php selected( $cache_settings['exclude_hashtags'], 'Yes' ); ?>>Yes</option>
                </select> 
              </td>
              <th scope="row">
                <label for="">Exclude Hashtags</label><br/>
                <code>Will remove hashtagged words from retrieved posts.</code>
              </th>
            </tr>

            <tr class="active">
              <td class="desc">
                <select name="social_hashtag_cache[<?php print $cache_num ?>][remove_emoji]" class="disable_onchange" >
                  <option value="No" <?php selected( $cache_settings['remove_emoji'], 'No' ); ?>>No</option>
                  <option value="Yes" <?php selected( $cache_settings['remove_emoji'], 'Yes' ); ?>>Yes</option>
                </select> 
              </td>
              <th scope="row">
                <label for="">Remove Emoji</label><br/>
                <code>Will remove emoji unicode-range characters from retrieved posts.</code>
              </th>
            </tr>

            <tr class="active">
              <td class="desc">
            	  <select name="social_hashtag_cache[<?php print $cache_num ?>][skip_retweets]" class="disable_onchange" >
            	    <option value="No" <?php selected( $cache_settings['skip_retweets'], 'No' ); ?>>No</option>
            	    <option value="Yes" <?php selected( $cache_settings['skip_retweets'], 'Yes' ); ?>>Yes</option>
            		</select> 
              </td>
          	  <th scope="row">
            		<label for="">Skip Retweets</label><br/>
            		<code>Skip duplicate tweets that are (we 'RT' in the subject)</code>
            	</th>
            </tr>
            <tr class="active">
              <td class="desc">
                <p><input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][string]" value="<?php print $cache_settings['string'] ?>" class="regular-text disable_onchange" /></p>
              </td>
          	  <th scope="row">
            		<label for="">Search String</label><br/>
            		<code>Textual search based on tweet contents.</code><br />
            		<code>Supports: single word, multi word, tag (#***), mentions (@***), negate (-***)</code>
            		<code><a href="https://dev.twitter.com/docs/using-search" target="_blank">more search options</a>
            	</th>
            </tr>
            <tr class="active">
              <td class="desc">
                <p><input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][period]" value="<?php print $cache_settings['period'] ?>" class="regular-text disable_onchange" /></p>
              </td>
          	  <th scope="row">
            		<label for="">Until</label><br/>
            		<code>Returns tweets generated before the given date.</code><br />
            		<code>Date should be formatted as YYYY-MM-DD</code>
            	</th>
            </tr>
            <tr class="active">
              <td class="desc">
                <p><input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][location]" value="<?php print $cache_settings['location'] ?>" class="regular-text disable_onchange" /></p>
              </td>
          	  <th scope="row">
            		<label for="">Location</label><br/>
            		<code>[37.781157, -122.398720, 1mi]</code><br />
            		<code><a href="http://itouchmap.com/latlong.html" target="_blank">Find a Lat/Lon</a>
            	</th>
            </tr>
      			<tr>
              <td class="desc">
            	  <?php $cron_intervals = $this->get_cron_intervals(); ?>
            	  <select name="social_hashtag_cache[<?php print $cache_num ?>][cron_interval]" >
            	  <?php foreach( $cron_intervals as $type): ?>
            	    <option value="<?php print $type ?>" <?php selected( $cache_settings['cron_interval'], $type ); ?>><?php print $type ?></option>
            	  <?php endforeach; ?>
            		</select>
            	</td>
            	<th scope="row">
            		<label for="">Cron Interval</label>
            	</th>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th class="manage-column" scope="col">
            		<p><a class="button-secondary" id="run_manually" href="javascript:run_manually(<?php print $cache_num ?>);">Run This Query Manually</a> <!-- input type=checkbox value=true class=debug> <small>debug to javascript console</small --> <div id="run_manually_response"></div></p>            
              </th>
              <th class="manage-column" scope="col"> <div class="remove-div"><a href="javascript:delete_an_api(<?php print $cache_num ?>);" class="widget-control-remove">Remove</a></div>  </th>
            </tr>
          </tfoot>
    		</table>    
  		</div><!-- .widget-holder -->
		</div><!-- .widgets-holder-wrap -->
		<div style="width:100%;height:10px"></div>  
<?php  
  }
}


class PLATFORM_TELEPORTD Extends PLATFORM_BASE {  
  function parse_response($response_object, $plugin_options){
    $this->unset_variables();
    $this->pic_sha              = $response_object->sha;
    $this->pic_mysqldate        = date( 'Y-m-d H:i:s', $response_object->date );
    $this->pic_handle           = $response_object->user->handle;
    $this->pic_handle_platform  = $response_object->user->src;
    $this->pic_handle_avatar    = $response_object->user->pic;
    $this->pic_username         = $response_object->user->name;
    if( $response_object->thumb ){
      $this->pic_thumb            = $response_object->thumb;
    }
    if( $response_object->full ){
      $this->pic_full             = $response_object->full;
    }
    $this->pic_loc              = implode(",", $response_object->loc);
    $this->pic_platform         = $response_object->src;
    $this->pic_strs             = $response_object->qidx->strs;
    $this->pic_tags             = $response_object->qidx->tags;
    $this->pic_full_title       = implode(" ", $response_object->qidx->strs) . " " . implode(" $", $response_object->qidx->tags);
    $this->pic_clean_title      = trim($clean_title) != '' ? $clean_title : $this->pic_handle . ' using ' . $this->pic_handle_platform;
    return true;
  }
  function clean_response($response_object){
    return $response_object->results;
  }
  function get_next_page($response_object, $last_page){
    $this->next_page = '';
  }
  function admin_form($cache_settings, $cache_num, $api_options){
?>
    <div class="widgets-holder-wrap">
      <div class="sidebar-name">
  		  <div class="sidebar-name-arrow"><br></div>
  		  <h3><?php print ($cache_settings['search_name'] ? $cache_settings['search_name'] : $cache_settings['api_selected']) ?> <span id="removing-widget">Deactivate <span></span></span></h3>
  		</div>
      <div class="widget-holder">
        <table id="all-plugins-table" class="widefat">
          <tbody class="plugins">
            <tr>
            	<td>
            	  <label for="">API: </label>
            	  <select name="social_hashtag_cache[<?php print $cache_num ?>][api_selected]" class="disable_onchange" >
            	  <?php foreach( $api_options as $api => $array): ?>
            	    <option value="<?php print $api ?>" <?php selected( $cache_settings['api_selected'], $api ); ?>><?php print $api ?></option>
            	  <?php endforeach; ?>
            		</select> 
            	  <label for="">API Key: </label>
            		<input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][api_authentication]" value="<?php print $cache_settings['api_authentication'] ?>" class="regular-text disable_onchange" id="social_hashtag_api" /> 
            		<!-- a class="button-secondary" id="test_api" href="javascript:test_api(<?php print $cache_num ?>);">Test API</a> <div id="test_response"></div -->
            	</td>
          	  <th scope="row">
            	</th>
            </tr>
            <tr class="active">
              <td class="desc">
                <p><input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][search_name]" value="<?php print ($cache_settings['search_name'] ? $cache_settings[search_name] : $cache_settings['api_selected']) ?>" class="regular-text disable_onchange" /></p>
              </td>
          	  <th scope="row">
            		<label for="">Search Name</label><br/>
            		<code>Name this search combination, and we'll create a category for them</code>
            	</th>
            </tr>
            <tr class="active">
              <td class="desc">
                <p><input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][string]" value="<?php print $cache_settings['string'] ?>" class="regular-text disable_onchange" /></p>
              </td>
          	  <th scope="row">
            		<label for="">String</label><br/>
            		<code>Textual search based on picture comments.</code><br />
            		<code>Supports: single word, multi word, tag (#***), user (usr:***), source (src:***)</code>
            	</th>
            </tr>
            <tr class="active">
              <td class="desc">
                <p><input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][period]" value="<?php print $cache_settings['period'] ?>" class="regular-text disable_onchange" /></p>
              </td>
          	  <th scope="row">
            		<label for="">Period</label><br/>
            		<code>Filter results based on time. Array representation of begin, end.</code><br />
            		<code>Historical data: 20 days</code>
            		<code>unit: POSIX time</code>
            	</th>
            </tr>
            <tr class="active">
              <td class="desc">
                <p><input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][location]" value="<?php print $cache_settings['location'] ?>" class="regular-text disable_onchange" /></p>
              </td>
          	  <th scope="row">
            		<label for="">Location</label><br/>
            		<code>[34.19, -119.49, 5.0, 3.0]</code><br />
            		<code><a href="http://itouchmap.com/latlong.html" target="_blank">Find a Lat/Lon</a>
            	</th>
            </tr>
      			<tr>
              <td class="desc">
            	  <?php $cron_intervals = $this->get_cron_intervals(); ?>
            	  <select name="social_hashtag_cache[<?php print $cache_num ?>][cron_interval]" >
            	  <?php foreach( $cron_intervals as $type): ?>
            	    <option value="<?php print $type ?>" <?php selected( $cache_settings['cron_interval'], $type ); ?>><?php print $type ?></option>
            	  <?php endforeach; ?>
            		</select>
            	</td>
            	<th scope="row">
            		<label for="">Cron Interval</label>
            	</th>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th class="manage-column" scope="col">
            		<p><a class="button-secondary" id="run_manually" href="javascript:run_manually(<?php print $cache_num ?>);">Run This Query Manually</a> <!--input type=checkbox value=true class=debug> <small>debug to javascript console</small --> <div id="run_manually_response"></div></p>            
              </th>
              <th class="manage-column" scope="col"> <div class="remove-div"><a href="javascript:delete_an_api(<?php print $cache_num ?>);" class="widget-control-remove">Remove</a></div>  </th>
            </tr>
          </tfoot>
    		</table>    
  		</div><!-- .widget-holder -->
		</div><!-- .widgets-holder-wrap -->
		<div style="width:100%;height:10px"></div>  
<?php  
  }
}

class PLATFORM_INSTAGRAM Extends PLATFORM_BASE {
  function parse_response($response_object, $plugin_options){
    $this->unset_variables();

    // remove hash tags from title and create tags with them
    $pattern = "/\#([a-z1-9^\S])+/";
    preg_match_all($pattern, $response_object->caption->text, $hashtags_in_title);

    if($plugin_options['exclude_hashtags'] == 'Yes'){
        $clean_title = $this->cleaner(preg_replace($pattern, "", $response_object->caption->text));
    } else {
        $clean_title = $this->cleaner($response_object->caption->text);
    }
    if($plugin_options['remove_emoji'] == 'Yes'){
        $clean_title = $this->removeEmoji($clean_title);
    }
    
    $this->pic_tags             = $response_object->tags;
    $this->pic_loc              = !empty($response_object->location->latitude)?$response_object->location->latitude . "," . $response_object->location->longitude:'';
    $this->pic_strs             = str_replace("#", "", $hashtags_in_title[0]);
    $this->pic_mysqldate        = date( 'Y-m-d H:i:s', $response_object->created_time );
    $this->pic_thumb            = $response_object->images->thumbnail->url;
    $this->pic_full             = $response_object->images->standard_resolution->url;
    $this->pic_sha              = $response_object->id;
    $this->pic_handle_avatar    = $response_object->user->profile_picture;
    $this->pic_handle           = $response_object->user->username;
    $this->pic_handle_platform  = 'instagram';    
    $this->pic_username         = $response_object->user->full_name;
    $this->pic_platform         = 'instagram';
    $this->pic_full_title       = urlencode($this->removeEmoji($response_object->caption->text));
    $this->pic_clean_title      = trim($clean_title) != '' ? $clean_title : $this->pic_handle . ' using ' . $this->pic_handle_platform;
    $this->pic_link             = $response_object->link;
    return true;
  }
  function clean_response($response_object){
    return $response_object->data;
  }
  function get_next_page($response_object, $last_page){
    $this->next_page = $response_object->pagination->next_url;
  }
  function admin_form($cache_settings, $cache_num, $api_options){
?>
    <div class="widgets-holder-wrap">
      <div class="sidebar-name">
  		  <div class="sidebar-name-arrow"><br></div>
  		  <h3><?php print ($cache_settings['search_name'] ? $cache_settings['search_name'] : $cache_settings['api_selected']) ?> <span id="removing-widget">Deactivate <span></span></span></h3>
  		</div>
      <div class="widget-holder">
        <table id="all-plugins-table" class="widefat">
          <tbody class="plugins">
            <tr>
            	<td>
            	  <label for="">API: </label>
            	  <select name="social_hashtag_cache[<?php print $cache_num ?>][api_selected]" class="disable_onchange" >
            	  <?php foreach( $api_options as $api => $array): ?>
            	    <option value="<?php print $api ?>" <?php selected( $cache_settings['api_selected'], $api ); ?>><?php print $api ?></option>
            	  <?php endforeach; ?>
            		</select> 
            	  <label for="">Client ID: </label>
            		<input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][api_authentication]" value="<?php print $cache_settings['api_authentication'] ?>" class="regular-text disable_onchange" id="social_hashtag_api" /> 
            		<!-- a class="button-secondary" id="test_api" href="javascript:test_api(<?php print $cache_num ?>);">Test API</a> <div id="test_response"></div -->
            	</td>
          	  <th scope="row">
                <label for="">Authorization</label><br/>
                <code>Instagram requires that you <a target="_blank" href="http://instagram.com/developer/clients/manage/">register a 'client' application</a><br/>(it's free & takes about 5 minutes to set up)</code>
            	</th>
            </tr>

            <tr class="active">
              <td class="desc">
                <select name="social_hashtag_cache[<?php print $cache_num ?>][exclude_hashtags]" class="disable_onchange" >
                  <option value="No" <?php selected( $cache_settings['exclude_hashtags'], 'No' ); ?>>No</option>
                  <option value="Yes" <?php selected( $cache_settings['exclude_hashtags'], 'Yes' ); ?>>Yes</option>
                </select> 
              </td>
              <th scope="row">
                <label for="">Exclude Hashtags</label><br/>
                <code>Will remove hashtagged words from retrieved posts.</code>
              </th>
            </tr>

            <tr class="active">
              <td class="desc">
                <select name="social_hashtag_cache[<?php print $cache_num ?>][remove_emoji]" class="disable_onchange" >
                  <option value="No" <?php selected( $cache_settings['remove_emoji'], 'No' ); ?>>No</option>
                  <option value="Yes" <?php selected( $cache_settings['remove_emoji'], 'Yes' ); ?>>Yes</option>
                </select> 
              </td>
              <th scope="row">
                <label for="">Remove Emoji</label><br/>
                <code>Will remove emoji unicode-range characters from retrieved posts.</code>
              </th>
            </tr>

            <tr class="active">
              <td class="desc">
                <p><input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][search_name]" value="<?php print ($cache_settings['search_name'] ? $cache_settings['search_name'] : $cache_settings['api_selected']) ?>" class="regular-text disable_onchange" /></p>
              </td>
          	  <th scope="row">
            		<label for="">Search Name</label><br/>
            		<code>Name this search combination, and we'll create a category for them</code>
            	</th>
            </tr>
            <tr class="active">
              <td class="desc">
                <p><input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][string]" value="<?php print $cache_settings['string'] ?>" class="regular-text disable_onchange" /></p>
              </td>
          	  <th scope="row">
            		<label for="">Tag</label><br/>
            		<code>Textual search based on picture comments.</code><br />
            		<code>Supports: single word (#***)</code>
            	</th>
            </tr>
      			<tr>
              <td class="desc">
            	  <?php $cron_intervals = $this->get_cron_intervals(); ?>
            	  <select name="social_hashtag_cache[<?php print $cache_num ?>][cron_interval]" >
            	  <?php foreach( $cron_intervals as $type): ?>
            	    <option value="<?php print $type ?>" <?php selected( $cache_settings['cron_interval'], $type ); ?>><?php print $type ?></option>
            	  <?php endforeach; ?>
            		</select>
            	</td>
            	<th scope="row">
            		<label for="">Cron Interval</label>
            	</th>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th class="manage-column" scope="col">
            		<p><a class="button-secondary" id="run_manually" href="javascript:run_manually(<?php print $cache_num ?>);">Run This Query Manually</a> <!--input type=checkbox value=true class=debug> <small>debug to javascript console</small --> <div id="run_manually_response"></div></p>            
              </th>
              <th class="manage-column" scope="col"> <div class="remove-div"><a href="javascript:delete_an_api(<?php print $cache_num ?>);" class="widget-control-remove">Remove</a></div>  </th>
            </tr>
          </tfoot>
    		</table>    
  		</div><!-- .widget-holder -->
		</div><!-- .widgets-holder-wrap -->
		<div style="width:100%;height:10px"></div>  
<?php  
  }
}

class PLATFORM_YOUTUBE Extends PLATFORM_BASE {
  function parse_response($response_object, $plugin_options){
    $this->unset_variables();

    $date_split = split("T", $response_object->uploaded);
    $this->pic_mysqldate        = date( $date_split[0] . substr(0, 8, $date_split[1]) );
    
    $this->pic_tags             = $response_object->tags;
    // $this->pic_loc              = $response_object->location->latitude . "," . $response_object->location->longitude;
    // $this->pic_strs             = str_replace("#", "", $hashtags_in_title[0]);
    $this->pic_thumb            = $response_object->thumbnail->sqDefault;
    $this->pic_full             = $response_object->thumbnail->hqDefault;
    $this->pic_sha              = $response_object->id;
    // $this->pic_handle_avatar    = $response_object->user->profile_picture;
    $this->pic_handle           = $response_object->uploader;
    $this->pic_handle_platform  = 'youtube';    
    $this->pic_username         = $response_object->uploader;
    $this->pic_platform         = 'youtube';
    $this->pic_full_title       = $response_object->title;
    $this->pic_clean_title      = $response_object->title;
    $this->pic_post_content     = "<iframe id='ytplayer' type='text/html' width='640' height='360' src='https://www.youtube.com/embed/{$response_object->id}' frameborder='0' allowfullscreen></iframe>" . " <details>{$response_object->description}</details>";

    $this->vid_embed            = $response_object->player->default;
    return true;
  }
  function clean_response($response_object){
    return $response_object->data->items;
  }
  function get_next_page($response_object, $last_page){
    if(stristr($last_page, "start-index=")){
      $split = split("&", $last_page);
      foreach($split as $num => $url_piece){
        if(substr($url_piece, 0, 11) == 'start-index'){
          $split2 = split("=", $url_piece);
          //TODO sniff for the max-index, dont hardcode 50 here
          $newurl .= 'start-index=' . ($split2[1] + 50) . '&';
        }else{
          $newurl .= $url_piece . '&';
        }
      }       
    }
    else{
     $newurl = $last_page . '&start-index=51';
    }
    $this->next_page = $newurl;
  }
  function admin_form($cache_settings, $cache_num, $api_options){
?>
    <div class="widgets-holder-wrap">
      <div class="sidebar-name">
  		  <div class="sidebar-name-arrow"><br></div>
  		  <h3><?php print ($cache_settings['search_name'] ? $cache_settings['search_name'] : $cache_settings['api_selected']) ?> <span id="removing-widget">Deactivate <span></span></span></h3>
  		</div>
      <div class="widget-holder">
        <table id="all-plugins-table" class="widefat">
          <tbody class="plugins">
            <tr>
            	<td>
            	  <label for="">API: </label>
            	  <select name="social_hashtag_cache[<?php print $cache_num ?>][api_selected]" class="disable_onchange" >
            	  <?php foreach( $api_options as $api => $array): ?>
            	    <option value="<?php print $api ?>" <?php selected( $cache_settings['api_selected'], $api ); ?>><?php print $api ?></option>
            	  <?php endforeach; ?>
            		</select> 
            		<!-- a class="button-secondary" id="test_api" href="javascript:test_api(<?php print $cache_num ?>);">Test API</a> <div id="test_response"></div -->
            	</td>
          	  <th scope="row">
            	</th>
            </tr>
            <tr class="active">
              <td class="desc">
                <p><input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][search_name]" value="<?php print ($cache_settings['search_name'] ? $cache_settings['search_name'] : $cache_settings['api_selected']) ?>" class="regular-text disable_onchange" /></p>
              </td>
          	  <th scope="row">
            		<label for="">Search Name</label><br/>
            		<code>Name this search combination, and we'll create a category for them</code>
            	</th>
            </tr>
            <tr class="active">
              <td class="desc">
                <p><input type="text" name="social_hashtag_cache[<?php print $cache_num ?>][string]" value="<?php print $cache_settings['string'] ?>" class="regular-text disable_onchange" /></p>
              </td>
          	  <th scope="row">
            		<label for="">Search Term</label><br/>
            		<code>Textual search based on video tags & title.</code><br />
            	</th>
            </tr>
      			<tr>
              <td class="desc">
            	  <?php $cron_intervals = $this->get_cron_intervals(); ?>
            	  <select name="social_hashtag_cache[<?php print $cache_num ?>][cron_interval]" >
            	  <?php foreach( $cron_intervals as $type): ?>
            	    <option value="<?php print $type ?>" <?php selected( $cache_settings['cron_interval'], $type ); ?>><?php print $type ?></option>
            	  <?php endforeach; ?>
            		</select>
            	</td>
            	<th scope="row">
            		<label for="">Cron Interval</label>
            	</th>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th class="manage-column" scope="col">
            		<p><a class="button-secondary" id="run_manually" href="javascript:run_manually(<?php print $cache_num ?>);">Run This Query Manually</a> <!--input type=checkbox value=true class=debug> <small>debug to javascript console</small --> <div id="run_manually_response"></div></p>            
              </th>
              <th class="manage-column" scope="col"> <div class="remove-div"><a href="javascript:delete_an_api(<?php print $cache_num ?>);" class="widget-control-remove">Remove</a></div>  </th>
            </tr>
          </tfoot>
    		</table>    
  		</div><!-- .widget-holder -->
		</div><!-- .widgets-holder-wrap -->
		<div style="width:100%;height:10px"></div>  
<?php  
  }
}