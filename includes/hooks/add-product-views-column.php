<?php
// Add a new column to the posts table in the WordPress dashboard
function add_product_views_column_to_products($columns)
{
  // Add a new column after the title column
  $columns['product_views'] = 'Product Views';
  return $columns;
}
add_filter('manage_product_posts_columns', 'add_product_views_column_to_products');

function make_product_views_column_sortable($columns)
{
  // Add a new column after the title column
  $columns['product_views'] = 'product_views';
  return $columns;
}
add_filter('manage_edit-product_sortable_columns', 'make_product_views_column_sortable');


function product_views_orderby( $query ) {
  if ( is_admin() && $query->is_main_query() ) {
      $orderby = $query->get( 'orderby' );
      if ( 'product_views' === $orderby ) {
          $query->set( 'meta_key', 'product_views' );
          $query->set( 'orderby', 'meta_value_num' );
      }
  }
}
add_action( 'pre_get_posts', 'product_views_orderby' );


// Display the checkbox for each post in the product views column
function display_product_views_column($column_name, $post_id)
{
  if ($column_name == 'product_views') {

    // ensure this is not a translated product and get the original product id
    $original_product_id = apply_filters('wpml_original_element_id', null, $post_id, 'post_product');
    if ($original_product_id && $original_product_id !== $post_id) {
      $post_id = $original_product_id;
    }

    // Get the post meta for the product views
    $product_views = (int) get_post_meta($post_id, 'product_views', true);

    echo '<span>' . $product_views . '</span>';
  }
}
add_action('manage_product_posts_custom_column', 'display_product_views_column', 10, 2);
