<?php

/**
 * Archive Template for Custom Post Type: Products
 */

get_header(); // Load the theme's header
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        if (have_posts()) :
            // Start the Loop
            while (have_posts()) : the_post();
                // Include a template part for displaying each post
                get_template_part('template-parts/content', get_post_type());
            endwhile;

            // Pagination
            the_posts_pagination(array(
                'prev_text' => __('Previous', 'my-theme-child'),
                'next_text' => __('Next', 'my-theme-child'),
            ));

        else :
            // If no posts are found
            get_template_part('template-parts/content', 'none');
        endif;
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar(); // Load the theme's sidebar (if needed)
get_footer(); // Load the theme's footer
?>