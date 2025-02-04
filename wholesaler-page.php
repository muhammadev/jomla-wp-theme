<?php
/*
Template Name: Wholesaler Page
Template Post Type: post, page, wholesaler
*/

get_header();

// Get the wholesaler mobile, whatsapp, telegram, and description
$wholesaler_mobile = get_field('mobile');
$wholesaler_whatsapp = get_field('whatsapp');
$wholesaler_telegram = get_field('telegram');
$wholesaler_description = get_field('description');
$wholesaler_background_image = get_field('background_image');

function ensure_a_plus_two($number)
{
  // Ensure the phone number starts with +2
  if (preg_match('/^0/', $number)) {
    $number = '+2' . $number;
  } elseif (preg_match('/^2/', $number)) {
    $number = '+' . $number;
  }

  return $number;
}

$wholesaler_mobile = ensure_a_plus_two($wholesaler_mobile);
$wholesaler_whatsapp = ensure_a_plus_two($wholesaler_whatsapp);
$wholesaler_telegram = ensure_a_plus_two($wholesaler_telegram);

?>

<div class="min-h-screen h-fit w-full">
  <div
    class="relative text-center h-2/5 min-h-[300px] flex flex-col justify-center items-center"
    <?php
    if ($wholesaler_background_image) {
      echo 'style="background-image: url(' . $wholesaler_background_image['url'] . '); background-size: cover; background-position: center; background-repeat: no-repeat;"';
    }
    ?>>

    <?php
    if ($wholesaler_background_image) {
      echo '<div class="absolute inset-0 bg-black opacity-40"></div>';
    }
    ?>

    <h1 class="<?php echo ($wholesaler_background_image ? 'text-white' : '') ?> text-3xl md:text-4xl lg:text-6xl my-4 font-bold z-10"><?php the_title(); ?></h1>
    <p class="<?php echo ($wholesaler_background_image ? 'text-white' : '') ?> text-lg md:text-xl lg:text-2xl z-10">
      <?php echo $wholesaler_description; ?>
    </p>
  </div>

  <div class="container mx-auto">


    <div class="my-12 mx-auto max-w-[80%] flex flex-wrap justify-center gap-5">
      <a class="flex items-center gap-2" href="tel:<?php echo $wholesaler_mobile ?>">
        <i class="inline-block w-4 md:w-5 lg:w-6"><img src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/phone.svg" alt=""></i>
        <p dir="ltr" class="text-md md:text-lg lg:text-xl">
          <?php echo $wholesaler_mobile; ?>
        </p>
      </a>

      <a class="flex items-center gap-2" href="https://wa.me/<?php echo $wholesaler_whatsapp ?>" target="_blank">
        <i class="inline-block w-4 md:w-5 lg:w-6"><img src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/wa.svg" alt=""></i>
        <p dir="ltr" class="text-base md:text-lg lg:text-xl">
          <?php echo $wholesaler_whatsapp; ?>
        </p>
      </a>

      <a class="flex items-center gap-2" href="https://t.me/<?php echo $wholesaler_telegram; ?>" target="_blank">
        <i class="inline-block w-4 md:w-5 lg:w-6"><img src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/telegram.svg" alt=""></i>
        <p dir="ltr" class="text-md md:text-lg lg:text-xl">
          <?php echo $wholesaler_telegram; ?>
        </p>
      </a>
    </div>

    <div class="wholesaler-gallery">
      <?php
      while (have_posts()) : the_post();

        $wholesaler_id = get_the_ID();
        $current_language = apply_filters('wpml_current_language', NULL);

        // Query related products
        $related_products = get_posts(array(
          'post_type'  => 'product',
          'meta_query' => array(
            array(
              'key'     => 'wholesaler',
              'value'   => $wholesaler_id,
              'compare' => 'LIKE'
            )
          ),
          'suppress_filters' => false,
        ));

        // Function to extract the first video URL from product colors
        function get_first_video_from_product($product_id)
        {
          $videos = [];
          $product_colors = get_field('product_colors', $product_id);

          if ($product_colors) {
            foreach ($product_colors as $color) {
              if (!empty($color['gallery'])) {
                foreach ($color['gallery'] as $media) {
                  // Get media ID (handling both array and numeric ID cases)
                  $media_id = is_array($media) ? $media['id'] : intval($media);
                  $mime_type = get_post_mime_type($media_id);

                  // Store only video attachments, only the first video is needed
                  if (strpos($mime_type, 'video') !== false) {
                    $videos[] = wp_get_attachment_url($media_id);
                    // Only store the first video
                    return $videos[0];
                  }
                }
              }
            }
          }
          return null;
        }

        // Output videos and product details
        if ($related_products) : ?>
        <?php echo count($related_products); ?>
          <div class="wholesaler-products">
            <?php foreach ($related_products as $product) :
              echo $product->post_title . '<br>';
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
        <?php endif; ?>

      <?php endwhile; ?>
    </div>
    <div class="wholesaler-info">
      <div class="wholesaler-description">
        <?php the_content(); ?>
      </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>