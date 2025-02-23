<?php
add_action('init', function () {
  register_taxonomy('collection', array(
    0 => 'product',
  ), array(
    'labels' => array(
      'name' => 'Collections',
      'singular_name' => 'Collection',
      'menu_name' => 'Collections',
      'all_items' => 'All Collections',
      'edit_item' => 'Edit Collection',
      'view_item' => 'View Collection',
      'update_item' => 'Update Collection',
      'add_new_item' => 'Add New Collection',
      'new_item_name' => 'New Collection Name',
      'parent_item' => 'Parent Category',
      'parent_item_colon' => 'Parent Collection:',
      'search_items' => 'Search Collections',
      'not_found' => 'No collections found',
      'no_terms' => 'No collections',
      'filter_by_item' => 'Filter by collection',
      'items_list_navigation' => 'Collections list navigation',
      'items_list' => 'Collections list',
      'back_to_items' => '← Go to collections',
      'item_link' => 'Collection Link',
      'item_link_description' => 'A link to a collection',
    ),
    'public' => true,
    'hierarchical' => true,
    'show_in_menu' => true,
    'show_in_rest' => true,
  ));
});
