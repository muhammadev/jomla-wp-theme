<?php
function get_related_products_by_collections($collections, $current_product_id)
{
  $term_ids = wp_list_pluck($collections, 'term_id');

  // If WPML is active, get the translated term IDs for all languages
  // if (function_exists('wpml_object_id')) {
  //   $translated_term_ids = array();
  //   foreach ($term_ids as $term_id) {
  //     $translated_term_ids[] = wpml_get_object_id($term_id, 'product_category', true); // Get the translated term ID
  //   }
  //   $term_ids = $translated_term_ids;
  // }

  $args = array(
    'post_type'      => 'product',
    'posts_per_page' => 10,
    'post__not_in'   => array($current_product_id),
    'tax_query'      => array(
      array(
        'taxonomy' => 'collection', // Replace with your taxonomy
        'field'    => 'id',
        'terms'    => $term_ids,
      ),
    ),
    'suppress_filters' => false, // Allow WPML to filter by language
  );

  $other_products = new WP_Query($args);

  if ($other_products->have_posts()) {
?>
    <div class="related-products blog p-4 mt-10">
      <h1 class="text-center text-lg md:text-2xl lg:text-4xl font-bold"><?php echo esc_html__("Related Products", "my-theme-child") ?></h1>

      <!-- <div class="my-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 product-archive-grid"> -->
      <div class="my-10 product-archive-slider">
        <?php

        while ($other_products->have_posts()) {
          $other_products->the_post();

          get_template_part('template-parts/product', 'card'); // Adjust the path if needed
        }

        ?>
      </div>
    </div>
<?php
  }

  wp_reset_postdata();
}
