<?php

add_action( 'init', 'create_social_hashtag_posttype' );
function create_social_hashtag_posttype() {

  if( empty($social_hashtag_cache) ){
    $social_hashtag_cache = new SOCIAL_HASHTAG_CACHE();
  }

  $global_options = $social_hashtag_cache->get_social_hashtag_options(null, 'global');

  $slug = (!empty($global_options['slug'])?$global_options['slug']:$social_hashtag_cache->cpt_slug);

  register_post_type( 'social_hashtag',
    array(
      'labels' => array(
      'name' => __( 'Social Hashtags' ),
      'singular_name' => __( 'Social Hashtag' ),
      //'add_new' => __( 'Add New Social Hashtag' ),
      //'add_new_item' => __( 'Add New Social Hashtag' ),
      'edit_item' => __( 'Edit Social Hashtag' ),
      //'new_item' => __( 'Add New Social Hashtag' ),
      'view_item' => __( 'View Social Hashtag' ),
      'search_items' => __( 'Search Social Hashtags' ),
      'not_found' => __( 'No social Hashtags found' ),
      'not_found_in_trash' => __( 'No social hashtag found in trash' )
    ),
    'public' => true,
    'supports' => array( 'title', 'author', 'thumbnail', 'editor', 'custom-fields'),
    'capability_type' => 'post',
    'has_archive' => $slug,
    'hierarchical' => false,
    'taxonomies' => array('social_hashtag_categories', 'social_hashtag_tags'),
    'rewrite' => array('slug' => $slug),
    'menu_position' => '5'
    )
  );
  register_taxonomy(
  	'social_hashtag_categories', 'social_hashtag',
  	array(
  	'labels' => array(
  		'name' => 'Social Hashtag Categories',
  		'singular_name' => 'Social Hashtag Categories',
  		'search_items' => 'Search Social Hashtag Categories',
  		'popular_items' => 'Popular Social Hashtag Categories',
  		'all_items' => 'All Social Hashtag Categories',
  		'parent_item' => 'Parent Social Hashtag Categories',
  		'parent_item_colon' => 'Parent Social Hashtag Categories:',
  		'edit_item' => 'Edit Social Hashtag Category',
  		'update_item' => 'Update Social Hashtag Category',
  		'add_new_item' => 'Add New Social Hashtag Category',
  		'new_item_name' => 'New Social Hashtag Category Name'
  	),
  		'hierarchical' => true,
  		'label' => 'Social Hashtag Category',
  		'show_ui' => true,
  		'rewrite' => array( 'slug' => $slug . '-categories' ),
  	)
  );
  register_taxonomy(
  	'social_hashtag_tags', 'social_hashtag',
  	array(
  	'labels' => array(
  		'name' => 'Social Hashtag Tags',
  		'singular_name' => 'Social Hashtag Tags',
  		'search_items' => 'Search Social Hashtag Tags',
  		'popular_items' => 'Popular Social Hashtag Tags',
  		'all_items' => 'All Social Hashtag Tags',
  		'parent_item' => 'Parent Social Hashtag Tags',
  		'parent_item_colon' => 'Parent Social Hashtag Tags:',
  		'edit_item' => 'Edit Social Hashtag Tag',
  		'update_item' => 'Update Social Hashtag Tag',
  		'add_new_item' => 'Add New Social Hashtag Tag',
  		'new_item_name' => 'New Social Hashtag Tag Name'
  	),
  		'hierarchical' => false,
  		'label' => 'Social Hashtag Tag',
  		'show_ui' => true,
  		'update_count_callback' => '_update_post_term_count',
  		'rewrite' => array( 'slug' => $slug . '-tags' ),
  	)
  );
}


add_filter('manage_edit-social_hashtag_columns', 'social_hashtag_extra_columns');
function social_hashtag_extra_columns($columns) {
  $columns['social_hashtag_thumbnail'] = 'Thumbnail';
  return $columns;
}

add_action('manage_posts_custom_column',  'social_hashtag_show_extra_columns');
function social_hashtag_show_extra_columns($column) {
  global $post;
  switch ($column) {
    case 'social_hashtag_thumbnail':
      $social_hashtag_thumb_url = get_post_meta($post->ID, 'social_hashtag_thumb_url', true);
      echo "<img src='{$social_hashtag_thumb_url}' />";
      break;
  }
}