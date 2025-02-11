<?php

/**
 * Template Name: Sales Archive
 */

$paged = get_query_var('paged') ? get_query_var('paged') : 1;

$products_on_sale = new WP_Query(array(
  'post_type' => 'product',
  'meta_query' => array(
    array(
      'key' => 'offer',
      'value' => '1',
      'compare' => '='
    ),
  ),
  'posts_per_page' => 10,
  'paged'          => $paged,
  'suppress_filters' => false
));

// var_dump($products_on_sale->posts);

get_header();
?>

<?php if (astra_page_layout() == 'left-sidebar') : ?>
  <?php get_sidebar(); ?>
<?php endif; ?>

<div id="primary" <?php astra_primary_class(); ?>>

  <?php
  astra_primary_content_top();
  ?>

  <header class="py-16 px-8 text-center bg-red-700 mb-16">
    <h1 class="text-white font-bold text-4xl md:text-5xl lg:text-6xl !leading-normal">
      <i>
        <?php echo __('Exclusive Deals & Offers', 'my-theme-child') ?>
      </i>
    </h1>
  </header>

  <?php if ($products_on_sale->have_posts()) : ?>
    <div class="astra-container brand-products">
      <?php display_products($products_on_sale); ?>
    </div>
  <?php endif; ?>
</div>

<?php
get_footer();
?>