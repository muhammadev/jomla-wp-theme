<?php
add_action('init', function () {
  register_taxonomy('size', array(
    0 => 'product',
  ), array(
    'labels' => array(
      'name' => 'Sizes',
      'singular_name' => 'Size',
      'menu_name' => 'Sizes',
      'all_items' => 'All Sizes',
      'edit_item' => 'Edit Size',
      'view_item' => 'View Size',
      'update_item' => 'Update Size',
      'add_new_item' => 'Add New Size',
      'new_item_name' => 'New Size Name',
      'search_items' => 'Search Sizes',
      'popular_items' => 'Popular Sizes',
      'separate_items_with_commas' => 'Separate sizes with commas',
      'add_or_remove_items' => 'Add or remove sizes',
      'choose_from_most_used' => 'Choose from the most used sizes',
      'not_found' => 'No sizes found',
      'no_terms' => 'No sizes',
      'items_list_navigation' => 'Sizes list navigation',
      'items_list' => 'Sizes list',
      'back_to_items' => '← Go to sizes',
      'item_link' => 'Size Link',
      'item_link_description' => 'A link to a size',
    ),
    'public' => true,
    'show_in_menu' => true,
    'show_in_rest' => true,
  ));
});
