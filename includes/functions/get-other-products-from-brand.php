<?php

function get_other_products_from_brand($brand_id, $current_product_id)
{
  $args = array(
    'post_type' => 'product',
    'posts_per_page' => 10,
    'post__not_in' => array($current_product_id),
    'meta_query' => array(
      array(
        'key' => 'brand',
        'value' => $brand_id,
        'compare' => '=',
      ),
    ),
  );

  $query = new WP_Query($args);

  if ($query->have_posts()) {
?>
    <div class="related-products blog p-4 mt-10">
      <h1 class="text-center text-lg md:text-2xl lg:text-4xl font-bold">
        <?php printf(
          esc_html__(
            "See other products from %s",
            "my-theme-child"
          ),
          '<a href="' . get_permalink($brand_id) . '"><span class="featured-title">' . esc_html(get_the_title($brand_id)) . '</span></a>'
        )
        ?>
      </h1>

      <!-- <div class="my-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 product-archive-grid"> -->
      <div class="my-10 product-archive-slider">
        <?php

        while ($query->have_posts()) {
          $query->the_post();

          get_template_part('template-parts/product', 'card');
        }

        ?>
      </div>
    </div>
<?php
  }


  wp_reset_postdata();
}
