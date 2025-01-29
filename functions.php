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
