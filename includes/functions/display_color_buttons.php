<?php

function display_color_buttons($color_galleries, $product_colors)
{
?>
  <div class="flex flex-wrap">
    <?php
    if ($color_galleries) {
      foreach ($color_galleries as $color_index => $gallery) {
        // Get the first image URL of the gallery
        $first_image = !empty($gallery) ? $gallery[0] : '';
        $first_image_url = wp_get_attachment_url($first_image['id']);

        // Display the button with the first image as its background
        echo '<button title="' . $product_colors[$color_index]['color']->name . '" data-index="' . $color_index . '" class="color-option border border-black ' . ($color_index === 0 ? 'active' : '') . '"><img src="' . esc_url($first_image_url) . '" alt=""/></button>';
      }
    }
    ?>
  </div>
<?php
}
