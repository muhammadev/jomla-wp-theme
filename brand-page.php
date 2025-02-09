<?php
/*
Template Name: Brand Page
Template Post Type: post, page, brand
*/

get_header();

// Get the brand mobile, whatsapp, telegram, and description
$brand_mobile = get_field('mobile');
$brand_whatsapp = get_field('whatsapp');
$brand_telegram = get_field('telegram');
$brand_description = get_field('description');
$brand_background_image = get_field('background_image');

$brand_mobile = ensure_a_plus_two($brand_mobile);
$brand_whatsapp = ensure_a_plus_two($brand_whatsapp);
$brand_telegram = ensure_a_plus_two($brand_telegram);

$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'products';

?>

<div class="min-h-screen h-fit w-full">
  <!-- Brand Header -->
  <div
    class="relative text-center h-2/5 min-h-[300px] flex flex-col justify-center items-center"
    <?php
    if ($brand_background_image) {
      echo 'style="background-image: url(' . $brand_background_image['url'] . '); background-size: cover; background-position: center; background-repeat: no-repeat;"';
    }
    ?>>

    <?php
    if ($brand_background_image) {
      echo '<div class="absolute inset-0 bg-black opacity-40"></div>';
    }
    ?>

    <h1 class="<?php echo ($brand_background_image ? 'text-white' : '') ?> text-3xl md:text-4xl lg:text-6xl my-4 font-bold z-10"><?php the_title(); ?></h1>
    <p class="<?php echo ($brand_background_image ? 'text-white' : '') ?> text-lg md:text-xl lg:text-2xl z-10">
      <?php echo $brand_description; ?>
    </p>
  </div>

  <div class="ast-container-fluid mx-auto">
    <!-- Brand's Contact Info -->
    <div class="my-12 mx-auto max-w-[80%] flex flex-wrap justify-center gap-5">
      <a class="flex items-center gap-2" href="tel:<?php echo $brand_mobile ?>">


        <i class="inline-block w-4 md:w-5 lg:w-6"><img src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/phone.svg" alt=""></i>
        <p dir="ltr" class="text-md md:text-lg lg:text-xl">
          <?php echo $brand_mobile; ?>
        </p>
      </a>

      <a class="flex items-center gap-2" href="https://wa.me/<?php echo $brand_whatsapp ?>" target="_blank">
        <i class="inline-block w-4 md:w-5 lg:w-6"><img src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/wa.svg" alt=""></i>
        <p dir="ltr" class="text-base md:text-lg lg:text-xl">
          <?php echo $brand_whatsapp; ?>
        </p>
      </a>

      <a class="flex items-center gap-2" href="https://t.me/<?php echo $brand_telegram; ?>" target="_blank">
        <i class="inline-block w-4 md:w-5 lg:w-6"><img src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/telegram.svg" alt=""></i>
        <p dir="ltr" class="text-md md:text-lg lg:text-xl">
          <?php echo $brand_telegram; ?>
        </p>
      </a>
    </div>

    <!-- Tabs -->
    <div class="w-full h-16 bg-gray-200 grid grid-cols-3 text-base md:text-lg lg:text-xl my-12">
      <a
        href="<?php echo get_permalink() . '?tab=products'; ?>"
        class="flex justify-center items-center h-full cursor-pointer hover:bg-custom-blue hover:text-white <?php echo $activeTab === 'products' ? 'bg-custom-blue text-white' : '' ?>">
        <?php echo __("Products", "my-theme-child"); ?>
      </a>
      <a
        href="<?php echo get_permalink() . '?tab=videos'; ?>"
        class="flex justify-center items-center h-full cursor-pointer hover:bg-custom-blue hover:text-white <?php echo $activeTab === 'videos' ? 'bg-custom-blue text-white' : '' ?>">
        <?php echo __("Videos", "my-theme-child"); ?>
      </a>
      <a
        href="<?php echo get_permalink() . '?tab=sales'; ?>"
        class="flex justify-center items-center h-full cursor-pointer hover:bg-custom-blue hover:text-white <?php echo $activeTab === 'sales' ? 'bg-custom-blue text-white' : '' ?>">
        <?php echo __("Sales", "my-theme-child"); ?>
      </a>
    </div>

    <!-- Products -->
    <?php
    while (have_posts()) : the_post();

      $brand_id = get_the_ID();
      $current_language = apply_filters('wpml_current_language', NULL);

      // Query related products
      $related_products = new WP_Query(array(
        'post_type'  => 'product',
        'meta_query' => array(
          array(
            'key'     => 'brand',
            'value'   => $brand_id,
            'compare' => 'LIKE'
          )
        ),
        'suppress_filters' => false,
      ));

      // Output videos and product details
      if ($related_products->have_posts()) : ?>
        <?php if ($activeTab === 'products') : ?>
          <div class="astra-container">
            <div class="ast-row custom-grid flex flex-row flex-wrap items-stretch">
              <?php

              while ($related_products->have_posts()) {
                $related_products->the_post();
                get_template_part('template-parts/product', 'card');
              }
              wp_reset_postdata(); // Add this to restore global post data
              ?>
            </div>
          </div>

        <?php elseif ($activeTab === 'videos') : ?>
          <!-- Brand's Product Videos -->
          <div class="brand-gallery">
            <div class="brand-products">
              <?php foreach ($related_products as $product) :
                $product_video_url = get_first_video_from_product($product->ID);
                if ($product_video_url) :
                  // Get product details (title, price, etc.)
                  $product_url = get_permalink($product->ID);
                  $product_title = get_the_title($product->ID);
                  $product_price = get_field('price', $product->ID); // Assuming price field exists
                  $product_description = get_the_excerpt($product->ID); // Or use a custom field for description
              ?>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4 bg-gray-100 md:bg-transparent rounded md:rounded-none overflow-hidden">
                    <!-- Video Section -->
                    <div class="col-span-2 max-h-[400px]">
                      <video controls class="h-full w-full max-w-[750px] object-contain">
                        <source src="<?php echo esc_url($product_video_url); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                      </video>
                    </div>

                    <!-- Product Details Section -->
                    <div class="flex-grow col-span-1 p-4 md:p-0">
                      <h1 class="text-3xl"><?php echo esc_html($product_title); ?></h1>

                      <div class="my-4">
                        <!-- if there's an offer, display the price after offer, if not, display the original price -->
                        <?php if (get_field('offer', $product->ID)) : ?>
                          <p class="text-lg md:text-2xl lg:text-3xl text-red-500">
                            <?php echo __('EGP', 'my-theme-child') ?>&nbsp;<?php the_field('price_after_offer', $product->ID); ?>
                          </p>
                          <p class="text-sm md:text-md lg:text-lg">
                            <?php echo __('Instead of', 'my-theme-child') ?>
                            <span class="line-through">
                              <?php echo __('EGP', 'my-theme-child') ?>&nbsp;<?php the_field('price', $product->ID); ?>
                            </span>
                          </p>
                        <?php else : ?>
                          <p class="text-xl md:text-2xl lg:text-3xl"><?php echo __('EGP', 'my-theme-child') ?> <?php the_field('price', $product->ID); ?></p>
                        <?php endif; ?>
                      </div>

                      <a href="<?php echo $product_url ?>" target="_blank" class="btn-get-it-now">
                        <?php echo __("View Product", "my-theme-child") ?>
                      </a>
                    </div>
                  </div>
              <?php endif;
              endforeach; ?>
            </div>
          </div>
        <?php elseif ($activeTab === 'sales') : ?>
          <div>sales</div>
        <?php endif; ?>
      <?php endif; ?>
    <?php endwhile; ?>

    <div class="brand-info">
      <div class="brand-description">
        <?php the_content(); ?>
      </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>