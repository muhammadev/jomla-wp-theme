<?php
function brand_contact_info_modal($brand_id)
{
  $brand_title = get_the_title($brand_id);
  $brand_mobile = get_field('mobile', $brand_id);
  $brand_whatsapp = get_field('whatsapp', $brand_id);
  $brand_telegram = get_field('telegram', $brand_id);

  $brand_mobile = ensure_a_plus_two($brand_mobile);
  $brand_whatsapp = ensure_a_plus_two($brand_whatsapp);
  $brand_telegram = ensure_a_plus_two($brand_telegram);


?>
  <!-- modal for showing brand contact info (mobile, whatsapp, telegram) -->
  <div id="brand-contact-info-modal" class="modal fixed top-0 left-0 w-screen h-screen bg-black/50 z-[999] flex justify-center items-center" style="display: none;" role="alert">
    <div class="modal-content text-center bg-white p-4 rounded-lg w-full max-w-[90%] md:max-w-[500px]">
      <h2 class="text-2xl font-bold"><?php printf(esc_html__('%s\'s Contact Info', 'my-theme-child'), $brand_title) ?></h2>

      <p class="my-5"><?php printf(esc_html__('You can contact %s via the following channels:', 'my-theme-child'), $brand_title) ?></p>

      <div class="grid grid-cols-1 gap-4 items-center text-base md:text-lg lg:text-xl">
        <?php if ($brand_mobile) : ?>
          <!-- mobile -->
          <div class="flex items-center gap-4 col-span-1">
            <span class="flex items-center gap-2 text-lg font-bold">
              <i class="inline-block w-8"><img src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/phone.svg" alt=""></i>
              <?php echo esc_html__('Mobile:', 'my-theme-child') ?>
            </span>
          </div>
          <a id="brand-mobile" dir="ltr" href="tel:<?php echo $brand_mobile ?>" class="bg-custom-blue text-center text-white hover:text-white p-4 rounded"><?php echo $brand_mobile ?></a>
        <?php endif; ?>

        <?php if ($brand_whatsapp) : ?>
          <!-- WhatsApp -->
          <div class="flex items-center gap-4">
            <span class="flex items-center gap-2 text-lg font-bold">
              <i class="inline-block w-8"><img src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/wa.svg" alt=""></i>
              <?php echo esc_html__('WhatsApp:', 'my-theme-child') ?>
            </span>
          </div>
          <a id="brand-whatsapp" dir="ltr" href="https://wa.me/<?php echo $brand_whatsapp ?>" class="bg-custom-blue text-center text-white hover:text-white p-4 rounded"><?php echo $brand_whatsapp ?></a>
        <?php endif; ?>

        <?php if ($brand_telegram) : ?>
          <!-- Telegram -->
          <div class="flex items-center gap-4">
            <span class="flex items-center gap-2 text-lg font-bold">
              <i class="inline-block w-8"><img src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/telegram.svg" alt=""></i>
              <?php echo esc_html__('Telegram:', 'my-theme-child') ?>
            </span>
          </div>
          <a id="brand-telegram" dir="ltr" href="https://t.me/<?php echo $brand_telegram ?>" class="bg-custom-blue text-center text-white hover:text-white p-4 rounded"><?php echo $brand_telegram ?></a>
        <?php endif; ?>
      </div>

      <button class="close text-white px-4 py-2 rounded-lg mt-5">
        <i class="inline-block w-4 md:w-5 lg:w-6"><img src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/close-x.svg" alt=""></i>
      </button>
    </div>
  </div>
<?php } ?>