<?php
function filter_products()
{
  $filter_data = isset($_POST['filter_data']) ? $_POST['filter_data'] : [];

  $args = array(
    'post_type' => 'product',
    'posts_per_page' => -1, // TODO: change to proper number
    'post_status' => 'publish',
    'tax_query' => [],
    'meta_query' => [],
    'suppress_filters' => false,
  );

  // add tax query for collection
  if (!empty($filter_data['collection'])) {
    $args['tax_query'][] = [
      'taxonomy' => 'collection',
      'field' => 'slug',
      'terms' => sanitize_text_field($filter_data['collection']),
    ];
  }

  // add tax query for color
  if (!empty($filter_data['color'])) {
    $args['tax_query'][] = [
      'taxonomy' => 'color',
      'field' => 'slug',
      'terms' => sanitize_text_field($filter_data['color']),
    ];
  }

  // add meta query for brand
  if (!empty($filter_data['brand'])) {
    $args['meta_query'][] = [
      'key' => 'brand',
      'value' => sanitize_text_field($filter_data['brand']),
      'compare' => '='
    ];
  }



  // Add meta query for price (from)
  if (!empty($filter_data['price_from'])) {
    $args['meta_query'][] = [
      'key' => 'price',
      'value' => floatval($filter_data['price_from']),
      'compare' => '>=',
      'type' => 'NUMERIC',
    ];
  }

  // Add meta query for price (to)
  if (!empty($filter_data['price_to'])) {
    $args['meta_query'][] = [
      'key' => 'price',
      'value' => floatval($filter_data['price_to']),
      'compare' => '<=',
      'type' => 'NUMERIC',
    ];
  }

  // Add meta query for sale
  if (!empty($filter_data['sale']) && $filter_data['sale'] == 1) {
    $args['meta_query'][] = [
      'key'     => 'offer',
      'value'   => 1,
      'compare' => '=',
      'type'    => 'NUMERIC',
    ];
  }

  // Run the query
  $query = new WP_Query($args);

  if ($query->have_posts()) :
?>
    <div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <?php
        while ($query->have_posts()) :
          $query->the_post();
          get_template_part('template-parts/content', 'blog'); // Load a custom template part for home page
        endwhile;

        ?>
      </div>
      <?php
      // Ensure pagination works with Astra
      global $wp_query;
      $wp_query = $query;

      if ($query->max_num_pages > 1) : ?>
        <nav class="flex justify-center mt-4">
          <ul class="flex items-center space-x-2">
            <?php
            astra_pagination();
            ?>
          </ul>
        </nav>
    <?php endif;
      wp_reset_postdata();

    else :
      echo 'No products found.';
    endif;
    ?>
    </div>
  <?php

  wp_die(); // Always call this to end AJAX requests
}

// Hook the action
add_action('wp_ajax_filter_products', 'filter_products'); // For logged-in users
add_action('wp_ajax_nopriv_filter_products', 'filter_products'); // For non-logged-in users
