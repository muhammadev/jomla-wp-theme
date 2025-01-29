<?php
/*
Template Name: Product Page
Template Post Type: post, page, product
*/
get_header();


// $gallery = maybe_unserialize(get_field('gallery'));
$product_colors = maybe_unserialize(get_field('product_colors'));

$color_galleries = array();

// Get the gallery from $product_colors
if ($product_colors) {
    foreach ($product_colors as $color) {
        $color_galleries[] = $color['gallery'];
    }
}
?>

<div class="product-container w-full top-0 grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
    <div class="product-gallery">
        <?php
        if ($product_colors) {
            foreach ($product_colors as $index => $product_color) {
                $gallery = $product_color['gallery'];
        ?>
                <!-- Main Slider -->
                <div class="main-slider-container" data-index="<?php echo $index; ?>">
                    <div class="main-slider product-slider">
                        <?php
                        if ($gallery) {
                            foreach ($gallery as $mediaIndex => $media) {
                                // Handle both array and ID inputs
                                if (is_array($media)) {
                                    $media_id = $media['id']; // Assuming 'id' exists in the array
                                    $alt = $media['alt'] ?? '';
                                } else {
                                    $media_id = intval($media);
                                    $alt = get_post_meta($media_id, '_wp_attachment_image_alt', true);
                                }

                                $media_url = wp_get_attachment_url($media_id);

                                // Check if the media is an image
                                if (wp_attachment_is_image($media_id)) {
                                    echo '<div><img data-index="' . $mediaIndex . '" src="' . esc_url($media_url) . '" alt="' . esc_attr($alt) . '"></div>';
                                } else {
                                    // Video element for non-image media
                                    echo '<div><video controls>
                                <source src="' . esc_url($media_url) . '" type="' . esc_attr(get_post_mime_type($media_id)) . '">
                                Your browser does not support the video tag.
                            </video></div>';
                                }
                            }
                        }
                        ?>
                    </div>

                    <!-- Viewer -->
                    <div id="viewer" class="w-screen h-screen" style="display: none;" data-index="<?php echo $index; ?>">
                        <?php
                        if ($gallery) {
                            foreach ($gallery as $mediaIndex => $media) {
                                // Handle both array and ID inputs
                                if (is_array($media)) {
                                    $media_id = $media['id']; // Assuming 'id' exists in the array
                                    $alt = $media['alt'] ?? '';
                                } else {
                                    $media_id = intval($media);
                                    $alt = get_post_meta($media_id, '_wp_attachment_image_alt', true);
                                }

                                $media_url = wp_get_attachment_url($media_id);

                                // Check if the media is an image
                                if (wp_attachment_is_image($media_id)) {
                                    echo '<img class="w-full" src="' . esc_url($media_url) . '" alt="' . esc_attr($alt) . '">';
                                } else {
                                    echo '<video controls>
                                <source src="' . esc_url($media_url) . '" type="' . esc_attr(get_post_mime_type($media_id)) . '">
                                Your browser does not support the video tag.
                            </video>';
                                }
                            }
                        }
                        ?>
                    </div>

                </div>

                <!-- Navigation Slider (Thumbnails) -->
                <div class="nav-slider" data-index="<?php echo $index; ?>">
                    <?php
                    if ($gallery) {
                        foreach ($gallery as $mediaIndex => $media) {
                            // Handle both array and ID inputs
                            if (is_array($media)) {
                                $media_id = $media['id']; // Assuming 'id' exists in the array
                                $alt = $media['alt'] ?? '';
                            } else {
                                $media_id = intval($media);
                                $alt = get_post_meta($media_id, '_wp_attachment_image_alt', true);
                            }

                            // Check if the media is an image
                            if (wp_attachment_is_image($media_id)) {
                                $thumb_url = wp_get_attachment_image_url($media_id, 'thumbnail');
                                echo '<div><img src="' . esc_url($thumb_url) . '" alt="' . esc_attr($alt) . '"></div>';
                            } else {
                                // For video thumbnails, get the featured image (or placeholder)
                                $thumb_url = wp_get_attachment_image_url($media_id, 'thumbnail') ?: esc_url(wp_upload_dir()['baseurl'] . '/custom-uploads/icons8-video-50.png');
                                echo '<div><img src="' . esc_url($thumb_url) . '" alt="Video Thumbnail"></div>';
                            }
                        }
                    }
                    ?>
                </div>
        <?php
            }
        }
        ?>
    </div>

    <div class="product-details block px-4">
        <h1 class="text-xl md:text-3xl lg:text-6xl"><?php the_title(); ?></h1>

        <!-- Price -->
        <div class="mt-4">
            <!-- if there's an offer, display the price after offer, if not, display the original price -->
            <?php if (get_field('offer')) : ?>
                <p class="text-lg md:text-2xl lg:text-3xl text-red-500">
                    <?php echo __('EGP', 'my-theme-child') ?>&nbsp;<?php the_field('price_after_offer'); ?>
                </p>
                <p class="text-sm md:text-md lg:text-lg">
                    <?php echo __('Instead of', 'my-theme-child') ?>
                    <span class="line-through">
                        <?php echo __('EGP', 'my-theme-child') ?>&nbsp;<?php the_field('price'); ?>
                    </span>
                </p>
            <?php else : ?>
                <p class="text-xl md:text-2xl lg:text-3xl"><?php echo __('EGP', 'my-theme-child') ?> <?php the_field('price'); ?></p>
            <?php endif; ?>
        </div>

        <!-- Colors -->
        <div class="mt-4">
            <!-- <p class="text-lg md:text-xl lg:text-2xl"><?php echo __('Colors:', 'my-theme-child') ?></p> -->
            <div class="flex flex-wrap">
                <?php
                if ($color_galleries) {
                    foreach ($color_galleries as $color_index => $gallery) {
                        // Get the first image URL of the gallery
                        $first_image = !empty($gallery) ? $gallery[0] : '';
                        $first_image_url = wp_get_attachment_url($first_image['id']);

                        // Display the button with the first image as its background
                        echo '<button data-index="' . $color_index . '" class="color-option ' . ($color_index === 0 ? 'active' : '') . '"><img src="' . esc_url($first_image_url) . '" alt=""/></button>';
                    }
                }
                ?>
            </div>
        </div>

        <div class="mt-4">
            <?php
            if (get_field('wholesaler')) :
                $wholesaler = get_field('wholesaler');
            ?>
                <p class="text-lg md:text-xl lg:text-2xl"><?php echo __('Wholesaler:', 'my-theme-child') ?> <?php echo $wholesaler->post_title; ?></p>
            <?php endif; ?>
        </div>

        <div><?php the_content(); ?></div>
    </div>
</div>

<?php get_footer(); ?>