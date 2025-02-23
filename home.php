<?php

/**
 * The Home Page template file for Astra Child Theme.
 */

if (! defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

$home_ctas = get_field('home_page_ctas', 'options');

get_header(); ?>

<?php if (astra_page_layout() == 'left-sidebar') : ?>
  <?php get_sidebar(); ?>
<?php endif; ?>

<div id="primary" <?php astra_primary_class(); ?>>
  <?php
  astra_primary_content_top();

  // Instead of the default Astra loop, customize homepage content:
  if (have_posts()) :
  ?>
    <div class="astra-container">
      <?php display_featured_products(); ?>

      <!-- featured brands -->
      <?php display_featured_brands(); ?>

      <!-- filter form -->
      <?php product_filter(); ?>

      <!-- filtered products -->
      <div id="filtered-products" class="mb-10"></div>
    </div>
  <?php
  else :
    get_template_part('template-parts/content', 'none');
  endif;

  astra_primary_content_bottom();
  ?>
</div><!-- #primary -->

<?php if (astra_page_layout() == 'right-sidebar') : ?>
  <?php get_sidebar(); ?>
<?php endif; ?>

<?php get_footer(); ?>