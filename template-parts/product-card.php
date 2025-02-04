<!-- from product-card -->
<article
  <?php
  echo wp_kses_post(
    astra_attr(
      'article-blog',
      array(
        'id'    => 'post-' . get_the_id(),
        'class' => join(' ', get_post_class(array('ast-grid-common-col', 'ast-full-width', 'ast-article-post', 'remove-featured-img-padding'))),
        'itemtype' => 'https://schema.org/CreativeWork',
        'itemscope' => 'itemscope',
      )
    )
  );
  ?>>
  <?php astra_entry_top(); ?>
  <div class="ast-post-format- blog-layout-4 ast-article-inner">
    <div class="post-content ast-grid-common-col">
      <?php if (has_post_thumbnail()) : ?>
        <div class="ast-blog-featured-section post-thumb ast-blog-single-element mb-4">
          <div class="post-thumb-img-content post-thumb">
            <a href="<?php the_permalink(); ?>">
              <?php
              the_post_thumbnail('large', array(
                'class' => 'attachment-large size-large wp-post-image',
                'alt'   => get_the_title(),
                'itemprop' => 'image',
                'decoding' => 'async',
                'fetchpriority' => 'high',
              ));
              ?>
            </a>
          </div>
        </div>
      <?php endif; ?>

      <!-- Display Hierarchical Categories -->
      <?php
      $current_language = apply_filters('wpml_current_language', NULL);
      $categories = get_the_terms(get_the_ID(), 'product-category'); // Use your taxonomy slug

      if ($categories && !is_wp_error($categories)) {
        // Calculate hierarchical depth for each term
        foreach ($categories as $category) {
          $depth = 0;
          $current_term = $category;
          while ($current_term->parent != 0) {
            $current_term = get_term($current_term->parent, 'product-category');
            $depth++;
          }
          $category->depth = $depth;
        }

        // Sort terms by depth (parents first, children later)
        usort($categories, function ($a, $b) {
          return $a->depth - $b->depth;
        });

        echo '<div class="post-categories mb-2">';
        $category_links = array();

        foreach ($categories as $category) {
          // Get translated term
          $translated_term_id = apply_filters(
            'wpml_object_id',
            $category->term_id,
            'product-category',
            true,
            $current_language
          );
          $translated_term = get_term($translated_term_id);

          // Generate translated term link
          $term_link = apply_filters(
            'wpml_permalink',
            get_term_link($translated_term_id, 'product-category'),
            $current_language
          );

          $category_links[] = sprintf(
            '<a class="text-xs opacity-50 hover:opacity-100" href="%s">%s</a>',
            esc_url($term_link),
            esc_html($translated_term->name)
          );
        }

        echo implode(' . ', $category_links);
        echo '</div>';
      }
      ?>

      <h2 class="entry-title ast-blog-single-element" itemprop="headline">
        <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
      </h2>

      <div class="entry-content clear" itemprop="text">
        <?php
        // Display the excerpt or custom content
        the_excerpt();
        ?>

        <div class="">
          <!-- if there's an offer, display the price after offer, if not, display the original price -->
          <?php if (get_field('offer')) : ?>
            <p class="text-sm text-red-500 !mb-0">
              <?php the_field('price_after_offer'); ?>&nbsp;<?php echo __('EGP', 'my-theme-child') ?>
            </p>
            <p class="text-sm !mb-0">
              <?php echo __('Instead of', 'my-theme-child') ?>
              <span class="line-through">
                <?php the_field('price'); ?>&nbsp;<?php echo __('EGP', 'my-theme-child') ?>
              </span>
            </p>
          <?php else : ?>
            <p class="text-sm"><?php the_field('price'); ?>&nbsp;<?php echo __('EGP', 'my-theme-child') ?></p>
          <?php endif; ?>
        </div>
      </div><!-- .entry-content .clear -->
    </div><!-- .post-content -->
  </div><!-- .blog-layout-4 -->
  <?php astra_entry_bottom(); ?>
</article><!-- #post-## -->