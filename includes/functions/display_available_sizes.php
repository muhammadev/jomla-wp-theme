<?php

function display_available_sizes($product_sizes)
{
?>
  <div class="flex flex-wrap gap-2">
    <?php
    if ($product_sizes) {
      foreach ($product_sizes as $size) {
        echo '<span class="inline-block px-2 py-1 border border-black">' . $size->name . '</span>';
      }
    }
    ?>
  </div>
<?php
}
