<form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
  <div class="relative">
    <input type="search" id="search" class="search-field" placeholder="<?php echo esc_html__('Search …', 'my-theme-child'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <button type="submit" class="reset-button border-none absolute top-0 end-0 bottom-0 bg-transparent">
      <img width="16px" src="<?php echo get_stylesheet_directory_uri() . '/src/assets/imgs/search-icon.svg'; ?>" title="search" alt="search">
    </button>
  </div>

  <button type="submit" class="reset-button mt-4 md:hidden">
    <?php echo esc_html__('Search', 'my-theme-child'); ?>
  </button>
</form>