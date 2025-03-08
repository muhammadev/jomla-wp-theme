<?php

/**
 * Taxonomy Archive Template
 */

get_header(); // Load the theme's header
?>

<div id="primary" class="content-area">
  <main id="main" class="site-main">
    <?php
    $term = get_queried_object();
    $term_id = $term->term_id;
    $term_description = term_description($term_id);
    ?>

    <header class="page-header h-[25vh] flex flex-col justify-center items-center bg-black mb-12">
      <!-- Term Title -->
      <h1 class="page-title text-white text-3xl md:text-6xl text-center font-bold mb-0">
        <?php
        printf(esc_html__('Explore Our %s Collection', 'my-theme-child'), '<span class="inline-block -skew-x-12 p-4 bg-red-600">' . esc_html(single_term_title('', false)) . '</span>');
        ?>
      </h1>

      <!-- Term Description -->
      <?php if ($term_description) : ?>
        <div class="term-description">
          <?php echo wp_kses_post($term_description); ?>
        </div>
      <?php endif; ?>

      <!-- Term Featured Image (Optional, using ACF) -->
      <?php
      $term_image = get_field('term_image', 'category_' . $term_id);
      if ($term_image) :
      ?>
        <div class="term-image">
          <img src="<?php echo esc_url($term_image['url']); ?>" alt="<?php echo esc_attr($term_image['alt']); ?>">
        </div>
      <?php endif; ?>
    </header><!-- .page-header -->

    <?php
    if (have_posts()) :
    ?>
      <!-- Product Grid/List -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 product-archive-grid">
        <?php
        while (have_posts()) : the_post();
          // Use your existing content-blog.php template part
          get_template_part('template-parts/content', 'blog');
        endwhile;
        ?>
      </div><!-- .product-archive-grid -->
    <?php
      // Pagination
      jumla_pagination();

    else :
      // No posts found
      echo '<p class="text-center">' . __('No products found.', 'my-theme-child') . '</p>';
    endif;
    ?>
  </main><!-- #main -->
</div><!-- #primary -->

<?php
// get_sidebar(); // Load the theme's sidebar (if needed)
get_footer(); // Load the theme's footer
?>