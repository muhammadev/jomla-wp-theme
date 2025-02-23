<?php
add_action('init', function () {
  register_taxonomy('product-tag', array(
    0 => 'product',
  ), array(
    'labels' => array(
      'name' => 'Product Tags',
      'singular_name' => 'Product Tag',
      'menu_name' => 'Product Tags',
      'all_items' => 'All Product Tags',
      'edit_item' => 'Edit Product Tag',
      'view_item' => 'View Product Tag',
      'update_item' => 'Update Product Tag',
      'add_new_item' => 'Add New Product Tag',
      'new_item_name' => 'New Product Tag Name',
      'search_items' => 'Search Product Tags',
      'popular_items' => 'Popular Product Tags',
      'separate_items_with_commas' => 'Separate product tags with commas',
      'add_or_remove_items' => 'Add or remove product tags',
      'choose_from_most_used' => 'Choose from the most used product tags',
      'not_found' => 'No product tags found',
      'no_terms' => 'No product tags',
      'items_list_navigation' => 'Product Tags list navigation',
      'items_list' => 'Product Tags list',
      'back_to_items' => '← Go to product tags',
      'item_link' => 'Product Tag Link',
      'item_link_description' => 'A link to a product tag',
    ),
    'public' => true,
    'show_in_menu' => true,
    'show_in_rest' => true,
  ));
});
