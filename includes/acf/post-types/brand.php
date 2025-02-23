<?php
add_action('init', function () {
  register_post_type('brand', array(
    'labels' => array(
      'name' => 'Brands',
      'singular_name' => 'Brand',
      'menu_name' => 'Brands',
      'all_items' => 'All Brands',
      'edit_item' => 'Edit Brand',
      'view_item' => 'View Brand',
      'view_items' => 'View Brands',
      'add_new_item' => 'Add New Brand',
      'add_new' => 'Add New Brand',
      'new_item' => 'New Brand',
      'parent_item_colon' => 'Parent Brand:',
      'search_items' => 'Search Brands',
      'not_found' => 'No brands found',
      'not_found_in_trash' => 'No brands found in Trash',
      'archives' => 'Brand Archives',
      'attributes' => 'Brand Attributes',
      'insert_into_item' => 'Insert into brand',
      'uploaded_to_this_item' => 'Uploaded to this brand',
      'filter_items_list' => 'Filter brands list',
      'filter_by_date' => 'Filter brands by date',
      'items_list_navigation' => 'Brands list navigation',
      'items_list' => 'Brands list',
      'item_published' => 'Brand published.',
      'item_published_privately' => 'Brand published privately.',
      'item_reverted_to_draft' => 'Brand reverted to draft.',
      'item_scheduled' => 'Brand scheduled.',
      'item_updated' => 'Brand updated.',
      'item_link' => 'Brand Link',
      'item_link_description' => 'A link to a brand.',
    ),
    'public' => true,
    'show_in_rest' => true,
    'supports' => array(
      0 => 'title',
      1 => 'editor',
      2 => 'thumbnail',
      3 => 'custom-fields',
    ),
    'delete_with_user' => false,
  ));
});
