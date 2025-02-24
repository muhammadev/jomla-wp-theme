<?php
/*
Template Name: Product Page
Template Post Type: post, page, product
*/
get_header();

$current_product_id = get_the_ID();
$current_language = apply_filters('wpml_current_language', NULL);
$collections = get_the_terms($current_product_id, 'collection');
$product_sizes = get_field('product_sizes');
$product_colors = maybe_unserialize(get_field('product_colors'));
$color_galleries = array();
$brand = get_field('brand');

// Get the gallery from $product_colors
if ($product_colors) {
  foreach ($product_colors as $color) {
    $color_galleries[] = $color['gallery'];
  }
}
?>

<div class="max-w-[1440px] mx-auto">
  <div class="product-container w-full top-0 px-8 py-4">
    <div class="block md:hidden">
      <!-- Display Hierarchical Categories -->
      <?php display_hierarchical_collections($collections, $current_language); ?>

      <h1 class="text-xl md:text-2xl lg:text-3xl font-semibold"><?php the_title(); ?></h1>

      <!-- Price -->
      <div class="my-4">
        <?php display_product_price(); ?>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-12">
      <div class="product-gallery md:sticky md:top-0 md:h-fit lg:col-span-2">
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
                      echo '<div><img loading="lazy" data-index="' . $mediaIndex . '" src="' . esc_url($media_url) . '" alt="' . esc_attr($alt) . '"></div>';
                    } else {
                      // Video element for non-image media
                      echo '<div><video loading="lazy" controls>
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
                      echo '<img loading="lazy" class="w-full" src="' . esc_url($media_url) . '" alt="' . esc_attr($alt) . '">';
                    } else {
                      echo '<video loading="lazy" controls>
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
                    echo '<div><img loading="lazy" src="' . esc_url($thumb_url) . '" alt="' . esc_attr($alt) . '"></div>';
                  } else {
                    // For video thumbnails, get the featured image (or placeholder)
                    $thumb_url = wp_get_attachment_image_url($media_id, 'thumbnail') ?: esc_url(wp_upload_dir()['baseurl'] . '/custom-uploads/icons8-video-50.png');
                    echo '<div><img loading="lazy" src="' . esc_url($thumb_url) . '" alt="Video Thumbnail"></div>';
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

      <!-- Product Details -->
      <div class="product-details block px-4 pb-4">
        <div class="hidden md:block">
          <!-- Display Hierarchical Categories -->
          <?php display_hierarchical_collections($collections, $current_language); ?>

          <!-- Product Title -->
          <h1 class="text-xl md:text-2xl lg:text-3xl font-semibold"><?php the_title(); ?></h1>

          <!-- Price -->
          <div class="my-4">
            <?php display_product_price(); ?>
          </div>
        </div>

        <!-- Colors -->
        <div class="mb-8">
          <div class="mb-3">
            <p class="text-app-gray"><?php echo esc_html__('Color:', 'my-theme-child') ?> <span id="active_color"><?php echo $product_colors['0']['color']->name ?></span></p>
          </div>

          <?php display_color_buttons($color_galleries, $product_colors); ?>
        </div>

        <?php if (!empty($product_sizes)) : ?>
        <!-- Available Sizes -->
        <div class="mb-8">
          <p class="text-app-gray mb-3"><?php echo esc_html__('Available Sizes:', 'my-theme-child') ?></p>

          <?php display_available_sizes($product_sizes); ?>
        </div>
        <?php endif; ?>

        <!-- brand -->
        <div class="mt-4">
          <?php
          if ($brand) :

            if ($current_language === 'en') {
              $brand_en = apply_filters('wpml_object_id', $brand->ID, 'brand', true, 'en');
              $brand = get_post($brand_en);
            }
          ?>
            <h2 class="text-lg md:text-xl lg:text-2xl">
              <?php echo esc_html__('Sold by:', 'my-theme-child') ?>
              <a class="featured-title" href="<?php echo get_permalink($brand->ID) ?>">
                <?php echo $brand->post_title; ?>
              </a>
            </h2>
          <?php endif; ?>
        </div>

        <div class="my-8">
          <button id="buy-now" data-modal="brand-contact-info-modal" class="modal-trigger w-full bg-red-600 hover:bg-red-700 focus:bg-red-700 text-white p-4 rounded">
            <?php echo esc_html__('Buy Now', 'my-theme-child') ?>
          </button>
        </div>

        <?php if (get_field('description')) : ?>
          <!-- Description -->
          <div class="my-8 rich-text-content">
            <!-- <h1 class="text-2xl font-semibold mb-4"><?php echo esc_html__('Description', 'my-theme-child') ?></h1> -->
            <div><?php the_content(); ?></div>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>

  <!-- related products -->
  <div class="max-w-[100vw] px-5">
    <?php
    if ($collections && !is_wp_error($collections)) {
      get_related_products_by_collections($collections, $current_product_id);
    }

    get_other_products_from_brand($brand->ID, $current_product_id);
    ?>
  </div>

  <?php brand_contact_info_modal($brand->ID); ?>

  <?php get_footer(); ?>