<?php
add_action('acf/include_fields', function () {
  if (! function_exists('acf_add_local_field_group')) {
    return;
  }

  acf_add_local_field_group(array(
    'key' => 'group_67b397d890452',
    'title' => 'Home Page',
    'fields' => array(
      array(
        'key' => 'field_67b397d9ad7ac',
        'label' => 'Products With Filter',
        'name' => 'products_with_filter',
        'aria-label' => '',
        'type' => 'group',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'layout' => 'block',
        'sub_fields' => array(
          array(
            'key' => 'field_67b39890ad7ad',
            'label' => 'Filters',
            'name' => 'filters',
            'aria-label' => '',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'layout' => 'table',
            'pagination' => 0,
            'min' => 0,
            'max' => 0,
            'collapsed' => '',
            'button_label' => 'Add Row',
            'rows_per_page' => 20,
            'sub_fields' => array(
              array(
                'key' => 'field_67b398a8ad7ae',
                'label' => 'Filter Label',
                'name' => 'filter_label',
                'aria-label' => '',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '',
                  'class' => '',
                  'id' => '',
                ),
                'default_value' => '',
                'maxlength' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'parent_repeater' => 'field_67b39890ad7ad',
              ),
              array(
                'key' => 'field_67b398bdad7af',
                'label' => 'Filter Option',
                'name' => 'filter_option',
                'aria-label' => '',
                'type' => 'flexible_content',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '',
                  'class' => '',
                  'id' => '',
                ),
                'layouts' => array(
                  'layout_67b398d17b7c7' => array(
                    'key' => 'layout_67b398d17b7c7',
                    'name' => '',
                    'label' => '',
                    'display' => 'block',
                    'sub_fields' => array(
                      array(
                        'key' => 'field_67b39902ad7b0',
                        'label' => 'Brands',
                        'name' => 'brands',
                        'aria-label' => '',
                        'type' => 'post_object',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                          'width' => '',
                          'class' => '',
                          'id' => '',
                        ),
                        'post_type' => array(
                          0 => 'brand',
                        ),
                        'post_status' => array(
                          0 => 'publish',
                        ),
                        'taxonomy' => '',
                        'return_format' => 'object',
                        'multiple' => 0,
                        'allow_null' => 0,
                        'bidirectional' => 0,
                        'ui' => 1,
                        'bidirectional_target' => array(),
                      ),
                    ),
                    'min' => '',
                    'max' => '',
                  ),
                ),
                'min' => '',
                'max' => '',
                'button_label' => 'Add Row',
                'parent_repeater' => 'field_67b39890ad7ad',
              ),
            ),
          ),
        ),
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'page_template',
          'operator' => '==',
          'value' => 'home-page.php',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
  ));
});
