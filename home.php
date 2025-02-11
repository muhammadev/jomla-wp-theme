<?php

/**
 * The Home Page template file for Astra Child Theme.
 */

if (! defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

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
      <!-- Featured Ad Banner -->
      <a href="">
        <div class="relative text-center flex flex-col justify-center items-center h-[400px] mb-10 overflow-hidden"
          style="background-image: url(http://jomla.test/wp-content/uploads/2025/01/WhatsApp-Image-2025-01-17-at-17.49.59_b2ac5eb2.jpg); background-size: cover; background-position: center; background-repeat: no-repeat;">
          <!-- <img class="object-cover w-full h-full" width="100%" height="100%" src="" alt=""> -->
          <div class="absolute inset-0 bg-black opacity-40"></div>

          <h1 class="text-white text-3xl md:text-4xl lg:text-6xl my-4 font-bold z-10">Easy Shopping With Jumla Box</h1>
          <p class="text-white text-lg md:text-xl lg:text-2xl z-10">
            Best In Town
          </p>
        </div>
      </a>

      <!-- Featured Products -->
      <div class="mb-10">
        <h1 class="text-center text-xl md:text-2xl lg:text-4xl mb-10"><?php echo __("Latest Products", "my-theme-child") ?></h1>
        <div class="ast-row">
          <?php
          while (have_posts()) :
            the_post();
            get_template_part('template-parts/content', 'blog'); // Load a custom template part for home page
          endwhile;
          astra_pagination();
          ?>
        </div>
      </div>
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