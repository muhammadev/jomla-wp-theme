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
    'posts_per_page' => 10,
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
    class="relative text-center h-2/5 min-h-[300px] flex flex-col justify-center items-center"
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

    <?php if ($brand_logo) : ?>
      <div class="z-10">
        <img class="rounded-full aspect-square object-cover" width="100px" src="<?php echo $brand_logo; ?>" alt="">
      </div>
    <?php endif; ?>
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
    <div class="brand-tabs w-full h-16 flex justify-center text-base md:text-lg lg:text-xl my-12">
      <a
        href="?tab=products"
        class="flex-grow flex justify-center items-center h-full cursor-pointer bg-gray-200 hover:bg-custom-blue hover:text-white">
        <?php echo __("All Products", "my-theme-child"); ?>
      </a>
      <?php if ($number_of_products_with_videos > 0) : ?>
        <a
          href="?tab=videos"
          class="flex-grow flex justify-center items-center h-full cursor-pointer bg-gray-200 hover:bg-custom-blue hover:text-white">
          <?php echo __("Videos", "my-theme-child"); ?>
        </a>
      <?php endif; ?>
      <?php if ($number_of_products_on_sale > 0) : ?>
        <a
          href="?tab=sale"
          class="flex-grow flex justify-center items-center h-full cursor-pointer bg-gray-200 hover:bg-custom-blue hover:text-white">
          <?php echo __("Sale", "my-theme-child"); ?>
        </a>
      <?php endif; ?>
    </div>

    <!-- Products|Videos|Sale -->
    <?php
    // Output videos and product details
    if ($related_products->have_posts()) : ?>
      <div class="astra-container brand-products">
        <?php display_products($related_products); ?>
      </div>

      <!-- Brand's Product Videos -->
      <div class="brand-videos" style="display: none;">
        <?php display_product_videos($related_products); ?>
      </div>

      <div class="astra-container brand-sale" style="display: none;">
        <?php display_products($related_products, true); ?>
      </div>
    <?php endif; ?>

    <div class="brand-info">
      <div class="brand-description">
        <?php the_content(); ?>
      </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>