<?php

/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if (! defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

?>
<?php astra_content_bottom(); ?>
</div> <!-- ast-container -->
</div><!-- #content -->
<?php
astra_content_after();

astra_footer_before();

jumla_footer();

astra_footer_after();
?>
</div><!-- #page -->
<?php
astra_body_bottom();
wp_footer();
?>

<!-- loading screen -->
<div class="loading-screen fixed top-0 left-0 w-screen h-screen bg-black/50 z-[999] flex justify-center items-center" role="alert">
  <div class="spinner w-12 h-12 border-[5px] border-white animate-spin"></div>
</div>
</body>

</html>