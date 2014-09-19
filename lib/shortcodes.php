<?php

// this is under construction :)

// After a fair amount of sleuthing, I am convinced that this file does not do anything -- Michael Mandiberg, June, 2014

add_shortcode('social_hashtag_pics', 'display_social_hashtag_pics');
function display_social_hashtag_pics( $atts ) {
	global $post;
	
	$defaults = shortcode_atts( 
	  array(
	  'cols' => 8,
	  'rows' => 6), 
	  $atts 
	);

  // cool masonry / fancybox display - http://www.queness.com/post/8881/create-a-twitter-feed-with-attached-images-from-media-entities
  
  $paged = ( get_query_var( 'paged' ) ) ? get_query_var('paged') : 1;
  $args = array(
    'post_type' => 'social_hashtag',
    'posts_per_page' => ($defaults['rows'] * $defaults['cols']),
    'orderby' => 'date',
    'order' => 'DESC',
    'paged' => $paged
  );
  $get_posts = new WP_Query($args);

?>  

<?php //print_r($get_posts) ?>
    
<?php if( $get_posts->have_posts() ): ?>

<!-- 	<div style='margin:20px;display:block;min-height:20px'>
		<div class="next"><?php next_posts_link('Older Entries &raquo;', $get_posts->max_num_pages) ?></div>
  	<div class="prev"><?php previous_posts_link('&laquo; Newer Entries', $get_posts->max_num_pages) ?></div>
  </div> -->

  <div id='social_hashtags'>
  	<ul>
		<?php while( $get_posts->have_posts() ):  $get_posts->the_post(); ?>
			<?php	
	      $social_hashtag_userhandle = get_post_meta($post->ID, 'social_hashtag_userhandle', true);
	      $social_hashtag_thumb_url = get_post_meta($post->ID, 'social_hashtag_thumb_url', true);
	      $social_hashtag_full_url = get_post_meta($post->ID, 'social_hashtag_full_url', true);
	      $social_hashtag_platform = get_post_meta($post->ID, 'social_hashtag_platform', true);
	      $social_hashtag_thumb_imagesize = get_post_meta($post->ID, 'social_hashtag_thumb_imagesize', true);
			?>      
      <li class='<?php print $social_hashtag_platform  . " " . $social_hashtag_thumb_imagesize ?>'>
      	<a href='<?php print $social_hashtag_full_url ?>' target=_blank><img src='<?php print $social_hashtag_thumb_url ?>' title='<?php print $post->post_title ?>' alt='<?php print $post->post_title ?>' /></a>
      </li>
		<?php  endwhile; ?>
		</ul>
	</div>
 
<?php  endif; ?>
  
<script type="text/javascript">
	// jQuery(document).ready(function() {
	//   jQuery('#map_canvas').gmap().bind('init', function(evt, map) {
	//   	jQuery('#map_canvas').gmap('getCurrentPosition', function(position, status) {
	//   		if ( status === 'OK' ) {
	//   			var clientPosition = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	//   			jQuery('#map_canvas').gmap('addMarker', {'position': clientPosition, 'bounds': true});
	//   			jQuery('#map_canvas').gmap('addShape', 'Circle', { 
	//   				'strokeWeight': 0, 
	//   				'fillColor': "#008595", 
	//   				'fillOpacity': 0.25, 
	//   				'center': clientPosition, 
	//   				'radius': 5, 
	//   				'clickable': false 
	//   			});
	//   		}
	//   	});   
	//   });
	// });
</script>
<div id="map_canvas"></div>

<?php } ?>