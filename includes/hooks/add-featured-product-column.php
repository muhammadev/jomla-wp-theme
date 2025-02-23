<?php
// Add a new column to the posts table in the WordPress dashboard
function add_featured_column_to_products($columns)
{
  // Add a new column after the title column
  $columns['featured'] = 'Featured';
  return $columns;
}
add_filter('manage_product_posts_columns', 'add_featured_column_to_products');

// Display the checkbox for each post in the featured column
function display_featured_checkbox_column($column_name, $post_id)
{
  if ($column_name == 'featured') {
    // Get the post meta for the featured product checkbox
    $is_featured = get_post_meta($post_id, 'featured_product', true);

    // Display the checkbox, checked if the product is featured
    echo '<input type="checkbox" class="featured-checkbox" data-post-id="' . $post_id . '" ' . checked($is_featured, '1', false) . ' />';
  }
}
add_action('manage_product_posts_custom_column', 'display_featured_checkbox_column', 10, 2);

// Enqueue custom JavaScript for the checkbox
function enqueue_featured_checkbox_script()
{
?>
  <script type="text/javascript">
    jQuery(document).ready(function($) {
      $('.featured-checkbox').change(function() {
        var post_id = $(this).data('post-id');
        var is_checked = $(this).prop('checked') ? '1' : '0';

        // Send AJAX request to save the checkbox state
        $.post(ajaxurl, {
          action: 'save_featured_product',
          post_id: post_id,
          is_featured: is_checked,
        }, function(response) {
          // Optionally show a success message or handle the response
          console.log(response);
        });
      });
    });
  </script>
<?php
}
add_action('admin_footer', 'enqueue_featured_checkbox_script');

// Handle AJAX request to save the featured checkbox state
function save_featured_product()
{
  if (isset($_POST['post_id']) && isset($_POST['is_featured'])) {
    $post_id = intval($_POST['post_id']);
    $is_featured = sanitize_text_field($_POST['is_featured']);

    // Save the checkbox state as post meta
    update_post_meta($post_id, 'featured_product', $is_featured);
  }
  wp_die(); // This is required to terminate the AJAX request
}
add_action('wp_ajax_save_featured_product', 'save_featured_product');
