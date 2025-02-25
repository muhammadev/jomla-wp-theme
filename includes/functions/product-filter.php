<?php

function product_filter()
{
?>
  <div id="product-filter" class="pt-4 px-5 lg:sticky lg:top-0 lg:h-fit lg:max-w-[250px]">
    <div id="inline-filter-container">
      <form id="product-filter-form" method="GET" action="<?php echo admin_url('admin-ajax.php'); ?>" class="">
        <div class="block lg:flex flex-wrap justify-start items-center gap-5 my-5 [&>div]:border-b-2 [&>div]:border-gray-200 [&>div]:pb-5 lg:[&>div]:border-b-0 lg:[&>div]:pb-0">
          <!-- Collections Filter (assuming it's a taxonomy) -->
          <div class="flex flex-col items-start gap-3 mb-4 lg:mb-0">
            <label for="collection" class="text-base"> <?php echo esc_html__("Collection", "my-theme-child"); ?> </label>
            <select id="collection" name="collection" class="w-full lg:w-[200px]">
              <option value=""> <?php echo esc_html__("All Collections", "my-theme-child"); ?> </option>
              <?php
              $collections = get_terms([
                'taxonomy' => 'collection',
                'hide_empty' => true
              ]);

              if (!empty($collections) && !is_wp_error($collections)) :
                foreach ($collections as $collection) :
              ?>
                  <option value="<?php echo esc_attr($collection->slug); ?>" <?php selected($_GET['collection'] ?? '', $collection->slug); ?>>
                    <?php echo esc_html($collection->name); ?>
                  </option>
              <?php
                endforeach;
              endif;
              ?>
            </select>
          </div>

          <!-- Brand Filter -->
          <div class="flex flex-col items-start gap-3 mb-4 lg:mb-0">
            <label for="brand" class="text-base"> <?php echo esc_html__("Brand", "my-theme-child"); ?> </label>
            <select id="brand" name="brand" class="w-full lg:w-[200px]">
              <option value=""><?php echo esc_html__("All Brands", "my-theme-child"); ?></option>
              <?php
              // Example: Query brand CPT to fill options
              $brands = new WP_Query([
                'post_type'      => 'brand',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
              ]);
              if ($brands->have_posts()) :
                while ($brands->have_posts()) : $brands->the_post();
                  $brand_slug = get_post_field('post_name', get_the_ID());
              ?>
                  <option value="<?php the_ID(); ?>" <?php selected($_GET['brand'] ?? '', $brand_slug); ?>>
                    <?php the_title(); ?>
                  </option>
              <?php
                endwhile;
                wp_reset_postdata();
              endif;
              ?>
            </select>
          </div>

          <!-- Color Filter -->
          <div class="flex flex-col items-start gap-3 mb-4 lg:mb-0">
            <label for="color" class="text-base"> <?php echo esc_html__("Color", "my-theme-child"); ?> </label>
            <select id="color" name="color" class="w-full lg:w-[200px]">
              <option value=""> <?php echo esc_html__("All Colors", "my-theme-child"); ?> </option>
              <?php
              $colors = get_terms([
                'taxonomy' => 'color',
                'hide_empty' => true,
                'suppress_filters' => false,
              ]);

              if (!empty($colors) && !is_wp_error($colors)) :
                foreach ($colors as $color) :
              ?>
                  <option value="<?php echo esc_attr($color->slug); ?>" <?php selected($_GET['color'] ?? '', $color->slug); ?>>
                    <?php echo esc_html($color->name); ?>
                  </option>
              <?php
                endforeach;
              endif;
              ?>
            </select>
          </div>

          <div class="text-start flex flex-col gap-3 mb-4 lg:mb-0">
            <label for="price-from" class="text-base"><?php echo esc_html__("Price From (EGP)", "my-theme-child"); ?></label>
            <input id="price-from" name="price-from" type="number" min="0" class="!w-[100px]" />

            <label for="price-to" class="text-base"><?php echo esc_html__("To (EGP)", "my-theme-child"); ?></label>
            <input id="price-to" name="price-to" type="number" min="0" class="!w-[100px]" />
          </div>

          <!-- On Sale Filter (assuming on sale is determined by a custom field) -->
          <div class="min-w-fit mb-4 lg:mb-0 !border-b-0 !pb-0">
            <label class="flex-grow flex items-center gap-2">
              <input class="w-4 h-4" type="checkbox" name="sale" <?php checked($_GET['sale'] ?? '', '0'); ?>>

              <?php echo esc_html__("Sale Only", "my-theme-child"); ?>
            </label>
          </div>
        </div>

        <!-- <hr class="hidden lg:block h-[1px] w-full"> -->
        <!--
        <div class="flex lg:hidden justify-center">
          <input class="reset-button" type="submit" value="<?php echo esc_html__("Apply Filters", "my-theme-child"); ?>">
        </div> -->
      </form>
    </div>

    <div class="flex justify-between items-center mt-0 lg:mt-5">
      <button id="open-filter-modal" data-modal="product-filter-modal" class="modal-trigger lg:hidden flex justify-center items-center gap-3 text-lg font-bold reset-button">
        <i class="inline-block w-4 md:w-5 lg:w-6"><img src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/filter.svg" alt=""></i>
        <?php echo esc_html__("Filter", "my-theme-child"); ?>
      </button>

      <button id="clear-filters" type="button" class="reset-button text-red-500 !border-red-500 hover:bg-red-500 hover:text-white"><?php echo esc_html__("Clear Filters", "my-theme-child"); ?></button>
    </div>

    <?php product_filter_modal(); ?>
  </div>
<?php
}

function product_filter_modal()
{
?>
  <div id="product-filter-modal" class="modal fixed top-0 left-0 w-screen h-screen bg-black/50 z-[999] flex justify-center items-center" style="display: none;" role="alert">
    <div class="modal-content text-center bg-white p-4 rounded-lg w-full max-w-[90%] md:max-w-[500px]">
      <h2 class="text-2xl font-bold"><?php echo esc_html__("Filter Products", "my-theme-child"); ?></h2>

      <div id="modal-filter-container"></div>

      <button class="close text-white px-4 py-2 rounded-lg mt-5">
        <i class="inline-block w-4 md:w-5 lg:w-6"><img src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/close-x.svg" alt=""></i>
      </button>
    </div>
  </div>
<?php
}
