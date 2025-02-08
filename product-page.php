<?php
/*
Template Name: Product Page
Template Post Type: post, page, product
*/
get_header();

$current_product_id = get_the_ID();
$current_language = apply_filters('wpml_current_language', NULL);
$categories = get_the_terms($current_product_id, 'product-category');
// Get the current product's categories
$terms = wp_get_post_terms($current_product_id, 'product-category');
$product_colors = maybe_unserialize(get_field('product_colors'));
$color_galleries = array();

// Get the gallery from $product_colors
if ($product_colors) {
  foreach ($product_colors as $color) {
    $color_galleries[] = $color['gallery'];
  }
}
?>

<div>
  <div class="product-container w-full top-0 grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-12 p-4">
    <div class="product-gallery">
      <?php
      if ($product_colors) {
        foreach ($product_colors as $index => $product_color) {
          $gallery = $product_color['gallery'];
      ?>
          <!-- Main Slider -->
          <div class="main-slider-container" data-index="<?php echo $index; ?>">
            <div class="main-slider product-slider">
              <?php
              if ($gallery) {
                foreach ($gallery as $mediaIndex => $media) {
                  // Handle both array and ID inputs
                  if (is_array($media)) {
                    $media_id = $media['id']; // Assuming 'id' exists in the array
                    $alt = $media['alt'] ?? '';
                  } else {
                    $media_id = intval($media);
                    $alt = get_post_meta($media_id, '_wp_attachment_image_alt', true);
                  }

                  $media_url = wp_get_attachment_url($media_id);

                  // Check if the media is an image
                  if (wp_attachment_is_image($media_id)) {
                    echo '<div><img data-index="' . $mediaIndex . '" src="' . esc_url($media_url) . '" alt="' . esc_attr($alt) . '"></div>';
                  } else {
                    // Video element for non-image media
                    echo '<div><video controls>
                                <source src="' . esc_url($media_url) . '" type="' . esc_attr(get_post_mime_type($media_id)) . '">
                                Your browser does not support the video tag.
                            </video></div>';
                  }
                }
              }
              ?>
            </div>

            <!-- Viewer -->
            <div id="viewer" class="w-screen h-screen" style="display: none;" data-index="<?php echo $index; ?>">
              <?php
              if ($gallery) {
                foreach ($gallery as $mediaIndex => $media) {
                  // Handle both array and ID inputs
                  if (is_array($media)) {
                    $media_id = $media['id']; // Assuming 'id' exists in the array
                    $alt = $media['alt'] ?? '';
                  } else {
                    $media_id = intval($media);
                    $alt = get_post_meta($media_id, '_wp_attachment_image_alt', true);
                  }

                  $media_url = wp_get_attachment_url($media_id);

                  // Check if the media is an image
                  if (wp_attachment_is_image($media_id)) {
                    echo '<img class="w-full" src="' . esc_url($media_url) . '" alt="' . esc_attr($alt) . '">';
                  } else {
                    echo '<video controls>
                                <source src="' . esc_url($media_url) . '" type="' . esc_attr(get_post_mime_type($media_id)) . '">
                                Your browser does not support the video tag.
                            </video>';
                  }
                }
              }
              ?>
            </div>

          </div>

          <!-- Navigation Slider (Thumbnails) -->
          <div class="nav-slider" data-index="<?php echo $index; ?>">
            <?php
            if ($gallery) {
              foreach ($gallery as $mediaIndex => $media) {
                // Handle both array and ID inputs
                if (is_array($media)) {
                  $media_id = $media['id']; // Assuming 'id' exists in the array
                  $alt = $media['alt'] ?? '';
                } else {
                  $media_id = intval($media);
                  $alt = get_post_meta($media_id, '_wp_attachment_image_alt', true);
                }

                // Check if the media is an image
                if (wp_attachment_is_image($media_id)) {
                  $thumb_url = wp_get_attachment_image_url($media_id, 'thumbnail');
                  echo '<div><img src="' . esc_url($thumb_url) . '" alt="' . esc_attr($alt) . '"></div>';
                } else {
                  // For video thumbnails, get the featured image (or placeholder)
                  $thumb_url = wp_get_attachment_image_url($media_id, 'thumbnail') ?: esc_url(wp_upload_dir()['baseurl'] . '/custom-uploads/icons8-video-50.png');
                  echo '<div><img src="' . esc_url($thumb_url) . '" alt="Video Thumbnail"></div>';
                }
              }
            }
            ?>
          </div>
      <?php
        }
      }
      ?>
    </div>

    <div class="product-details block p-4 bg-gray-100 rounded">
      <!-- Display Hierarchical Categories -->
      <?php
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

      <h1 class="text-xl md:text-3xl lg:text-6xl"><?php the_title(); ?></h1>

      <!-- Price -->
      <div class="my-8 bg-gray-200 p-4 rounded">
        <!-- if there's an offer, display the price after offer, if not, display the original price -->
        <?php if (get_field('offer')) : ?>
          <p class="text-lg md:text-2xl lg:text-3xl text-red-500">
            <?php the_field('price_after_offer'); ?>&nbsp;<?php echo __('EGP', 'my-theme-child') ?>
          </p>
          <p class="text-sm md:text-md lg:text-lg">
            <?php echo __('Instead of', 'my-theme-child') ?>
            <span class="line-through">
              <?php the_field('price'); ?>&nbsp;<?php echo __('EGP', 'my-theme-child') ?>
            </span>
          </p>
        <?php else : ?>
          <p class="text-xl md:text-2xl lg:text-3xl"><?php the_field('price'); ?>&nbsp;<?php echo __('EGP', 'my-theme-child') ?></p>
        <?php endif; ?>
      </div>

      <!-- brand -->
      <div class="mt-4">
        <?php
        if (get_field('brand')) :
          $brand = get_field('brand');

          if ($current_language === 'en') {
            $brand_en = apply_filters('wpml_object_id', $brand->ID, 'brand', true, 'en');
            $brand = get_post($brand_en);
          }
        ?>
          <p class="text-base md:text-lg lg:text-xl">
            <?php echo __('Brand:', 'my-theme-child') ?>
            <a target="_blank" href="<?php echo get_permalink($brand->ID) ?>">
              <?php echo $brand->post_title; ?>
            </a>
          </p>
        <?php endif; ?>
      </div>

      <!-- Colors -->
      <div class="my-8">
        <div class="my-3">
          <p class="text-app-gray"><?php echo __('Color:', 'my-theme-child') ?> <span id="active_color"><?php echo $product_colors['0']['color_name'] ?></span></p>
        </div>

        <div class="flex flex-wrap">
          <?php
          if ($color_galleries) {
            foreach ($color_galleries as $color_index => $gallery) {
              // Get the first image URL of the gallery
              $first_image = !empty($gallery) ? $gallery[0] : '';
              $first_image_url = wp_get_attachment_url($first_image['id']);

              // Display the button with the first image as its background
              echo '<button title="' . $product_colors[$color_index]['color_name'] . '" data-index="' . $color_index . '" class="color-option border border-black ' . ($color_index === 0 ? 'active' : '') . '"><img src="' . esc_url($first_image_url) . '" alt=""/></button>';
            }
          }
          ?>
        </div>
      </div>

      <!-- brand's mobile number -->
      <?php
      if (get_field('brand')) :
        $brand = get_field('brand');
        $brand_mobile = get_field('mobile', $brand->ID);
      ?>
        <a class="hover:text-white" href="tel:<?php echo $brand_mobile; ?>">
          <div class="my-8 bg-green-500 text-white p-4 rounded">
            <p class="text-base md:text-lg lg:text-xl">
              <?php echo __('Mobile:', 'my-theme-child') ?>
              <?php echo $brand_mobile; ?>
            </p>
          </div>
        </a>
      <?php endif; ?>

      <div><?php the_content(); ?></div>
    </div>
  </div>

  <!-- related products -->
  <div class="related-products blog p-4 mt-10">
    <h1 class="text-center text-lg md:text-2xl lg:text-4xl"><?php echo __("Related Products", "my-theme-child") ?></h1>

    <?php
    if ($terms && !is_wp_error($terms)) {
      $term_ids = wp_list_pluck($terms, 'term_id');

      // If WPML is active, get the translated term IDs for all languages
      // if (function_exists('wpml_object_id')) {
      //   $translated_term_ids = array();
      //   foreach ($term_ids as $term_id) {
      //     $translated_term_ids[] = wpml_get_object_id($term_id, 'product_category', true); // Get the translated term ID
      //   }
      //   $term_ids = $translated_term_ids;
      // }

      $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'post__not_in'   => array($current_product_id),
        'tax_query'      => array(
          array(
            'taxonomy' => 'product-category', // Replace with your taxonomy
            'field'    => 'id',
            'terms'    => $term_ids,
          ),
        ),
        'suppress_filters' => false, // Allow WPML to filter by language
      );

      $other_products = new WP_Query($args);

      if ($other_products->have_posts()) {
        echo '<div class="my-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 product-archive-grid">';

        while ($other_products->have_posts()) {
          $other_products->the_post();

          get_template_part('template-parts/product', 'card'); // Adjust the path if needed
        }

        echo '</div>';
      } else {
        echo '<p>' . __("No related products found.", "my-theme-child") . '</p>';
      }

      wp_reset_postdata();
    }
    ?>

  </div>
</div>


<?php get_footer(); ?>