<?php
function search_modal()
{
?>
  <div id="search-modal" class="modal fixed top-0 left-0 w-screen h-screen bg-black/50 z-[999] flex justify-center items-center" style="display: none;" role="alert">
    <div class="modal-content text-center bg-white py-5 px-8 rounded-lg w-full max-w-[90%] md:max-w-[500px]">
      <h2 class="text-2xl font-bold mb-4"><?php echo esc_html__('Search Our Products and Brands', 'my-theme-child'); ?></h2>

      <?php get_search_form(); ?>

      <button class="close text-white px-4 py-2 rounded-lg mt-5">
        <i class="inline-block w-4 md:w-5 lg:w-6"><img src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/close-x.svg" alt=""></i>
      </button>
    </div>
  </div>
<?php } ?>