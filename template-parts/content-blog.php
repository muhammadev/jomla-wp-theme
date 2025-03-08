<?php

/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 1.0.0
 */

?>
<?php astra_entry_before(); ?>
<?php
// if post is a product, display the product card
if (get_post_type() === 'product') {
  get_template_part('template-parts/product', 'card');
} else if (get_post_type() === 'brand') {
  $brand_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
  $brand_short_description = get_field('description');
  ?>
  <a class="px-4" href="<?php the_permalink(); ?>">
    <div class="brand-item p-4 flex flex-col items-center justify-center gap-4 border border-gray-200 rounded-md">
      <img class="w-[250px] h-[150px] rounded-full object-cover" src="<?php echo $brand_image; ?>" alt="<?php the_title(); ?>">
      <h3 class="text-center text-lg font-bold"><?php the_title(); ?></h3>
      <p class="text-center"><?php echo $brand_short_description; ?></p>
    </div>
  </a>
<?php
} else {
?>
  <article
    <?php
    echo wp_kses_post(
      astra_attr(
        'article-blog',
        array(
          'id'    => 'post-' . get_the_id(),
          'class' => join(' ', get_post_class()),
        )
      )
    );
    ?>>
    <?php astra_entry_top(); ?>
    <?php astra_entry_content_blog(); ?>
    <?php astra_entry_bottom(); ?>
  </article><!-- #post-## -->
<?php
}
?>
<?php astra_entry_after(); ?>