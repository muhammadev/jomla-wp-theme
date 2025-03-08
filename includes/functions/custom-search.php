<?php

/**
 * Include custom taxonomy terms in WordPress search.
 */
function wpdocs_custom_search_join($join)
{
  global $wpdb;

  // Only modify the query on the front-end search
  if (is_search() && ! is_admin()) {
    // Join the term relationships and term tables
    $join .= "
          LEFT JOIN {$wpdb->term_relationships} AS tr 
              ON ({$wpdb->posts}.ID = tr.object_id)
          LEFT JOIN {$wpdb->term_taxonomy} AS tt 
              ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
          LEFT JOIN {$wpdb->terms} AS t 
              ON (tt.term_id = t.term_id)
      ";
  }
  return $join;
}
add_filter('posts_join', 'wpdocs_custom_search_join');


function wpdocs_custom_search_where($where)
{
  global $wpdb;

  if (is_search() && ! is_admin()) {
    // By default, WP searches post_title and post_content. 
    // We'll inject "t.name" so that taxonomy names are also checked.
    $where = preg_replace(
      "/(\({$wpdb->posts}\.post_title LIKE [^)]+) OR ({$wpdb->posts}\.post_content LIKE [^)]+\))/",
      "$1 OR $2 OR (t.name LIKE $1)",
      $where
    );

    // If you only want to target a specific taxonomy, 
    // you can also add an extra condition, for example:
    // $where .= " AND tt.taxonomy = 'your_custom_taxonomy_slug' ";
  }

  return $where;
}
add_filter('posts_where', 'wpdocs_custom_search_where');


function wpdocs_custom_search_groupby($groupby)
{
  global $wpdb;

  if (is_search() && ! is_admin()) {
    // Ensure we group by post ID to avoid duplicate results
    $groupby = "{$wpdb->posts}.ID";
  }
  return $groupby;
}
add_filter('posts_groupby', 'wpdocs_custom_search_groupby');
