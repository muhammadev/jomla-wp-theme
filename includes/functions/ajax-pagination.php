<?php
function custom_ajax_pagination_links($wp_query = null, $base_url = '')
{
  if ($wp_query === null) {
    global $wp_query;
  }

  $current_page = max(1, get_query_var('paged', get_query_var('page', 1))); // Get current page
  $max_pages = $wp_query->max_num_pages; // Total pages

  if ($max_pages > 1) {
    $current_url = home_url(add_query_arg(array())); // Get the current URL
    // $base_url = remove_query_arg('page', $current_url); // Remove any existing 'page' query parameter
    echo '<div class="pagination-container">';

    // Previous Button
    if ($current_page > 1) {
      echo '<a href="' . esc_url(add_query_arg('page', $current_page - 1, $base_url)) . '" class="prev">« Prev</a>';
    }

    // Page Numbers
    for ($i = 1; $i <= $max_pages; $i++) {
      $active_class = ($i == $current_page) ? 'active' : '';
      echo '<a data-uri="' . $base_url . '" href="' . esc_url(add_query_arg('page', $i, $base_url)) . '" class="page-num ' . $active_class . '">' . $i . '</a>';
    }

    // Next Button
    if ($current_page < $max_pages) {
      echo '<a href="' . esc_url(add_query_arg('page', $current_page + 1, $base_url)) . '" class="next">Next »</a>';
    }

    echo '</div>';
  }
}
