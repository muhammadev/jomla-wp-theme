<?php

function display_available_sizes($product_sizes)
{
?>
  <div class="flex flex-wrap gap-2">
    <?php
    if ($product_sizes) {
      foreach ($product_sizes as $size) {
        $size_name = $size['size']->name;
        $is_sold_out = $size['sold_out'];
        echo '<span class="inline-block px-2 py-1 border border-black ' . ($is_sold_out ? 'sold-out' : '') . '">' . $size_name . '</span>';
      }
    }
    ?>
  </div>
<?php
}
