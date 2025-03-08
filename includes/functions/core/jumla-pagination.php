<?php

/**
 * Outputs a TailwindCSS-styled pagination.
 *
 * @param array $args {
 *     Optional. An array of arguments.
 *
 *     @type int    $total     Total number of pages. Defaults to $wp_query->max_num_pages.
 *     @type int    $current   Current page number. Defaults to get_query_var('paged').
 *     @type string $base_url  Optional base URL to use for pagination links.
 *     @type int    $range     How many page links to show on either side of current page. Default 2.
 * }
 *
 * @param bool $is_url_params_mode If true, the base URL is a query string with URL parameters.
 * 
 * @param bool $is_ajax If true, the pagination links will be AJAX-enabled.
 * 
 * @param string $ajax_key The key to use for AJAX pagination. Default is ''. Only used when $is_ajax is true.
 */
function jumla_pagination($args = array(), $is_url_params_mode = false, $is_ajax = false, $ajax_key = '')
{
  global $wp_query;

  $defaults = array(
    'total'     => isset($wp_query->max_num_pages) ? $wp_query->max_num_pages : 1,
    'current'   => max(1, get_query_var('paged')),
    'base_url'  => '', // Optional: if provided, use this URL as the base for links. Only used when $is_url_params_mode is true.
    'range'     => 2,
  );
  $args = wp_parse_args($args, $defaults);

  $total_pages  = intval($args['total']);
  $current_page = intval($args['current']);
  $base_url     = $args['base_url'];
  $range        = intval($args['range']);

  if ($total_pages <= 1) {
    return; // No pagination needed.
  }

  // Begin building the pagination HTML.
  $html  = '<nav class="flex justify-center mb-4 mt-10">';
  $html .= '<ul class="inline-flex items-center">';

  // Previous button.
  if ($current_page > 1) {
    $prev_page = $current_page - 1;
    $prev_link = $is_url_params_mode
      ? trailingslashit($base_url) . ($base_url ? ('?page=' . $prev_page) : $prev_page)
      : esc_url(get_pagenum_link($prev_page));
    $html .= '<li><a ' . ($is_ajax ? 'data-key="' . $ajax_key . '"' : '') . 'data-page="' . $prev_page . '" href="' . $prev_link . '" class="' . ($is_ajax ? 'ajax-link ' : '') . 'px-3 py-2 mx-1 text-gray-700 bg-gray-200 rounded hover:bg-primary hover:text-white inline-block">' . esc_html__("Previous", "my-theme-child") . '</a></li>';
  } else {
    $html .= '<li><span class="inline-block px-3 py-2 mx-1 text-gray-400 bg-gray-200 rounded">Previous</span></li>';
  }

  // Determine the range of page numbers to display.
  $start = max(1, $current_page - $range);
  $end   = min($total_pages, $current_page + $range);

  // If the first page isn't in the range, show page 1 and an ellipsis.
  if ($start > 1) {
    $first_link = $is_url_params_mode
      ? trailingslashit($base_url) . ($base_url ? ('?page=' . '1') : '1')
      : esc_url(get_pagenum_link(1));
    $html .= '<li><a ' . ($is_ajax ? 'data-key="' . $ajax_key . '"' : '') . 'data-page="1" href="' . $first_link . '" class="' . ($is_ajax ? 'ajax-link ' : '') . 'px-3 py-2 mx-1 text-gray-700 bg-gray-200 rounded hover:bg-primary hover:text-white inline-block">1</a></li>';
    if ($start > 2) {
      $html .= '<li><span class="px-3 py-2 mx-1 text-gray-500">…</span></li>';
    }
  }

  // Loop through the page numbers.
  for ($i = $start; $i <= $end; $i++) {
    if ($i == $current_page) {
      $html .= '<li><span class="px-3 py-2 mx-1 text-white bg-primary rounded inline-block">' . $i . '</span></li>';
    } else {
      $page_link = $is_url_params_mode
        ? trailingslashit($base_url) . ($base_url ? ('?page=' . $i) : $i)
        : esc_url(get_pagenum_link($i));
      $html .= '<li><a ' . ($is_ajax ? 'data-key="' . $ajax_key . '"' : '') . 'data-page="' . $i . '" href="' . $page_link . '" class="' . ($is_ajax ? 'ajax-link ' : '') . 'px-3 py-2 mx-1 text-gray-700 bg-gray-200 rounded hover:bg-primary hover:text-white inline-block">' . $i . '</a></li>';
    }
  }

  // If the last page isn't in the range, add an ellipsis and the last page link.
  if ($end < $total_pages) {
    if ($end < $total_pages - 1) {
      $html .= '<li><span class="px-3 py-2 mx-1 text-gray-500">…</span></li>';
    }
    $last_link = $is_url_params_mode
      ? trailingslashit($base_url) . ($base_url ? ('?page=' . $total_pages) : $total_pages)
      : esc_url(get_pagenum_link($total_pages));
    $html .= '<li><a ' . ($is_ajax ? 'data-key="' . $ajax_key . '"' : '') . 'data-page="' . $total_pages . '" href="' . $last_link . '" class="' . ($is_ajax ? 'ajax-link ' : '') . 'px-3 py-2 mx-1 text-gray-700 bg-gray-200 rounded hover:bg-primary hover:text-white inline-block">' . $total_pages . '</a></li>';
  }

  // Next button.
  if ($current_page < $total_pages) {
    $next_page = $current_page + 1;
    $next_link = $is_url_params_mode
      ? trailingslashit($base_url) . ($base_url ? ('?page=' . $next_page) : $next_page)
      : esc_url(get_pagenum_link($next_page));
    $html .= '<li><a ' . ($is_ajax ? 'data-key="' . $ajax_key . '"' : '') . 'data-page="' . $next_link . '" href="' . $next_link . '" class="' . ($is_ajax ? 'ajax-link ' : '') . 'px-3 py-2 mx-1 text-gray-700 bg-gray-200 rounded hover:bg-primary hover:text-white inline-block">' . esc_html__("Next", "my-theme-child") . '</a></li>';
  } else {
    $html .= '<li><span class="px-3 py-2 mx-1 text-gray-400 bg-gray-200 rounded inline-block">Next</span></li>';
  }

  $html .= '</ul></nav>';

  echo $html;
}
