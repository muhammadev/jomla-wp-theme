<?php

if (! function_exists('menu_item')) {
  function get_menu_item($menu_item, $top_level = false)
  {
    $has_children = !empty($menu_item->children);
    $item_classes = [];

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

if (! function_exists('get_children_menu')) {
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

if (! function_exists('jumla_primary_menu')) {
  function jumla_primary_menu()
  {
    // Get the menu assigned to the 'primary' location.
    $locations = get_nav_menu_locations();
    $menu_id = $locations['primary'];
    $menu_items = wp_get_nav_menu_items($menu_id);

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
    <ul id="primary-menu" class="hidden lg:flex">
      <?php
      foreach ($menu_tree as $menu_item) {
        get_menu_item($menu_item, true);
      }
      ?>
    </ul>

    <div class="responsive-menu-sidebar fixed lg:hidden top-0 start-[-300px] bg-white w-[300px] h-screen">
      <div class="relative w-full h-full">
        <div class="backdrop hidden fixed top-0 left-0 w-screen h-screen bg-black/10"></div>
        <ul class="flex flex-col relative z-10 bg-white h-full overflow-auto">
          <?php
          foreach ($menu_tree as $menu_item) {
            get_menu_item($menu_item, true);
          }
          ?>
        </ul>

        <button id="close-sidebar" class="reset-button hidden absolute top-0 start-[300px] border-none !bg-white px-4 py-2 rounded-none">
          <i class="inline-block w-4 md:w-5 lg:w-6"><img src="<?php echo get_stylesheet_directory_uri() ?>/src/assets/imgs/close-x.svg" alt=""></i>
        </button>
      </div>
    </div>
  <?php
  }
}

if (! function_exists('my_theme_custom_header')) {
  function my_theme_custom_header()
  {
  ?>
    <header id="masthead" class="site-header">

      <div class="ast-container">

        <div class="ast-flex justify-between items-center gap-5 main-header-container">

          <a href="<?php echo get_home_url(); ?>" class="site-brand text-xl md:text-2xl lg:text-3xl font-bold p-4">
            <?php echo get_bloginfo('name'); ?>
          </a>

          <div class="hidden md:block">
            <!-- <input id="global-search" type="search" class="search-field" placeholder="Search" /> -->
            <?php get_search_form(); ?>
          </div>

          <div class="site-navigation flex items-center gap-1 lg:gap-5">
            <?php jumla_primary_menu(); ?>

            <button
              id="search-toggler"
              class="modal-trigger reset-button border-none shadow-none inline-block md:hidden"
              data-modal="search-modal">
              <img width="16px" src="<?php echo get_stylesheet_directory_uri() . '/src/assets/imgs/search-icon.svg'; ?>" title="open search" alt="open search">
            </button>

            <button id="menu-toggler" class="reset-button border-none shadow-none inline-block lg:hidden">
              <img width="16px" src="<?php echo get_stylesheet_directory_uri() . '/src/assets/imgs/burger-menu.svg'; ?>" title="open menu" alt="open menu">
            </button>
          </div>
        </div>
      </div>
    </header>

<?php
  }
}
