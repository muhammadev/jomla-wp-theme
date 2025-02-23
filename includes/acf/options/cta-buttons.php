<?php
add_action('acf/init', function () {
  acf_add_options_page(array(
    'page_title' => 'CTAs',
    'menu_slug' => 'ctas',
    'position' => 50,
    'redirect' => false,
  ));
});
