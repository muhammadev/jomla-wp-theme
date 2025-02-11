<?php
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

function count_products_on_sale(WP_Query $products)
{
  return array_reduce($products->posts, function ($count, $product) {
    return $count + (get_field('offer', $product->ID) || false) ? 1 : 0;
  }, 0);
}

function display_products(WP_Query $products, bool $sales_only = false)
{

  if (!count($products->posts) || ($sales_only && count_products_on_sale($products) === 0)) {
    echo '<p class="text-center">' . __('No products here to see!', 'my-theme-child') . '</p>';
    return;
  }

?>
  <div class="ast-row custom-grid flex flex-row flex-wrap items-stretch">
    <?php
    while ($products->have_posts()) : $products->the_post();
      $product_id = get_the_ID();

      if ($sales_only) {
        $is_on_sale = get_field('offer', $product_id) || false;

        if ($is_on_sale) {
          get_template_part('template-parts/product', 'card');
        }
      } else {
        get_template_part('template-parts/product', 'card');
      }
    endwhile;
    ?>
  </div>
  <?php

  // Ensure pagination works with Astra
  global $wp_query;
  $wp_query = $products;

  if ($products->max_num_pages > 1) : ?>
    <nav class="flex justify-center mt-4">
      <ul class="flex items-center space-x-2">
        <?php
        astra_pagination();
        ?>
      </ul>
    </nav>
    <?php endif;

  wp_reset_postdata();
}

function display_product_videos(WP_Query $products)
{
  while ($products->have_posts()) : $products->the_post();
    $product_id = get_the_ID();
    $product_video_url = get_first_video_from_product($product_id);
    if ($product_video_url) :
      // Get product details (title, price, etc.)
      $product_url = get_permalink($product_id);
      $product_title = get_the_title($product_id);
      $product_price = get_field('price', $product_id);
      $product_price_after_sale = get_field('price_after_offer', $product_id);
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
            <?php if (get_field('offer', $product_id)) : ?>
              <p class="text-lg md:text-2xl lg:text-3xl text-red-500">
                <?php echo __('EGP', 'my-theme-child') ?>&nbsp;<?php echo $product_price_after_sale; ?>
              </p>
              <p class="text-sm md:text-md lg:text-lg">
                <?php echo __('Instead of', 'my-theme-child') ?>
                <span class="line-through">
                  <?php echo __('EGP', 'my-theme-child') ?>&nbsp;<?php echo $product_price; ?>
                </span>
              </p>
            <?php else : ?>
              <p class="text-xl md:text-2xl lg:text-3xl"><?php echo __('EGP', 'my-theme-child') ?> <?php the_field('price', $product_id); ?></p>
            <?php endif; ?>
          </div>

          <a href="<?php echo $product_url ?>" target="_blank" class="btn-get-it-now">
            <?php echo __("View Product", "my-theme-child") ?>
          </a>
        </div>
      </div>
<?php endif;
  endwhile;
}
