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
      <div class="ast-blog-featured-section post-thumb ast-blog-single-element mb-4 relative">
        <div class="post-thumb-img-content post-thumb">
          <a href="<?php the_permalink(); ?>">
            <?php
            if (has_post_thumbnail()) {

              the_post_thumbnail('large', array(
                'class' => 'attachment-large size-large wp-post-image',
                'alt'   => get_the_title(),
                'itemprop' => 'image',
                'decoding' => 'async',
                'fetchpriority' => 'auto',
              ));
            } else {
            ?>
              <img src="https://placehold.co/600x400?text=JumlaBox&font=roboto" alt="JumlaBox">
            <?php
            }
            ?>
          </a>
        </div>

        <div class="floating-bar absolute top-7 start-2 z-10">
          <!-- display brand name if the current page is not a brand archive -->
          <?php
          $current_url = $_SERVER['REQUEST_URI'];
          if (!stripos($current_url, 'brand')) :
            $brand = get_field('brand');
          ?>
            <a class="" href="<?php echo get_permalink($brand); ?>">
              <span class="inline-block px-2 rounded-full bg-black text-white leading-normal text-xs font-semibold">
                <?php echo $brand->post_title; ?>
              </span>
            </a>
          <?php
          endif;

          $isSoldOut = get_field("sold_out");
          if ($isSoldOut) :
          ?>
            <span class="inline-block px-2 rounded-full bg-gray-400 text-white leading-normal text-xs font-semibold">
              <?php echo esc_html__("Sold Out", "my-theme-child"); ?>
            </span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Display Hierarchical Categories -->
      <?php
      $current_language = apply_filters('wpml_current_language', NULL);
      $categories = get_the_terms(get_the_ID(), 'collection'); // Use your taxonomy slug

      if ($categories && !is_wp_error($categories)) {
        // Calculate hierarchical depth for each term
        foreach ($categories as $category) {
          $depth = 0;
          $current_term = $category;
          while ($current_term->parent != 0) {
            $current_term = get_term($current_term->parent, 'collection');
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
            'collection',
            true,
            $current_language
          );
          $translated_term = get_term($translated_term_id);

          // Generate translated term link
          $term_link = apply_filters(
            'wpml_permalink',
            get_term_link($translated_term_id, 'collection'),
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
        $original_price = get_field('price');
        $has_offer = get_field('offer');
        $price_after_offer = get_field('price_after_offer');
        ?>

        <div class="">
          <!-- if there's an offer, display the price after offer, if not, display the original price -->
          <?php if ($has_offer) : $discount_percent = ceil(100 - (intval($price_after_offer) / intval($original_price)) * 100); ?>
            <p class="text-sm text-red-500 !mb-0">
              <?php echo $price_after_offer . ' ' . __('EGP', 'my-theme-child') . ' ' . $discount_percent . '%' ?>
            </p>
            <p class="text-sm !mb-0">
              <?php echo __('Instead of', 'my-theme-child') ?>
              <span class="line-through">
                <?php echo $original_price; ?>&nbsp;<?php echo __('EGP', 'my-theme-child') ?>
              </span>
            </p>
          <?php else : ?>
            <p class="text-sm"><?php echo $original_price; ?>&nbsp;<?php echo __('EGP', 'my-theme-child') ?></p>
          <?php endif; ?>
        </div>
      </div><!-- .entry-content .clear -->
    </div><!-- .post-content -->
  </div><!-- .blog-layout-4 -->
  <?php astra_entry_bottom(); ?>
</article><!-- #post-## -->