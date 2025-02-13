<?php
function enqueue_slider_scripts()
{
  // Enqueue Slick CSS
  wp_enqueue_style('slick-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css', [], '1.8.1');
  // Enqueue Slick Theme CSS (optional)
  wp_enqueue_style('slick-theme-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css', ['slick-css'], '1.8.1');
  // Enqueue Slick JS
  wp_enqueue_script('slick-js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', ['jquery'], '1.8.1', true);
}
add_action('wp_enqueue_scripts', 'enqueue_slider_scripts');

function enqueue_tailwind_css()
{
  wp_enqueue_style('tailwind-css', get_stylesheet_directory_uri() . '/dist/tailwind.css');
}
add_action('wp_enqueue_scripts', 'enqueue_tailwind_css');

function enqueue_compiled_assets()
{
  wp_enqueue_script('theme-scripts', get_stylesheet_directory_uri() . '/dist/js/main.js', [], '1.0.0', true);
  // wp_enqueue_style('theme-styles', get_stylesheet_directory_uri() . '/dist/main.css', [], '1.0.0');
}
add_action('wp_enqueue_scripts', 'enqueue_compiled_assets');


function my_theme_child_enqueue_styles()
{
  wp_enqueue_style('child-theme-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'my_theme_child_enqueue_styles');

function product_gallery_slider_shortcode()
{
  ob_start(); // Start output buffering
  $images = get_field('gallery');
  if ($images) {
    echo '<div class="product-slider">';
    foreach ($images as $image) {
      echo '<div><img src="' . $image['url'] . '" alt="' . $image['alt'] . '"></div>';
    }
    echo '</div>';
  }
  return ob_get_clean(); // Return the buffered content
}
add_shortcode('product_gallery_slider', 'product_gallery_slider_shortcode');

function wpse_242473_add_post_type_to_home($query)
{

  if ($query->is_main_query() && $query->is_home()) {
    $query->set('post_type', array('product'));
  }
}
add_action('pre_get_posts', 'wpse_242473_add_post_type_to_home');

function my_theme_load_textdomain()
{
  load_theme_textdomain('my-theme', get_stylesheet_uri() . '/languages');
}
add_action('after_setup_theme', 'my_theme_load_textdomain');

function custom_archive_template($template)
{
  if (is_post_type_archive('products')) {
    $new_template = locate_template(array('archive-products.php'));
    if (!empty($new_template)) {
      return $new_template;
    }
  }
  // return $template;
}
add_filter('archive_template', 'custom_archive_template');

function custom_archive_content()
{
  if (is_post_type_archive('products')) {
    while (have_posts()) : the_post();
      // Customize the output here
      echo '<div class="custom-product-card">';
      echo '<h2>' . get_the_title() . '</h2>';
      echo '<p>' . get_the_excerpt() . '</p>';
      echo '</div>';
    endwhile;
  }
}
add_action('astra_content_loop', 'custom_archive_content');

function force_template_for_all_products($post_id)
{
  if (get_post_type($post_id) === 'product') {
    update_post_meta($post_id, '_wp_page_template', 'product-page.php');
  }
}
add_action('save_post', 'force_template_for_all_products');

add_action('wpml_after_save_post', 'sync_translated_wholesaler_relationship', 10, 1);

function sync_translated_wholesaler_relationship($new_post_id)
{
  // Get post data and return if not a product
  $data = get_post($new_post_id);

  if (!$data || !isset($data->post_type)) {
    error_log('Invalid post data: ' . print_r($data, true));
    return;
  }

  if ($data->post_type !== 'product') return;

  // Get wholesaler relationship
  $wholesaler = get_field('wholesaler', $new_post_id);
  if (!$wholesaler) return;
  // get wholesaler language
  $wholesaler_language = apply_filters('wpml_element_language', NULL, ['element_id' => $wholesaler->ID, 'element_type' => 'wholesaler']);

  // Get post language
  $post_language = apply_filters('wpml_element_language_details', NULL, ['element_id' => $new_post_id, 'element_type' => 'post_product']);
  // $source_language_code = $post_language['language_code'];

  // Return if wholesaler language is the same as post language
  if ($wholesaler_language->language_code === $post_language->language_code) return;

  // => now the goal is to get the translated wholesaler and update the post with it

  // get translated wholesaler
  $translated_wholesaler_id = apply_filters('wpml_object_id', $wholesaler->ID, 'wholesaler', false, $post_language->language_code);
  $translated_wholesaler = get_post($translated_wholesaler_id);

  error_log('Translated Wholesaler ' . print_r($translated_wholesaler, true));

  // Update ACF field with translated relationships
  update_field('wholesaler', $translated_wholesaler->ID, $new_post_id);
}
