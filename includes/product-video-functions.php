<?php
// Function to extract the first video URL from product colors
function get_first_video_from_product($product_id)

{
  $videos = [];
  $product_colors = get_field('product_colors', $product_id);

  if ($product_colors) {
    foreach ($product_colors as $color) {
      if (!empty($color['gallery'])) {
        foreach ($color['gallery'] as $media) {
          // Get media ID (handling both array and numeric ID cases)
          $media_id = is_array($media) ? $media['id'] : intval($media);
          $mime_type = get_post_mime_type($media_id);

          // Store only video attachments, only the first video is needed
          if (strpos($mime_type, 'video') !== false) {
            $videos[] = wp_get_attachment_url($media_id);
            // Only store the first video
            return $videos[0];
          }
        }
      }
    }
  }
  return null;
}
