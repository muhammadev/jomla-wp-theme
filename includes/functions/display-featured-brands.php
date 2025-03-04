<?php

function display_featured_brands()
{
  $args = [
    'post_type' => 'brand',
    'posts_per_page' => 10,
    'post_status' => 'publish',
  ];

  $brands = new WP_Query($args);

  if ($brands->have_posts()) :
?>
    <div class="py-10">
      <h2 class="text-center text-2xl font-bold mb-4"><?php echo esc_html__('Featured Brands', 'my-theme-child'); ?></h2>

      <div class="px-10">
        <div class="featured-brands-slider">
          <?php
          while ($brands->have_posts()) : $brands->the_post();
            $brand_image = get_the_post_thumbnail_url(get_the_ID(), 'full');

            if ($brand_image) :
          ?>
              <a class="px-4" href="<?php the_permalink(); ?>">
                <div class="brand-item p-4 flex flex-col items-center justify-center gap-4 border border-gray-200 rounded-md">
                  <img class="w-[150px] h-[150px] rounded-full object-cover" src="<?php echo $brand_image; ?>" alt="<?php the_title(); ?>">
                  <span class="text-center text-lg font-bold"><?php the_title(); ?></span>
                </div>
              </a>
            <?php
            else :
            ?>
              <a class="px-4" href="<?php the_permalink(); ?>">
                <div class="brand-item p-4 flex flex-col items-center justify-center gap-4 border border-gray-200 rounded-md">
                  <img class="w-[150px] h-[150px] rounded-full object-cover" src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/no-image.png" alt="<?php the_title(); ?>">
                  <span class="text-center text-lg font-bold"><?php the_title(); ?></span>
                </div>
              </a>
          <?php
            endif;
          endwhile;
          wp_reset_postdata();
          ?>
        </div>
      </div>
    </div>

    <hr class="block h-[1px] w-full">

    <script>
      jQuery(document).ready(function($) {
        const isRTL = $("html").attr("dir") === "rtl";
        $('.featured-brands-slider').slick({
          rtl: isRTL,
          slidesToShow: 4,
          slidesToScroll: 1,
          autoplay: false,
          responsive: [{
              breakpoint: 1024,
              settings: {
                slidesToShow: 3,
              }
            },
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 2,
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
              }
            }
          ]
        });
      });
    </script>
<?php
  endif;
}
