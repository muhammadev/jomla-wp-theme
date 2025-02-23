<?php
add_action('init', function () {
  register_taxonomy('color', array(
    0 => 'product',
  ), array(
    'labels' => array(
      'name' => 'Colors',
      'singular_name' => 'Color',
      'menu_name' => 'Colors',
      'all_items' => 'All Colors',
      'edit_item' => 'Edit Color',
      'view_item' => 'View Color',
      'update_item' => 'Update Color',
      'add_new_item' => 'Add New Color',
      'new_item_name' => 'New Color Name',
      'search_items' => 'Search Colors',
      'popular_items' => 'Popular Colors',
      'separate_items_with_commas' => 'Separate colors with commas',
      'add_or_remove_items' => 'Add or remove colors',
      'choose_from_most_used' => 'Choose from the most used colors',
      'not_found' => 'No colors found',
      'no_terms' => 'No colors',
      'items_list_navigation' => 'Colors list navigation',
      'items_list' => 'Colors list',
      'back_to_items' => '← Go to colors',
      'item_link' => 'Color Link',
      'item_link_description' => 'A link to a color',
    ),
    'public' => true,
    'show_in_menu' => true,
    'show_in_rest' => true,
  ));
});
