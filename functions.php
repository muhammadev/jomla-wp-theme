<?php
require_once get_stylesheet_directory() . '/includes/product-video-functions.php';

function add_google_tag()
{
?>

  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-158SF4T4LB"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-158SF4T4LB');
  </script>
<?php
}
add_action('wp_head', 'add_google_tag');

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

add_action('wpml_after_save_post', 'sync_translated_brand_relationship', 10, 1);

function sync_translated_brand_relationship($new_post_id)
{
  // Get post data and return if not a product
  $data = get_post($new_post_id);

  if (!$data || !isset($data->post_type)) {
    error_log('Invalid post data: ' . print_r($data, true));
    return;
  }

  if ($data->post_type !== 'product') return;

  // Get brand relationship
  $brand = get_field('brand', $new_post_id);
  if (!$brand) return;
  // get brand language
  $brand_language = apply_filters('wpml_element_language', NULL, ['element_id' => $brand->ID, 'element_type' => 'brand']);

  // Get post language
  $post_language = apply_filters('wpml_element_language_details', NULL, ['element_id' => $new_post_id, 'element_type' => 'post_product']);
  // $source_language_code = $post_language['language_code'];

  // Return if brand language is the same as post language
  if ($brand_language->language_code === $post_language->language_code) return;

  // => now the goal is to get the translated brand and update the post with it

  // get translated brand
  $translated_brand_id = apply_filters('wpml_object_id', $brand->ID, 'brand', false, $post_language->language_code);
  $translated_brand = get_post($translated_brand_id);

  error_log('Translated Brand ' . print_r($translated_brand, true));

  // Update ACF field with translated relationships
  update_field('brand', $translated_brand->ID, $new_post_id);
}


// Ensure the phone number starts with +2
function ensure_a_plus_two($number)
{

  if (preg_match('/^0/', $number)) {
    $number = '+2' . $number;
  } elseif (preg_match('/^2/', $number)) {
    $number = '+' . $number;
  }

  return $number;
}
