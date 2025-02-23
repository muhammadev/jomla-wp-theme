<?php

function display_hierarchical_collections($collections, $current_language) {
  $collections = get_the_terms(get_the_ID(), 'collection'); // Use your taxonomy slug

  if ($collections && !is_wp_error($collections)) {
    // Calculate hierarchical depth for each term
    foreach ($collections as $category) {
      $depth = 0;
      $current_term = $category;
      while ($current_term->parent != 0) {
        $current_term = get_term($current_term->parent, 'collection');
        $depth++;
      }
      $category->depth = $depth;
    }

    // Sort terms by depth (parents first, children later)
    usort($collections, function ($a, $b) {
      return $a->depth - $b->depth;
    });

    echo '<div class="post-categories mb-2">';
    $category_links = array();

    foreach ($collections as $category) {
      // Get translated term
      $translated_term_id = apply_filters(
        'wpml_object_id',
        $category->term_id,
        'collection',
        true,
        $current_language
      );
      $translated_term = get_term($translated_term_id);

      // Generate translated term link
      $term_link = apply_filters(
        'wpml_permalink',
        get_term_link($translated_term_id, 'collection'),
        $current_language
      );

      $category_links[] = sprintf(
        '<a class="text-xs opacity-50 hover:opacity-100" href="%s">%s</a>',
        esc_url($term_link),
        esc_html($translated_term->name)
      );
    }

    echo implode(' . ', $category_links);
    echo '</div>';
  }
}
