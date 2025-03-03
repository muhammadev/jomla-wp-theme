<?php

if (! function_exists('get_menu_item')) {
  function get_menu_item($menu_item, $top_level = false, $extra_classes = '')
  {
    $has_children = !empty($menu_item->children);
    $item_classes = [$extra_classes];

    if ($top_level) {
      $item_classes[] = "menu-item-top-level";
    }

    if ($has_children) {
      $item_classes[] = "menu-item-has-children";
    }
?>
    <li class="menu-item <?php echo esc_attr(implode(" ", $menu_item->classes)) . ' ' . implode(" ", $item_classes) ?>">
      <?php
      if (!$has_children) {
      ?>
        <a href="<?php echo $menu_item->url; ?>">
          <?php
          echo $menu_item->title;
          ?>
        </a>
      <?php
      } else {
      ?>
        <button class="reset-button border-none menu-toggler flex items-center gap-2">
          <?php
          echo $menu_item->title;

          echo '<span><img width="8px" src="' . get_stylesheet_directory_uri() . '/src/assets/imgs/caret-down.svg" title="open sub menu" /></span>';
          ?>
        </button>
      <?php
        get_menu_children($menu_item);
      }
      ?>
    </li>
  <?php
  }
}

if (! function_exists('get_menu_children')) {
  function get_menu_children($menu)
  {
  ?>
    <ul class="sub-menu">
      <?php
      foreach ($menu->children as $child) {
        $child_classes = implode(' ', $child->classes);
        get_menu_item($child);
      }
      ?>
    </ul>
  <?php
  }
}

if (! function_exists('jumla_footer_menu')) {
  function jumla_footer_menu()
  {
    // Get the menu assigned to the 'footer' location.
    $locations = get_nav_menu_locations();
    if (!isset($locations['footer_menu'])) {
      return;
    }
    $menu_id = $locations['footer_menu'];
    $menu_items = wp_get_nav_menu_items($menu_id);

    if (empty($menu_items) || is_wp_error($menu_items)) {
      return;
    }

    // Build an array with hierarchy.
    $menu_tree = [];
    $menu_by_id = [];

    // Organize menu items by ID and add a children property.
    foreach ($menu_items as $menu_item) {
      $menu_item->children = [];
      $menu_by_id[$menu_item->ID] = $menu_item;
    }

    // Build the tree: attach children to their parent.
    foreach ($menu_by_id as $id => $menu_item) {
      if ($menu_item->menu_item_parent) {
        $menu_by_id[$menu_item->menu_item_parent]->children[] = $menu_item;
      } else {
        $menu_tree[] = $menu_item;
      }
    }
  ?>
    <ul class="flex flex-col relative z-10 bg-white h-full overflow-auto">
      <?php
      foreach ($menu_tree as $menu_item) {
        get_menu_item($menu_item, true, 'footer-menu-item');
      }
      ?>
    </ul>
  <?php
  }
}

function jumla_footer()
{
  ?>
  <footer class="border-t-2 p-5 pt-8" role="contentinfo">
    <div class="footer-widgets">
      <div class="container">
        <div class="flex flex-wrap justify-center gap-8">
          <div class="rich-text-content max-w-[40%]">
            <?php
            if (is_active_sidebar('footer-about-area')) {
              dynamic_sidebar('footer-about-area');
            }
            ?>

            <div class="">
              <p>&copy; <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>. <?php echo esc_html__("All Rights Reserved.", "my-theme-child"); ?></p>
            </div>
          </div>

          <div>
            <h3 class="text-xl font-bold mb-4"><?php echo esc_html__("Quick Links", "my-theme-child"); ?></h3>
            <?php jumla_footer_menu(); ?>
          </div>
        </div>
      </div>
    </div>
  </footer>
<?php
}
