<?php

function display_product_price()
{
?>
  <!-- if there's an offer, display the price after offer, if not, display the original price -->
  <?php if (get_field('offer')) : ?>
    <p>
      <span class="text-base md:text-lg lg:text-xl text-red-500">
        <?php the_field('price_after_offer'); ?>&nbsp;<?php echo esc_html__('EGP', 'my-theme-child') ?>
      </span>
      <span class="text-sm md:text-md lg:text-lg">
        <?php echo esc_html__('Instead of', 'my-theme-child') ?>
        <span class="line-through">
          <?php the_field('price'); ?>&nbsp;<?php echo esc_html__('EGP', 'my-theme-child') ?>
        </span>
      </span>
    </p>
  <?php else : ?>
    <p class="text-base md:text-lg lg:text-xl"><?php the_field('price'); ?>&nbsp;<?php echo esc_html__('EGP', 'my-theme-child') ?></p>
<?php endif;
}
