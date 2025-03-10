<?php
/*
Template Name: Brand Page
Template Post Type: post, page, brand
*/

get_header();

// Get the brand custom fields
$brand_logo = get_the_post_thumbnail_url(get_the_ID(), 'full');
$brand_background_image = get_field('background_image');
$brand_description = get_field('description');
$brand_mobile = get_field('mobile');
$brand_whatsapp = get_field('whatsapp');
$brand_telegram = get_field('telegram');

$brand_mobile = ensure_a_plus_two($brand_mobile);
$brand_whatsapp = ensure_a_plus_two($brand_whatsapp);
$brand_telegram = ensure_a_plus_two($brand_telegram);

$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'products';

while (have_posts()) : the_post();
  $brand_id = get_the_ID();
  $paged = get_query_var('paged') ? get_query_var('paged') : 1;

  // Query related products
  $related_products = new WP_Query(array(
    'post_type'  => 'product',
    'posts_per_page' => -1,
    'paged'          => $paged,
    'meta_query' => array(
      array(
        'key'     => 'brand',
        'value'   => $brand_id,
        'compare' => '='
      )
    ),
    'suppress_filters' => false,
  ));

  $number_of_products_on_sale = count_products_on_sale($related_products);
  $number_of_products_with_videos = count_products_with_videos($related_products);

endwhile;
?>

<div class="min-h-screen h-fit w-full">

  <!-- Brand Header -->
  <div
    class="relative h-2/5 min-h-[300px] flex justify-center lg:justify-start items-end"
    <?php
    if ($brand_background_image) {
      echo 'style="background-image: url(' . $brand_background_image['url'] . '); background-size: cover; background-position: center; background-repeat: no-repeat;"';
    }
    ?>>

    <?php
    if ($brand_background_image) {
      echo '<div class="absolute inset-0 bg-black opacity-60"></div>';
    }
    ?>

    <div class="container mx-auto z-10 flex flex-col lg:flex-row justify-center lg:justify-start items-center gap-8 p-4">
      <?php if ($brand_logo) : ?>
        <div class="min-w-[150px] lg:min-w-[200px] bg-gray-500/50 p-4 rounded-full lg:rounded-lg">
          <img class="rounded-full lg:rounded-lg aspect-square object-cover w-[150px] lg:w-[200px]" width="200px" src="<?php echo $brand_logo; ?>" alt="">
        </div>
      <?php endif; ?>

      <div class="text-center lg:text-start">
        <h1 class="text-white text-lg md:text-xl lg:text-3xl mb-4 font-bold"><?php the_title(); ?></h1>
        <p class="text-white text-base md:text-lg lg:text-xl">
          <?php echo $brand_description; ?>
        </p>
      </div>
    </div>

  </div>

  <div class="container mx-auto px-5">
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

    <!-- Description -->
    <div class="brand-description rich-text-content">
      <?php the_content(true, true); ?>
    </div>

    <?php if ($number_of_products_with_videos > 0 || $number_of_products_on_sale) : ?>
      <!-- Tabs -->
      <div class="brand-tabs w-full h-16 flex justify-center text-base md:text-lg lg:text-xl my-12 rounded-lg overflow-hidden">
        <a
          href="?tab=products"
          class="flex-grow flex justify-center items-center h-full cursor-pointer bg-gray-200 hover:bg-primary hover:text-white">
          <?php echo __("All Products", "my-theme-child"); ?>
        </a>
        <?php if ($number_of_products_with_videos > 0) : ?>
          <a
            href="?tab=videos"
            class="flex-grow flex justify-center items-center h-full cursor-pointer bg-gray-200 hover:bg-primary hover:text-white">
            <?php echo __("Videos", "my-theme-child"); ?>
          </a>
        <?php endif; ?>
        <?php if ($number_of_products_on_sale > 0) : ?>
          <a
            href="?tab=sale"
            class="flex-grow flex justify-center items-center h-full cursor-pointer bg-gray-200 hover:bg-primary hover:text-white">
            <?php echo __("Sale", "my-theme-child"); ?>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <!-- Products|Videos|Sale -->
    <?php
    // Output videos and product details
    if ($related_products->have_posts()) : ?>
      <div class="my-12">
        <div class="brand-products">
          <?php display_products($related_products); ?>
        </div>

        <!-- Brand's Product Videos -->
        <div class="brand-videos" style="display: none;">
          <?php display_product_videos($related_products); ?>
        </div>

        <div class="brand-sale" style="display: none;">
          <?php display_products($related_products, true); ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php get_footer(); ?>