<?php
function increment_product_views_callback()
{
  $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

  if (!$product_id) {
    wp_send_json_error('Invalid Product ID');
    wp_die();
  }

  // ensure this is not a translated product and get the original product id
  $original_product_id = apply_filters('wpml_original_element_id', null, $product_id, 'post_product');
  if ($original_product_id && $original_product_id !== $product_id) {
    $product_id = $original_product_id;
  }

  // get the view count
  $views = (int) get_post_meta($product_id, 'product_views', true);

  // Increment and update view count
  update_post_meta($product_id, 'product_views', $views + 1);

  wp_send_json_success(array('views' => $views + 1));
  wp_die();
}

add_action('wp_ajax_increment_product_views', 'increment_product_views_callback');
add_action('wp_ajax_nopriv_increment_product_views', 'increment_product_views_callback');

function increment_product_views()
{
?>
  <script>
    jQuery(document).ready(function($) {
      if (!$("body").hasClass('product-template')) return;

      // fire ajax call to count product views
      $.ajax({
        url: '<?php echo admin_url('admin-ajax.php') ?>',
        method: 'POST',
        data: {
          action: 'increment_product_views',
          product_id: $('#product-id').data('id'),
        },
        success: function(data) {
          console.log("success: ", data);
        },
        error: function(err) {
          console.error(err);
        }
      })
    });
  </script>
<?php } ?>