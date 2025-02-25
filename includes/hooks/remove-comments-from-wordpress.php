<?php
function remove_comment_admin_menu()
{
  remove_menu_page('edit-comments.php'); // Remove comments menu from dashboard
}
add_action('admin_menu', 'remove_comment_admin_menu');

function disable_comments_post_types()
{
  foreach (get_post_types() as $post_type) {
    if (post_type_supports($post_type, 'comments')) {
      remove_post_type_support($post_type, 'comments');
      remove_post_type_support($post_type, 'trackbacks');
    }
  }
}
add_action('init', 'disable_comments_post_types');

// function remove_comments_from_glance($items)
// {
//   echo "<script>console.log('hello, world!');</script>";

//   if (empty($items)) {
//     echo "<script>console.log('Items array is empty');</script>";
//   } else {
//     echo "<script>console.log('Items before modification: " . json_encode($items) . "');</script>";
//   }

//   foreach ($items as $key => $item) {
//     if (strpos($item, 'edit-comments.php') !== false) {
//       echo "<script>console.log('got you!');</script>";
//       unset($items[$key]); // Remove comment count from dashboard
//     }
//     echo "<script>console.log('not you :(');</script>";
//   }
//   return $items;
// }
// add_action('wp_dashboard_setup', function () {
//   add_filter('dashboard_glance_items', 'remove_comments_from_glance', 100);
// });

// function block_comments_submission()
// {
//   wp_die(__('Comments are disabled.'));
// }
// add_action('pre_comment_on_post', 'block_comments_submission');


function remove_comments_from_glance_widget()
{
  global $wp_meta_boxes;

  // Remove the default "At a Glance" dashboard widget
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);

  // Recreate the widget without comments
  wp_add_dashboard_widget('dashboard_right_now', 'At a Glance', 'custom_glance_widget_output');
}

function custom_glance_widget_output()
{
  $post_types = ['product', 'brand', 'page']; // Replace with your custom post types
  echo "<div class='main'><ul>";

  foreach ($post_types as $post_type) {
    $count = wp_count_posts($post_type);
    if (!$count || !isset($count->publish)) {
      continue;
    }

    $num_posts = number_format_i18n($count->publish);
    $post_type_obj = get_post_type_object($post_type);
    $text = _n($post_type_obj->labels->singular_name, $post_type_obj->labels->name, $num_posts);

    echo "<li class='$post_type-count'><a href='edit.php?post_type=$post_type'>$num_posts $text</a></li>";
  }

  echo "</ul></div>";
}

add_action('wp_dashboard_setup', 'remove_comments_from_glance_widget');
