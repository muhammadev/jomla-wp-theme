<?php

/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Astra
 * @since 1.0.0
 */

?>
<?php astra_entry_before(); ?>
<?php
get_template_part('template-parts/product', 'card');
?>
<?php astra_entry_after(); ?>