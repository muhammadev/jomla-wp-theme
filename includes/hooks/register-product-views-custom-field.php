<?php

function register_product_views_custom_field()
{
  register_post_meta('product', 'product_views', array(
    'type'         => 'number',
    'description'  => 'A numeric field for product views.',
    'single'       => true,
    'show_in_rest' => true,
  ));
}

add_action('init', 'register_product_views_custom_field');
