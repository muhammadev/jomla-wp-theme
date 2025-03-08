<?php

function display_featured_products()
{
  $args = array(
    'post_type' => 'product',
    'posts_per_page' => 4,
    'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => array(
      array(
        'key' => 'featured_product',
        'value' => '1',
        'compare' => '=',
      )
    ),
    'suppress_filters' => false,
  );

  $query = new WP_Query($args);

  if ($query->have_posts()) {
?>
    <div class="featured-products-slider">
      <?php
      while ($query->have_posts()) {
        $query->the_post();

        $product_id = get_the_ID();
        $product_title = get_the_title();
        $product_link = get_the_permalink();
        $product_image = get_the_post_thumbnail_url($product_id, 'full');

      ?>
        <!-- Featured Ad Banner -->
        <a href="<?php echo $product_link; ?>">
          <div class="relative text-center flex flex-col justify-center items-center h-[400px] overflow-hidden"
            style="background-image: url(<?php echo $product_image; ?>); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <div class="absolute inset-0 bg-black opacity-40"></div>

            <h1 class="text-white text-3xl md:text-4xl lg:text-6xl my-4 font-bold z-10"><?php echo $product_title; ?></h1>

            <div class="text-white text-lg md:text-xl lg:text-2xl z-10">
              <?php display_product_price(); ?>
            </div>
          </div>
        </a>
      <?php
      }
      ?>
    </div>
<?php
  }
}
