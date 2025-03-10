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
    ob_start();

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
    <ul class="flex flex-col relative z-10 overflow-auto">
      <?php
      foreach ($menu_tree as $menu_item) {
        get_menu_item($menu_item, true, 'footer-menu-item');
      }
      ?>
    </ul>
  <?php

    return ob_get_clean();
  }
}

function jumla_footer()
{
  $contact_settings = get_field('contact_settings', 'option');
  $phone = $contact_settings ? $contact_settings['phone'] : '';
  $email = $contact_settings ? $contact_settings['email'] : '';
  $address = $contact_settings ? $contact_settings['address'] : '';

  $footer_settings = get_field('footer_settings', 'option');
  $footer_logo = $footer_settings ? $footer_settings['footer_logo'] : '';
  $footer_description = $footer_settings ? $footer_settings['footer_description'] : '';
  $footer_copyright = $footer_settings ? $footer_settings['footer_copyright'] : '';

  $social_links = get_field("social_links", "option");

  $showContactUs = $phone || $email || $address;
  $showSocialLinks = !empty($social_links);
  ?>
  <footer class="border-t-2 py-8" role="contentinfo">
    <div class="container mx-auto px-5">
      <div class="flex flex-col md:flex-row flex-wrap items-center md:items-start gap-8">
        <?php if ($footer_logo || $footer_description) : ?>
          <div class="lg:max-w-[40%] text-center lg:text-start">
            <?php if ($footer_logo) : ?>
              <img class="h-[200px] mx-auto" src="<?php echo $footer_logo['url'] ?>" alt="<?php echo $footer_logo['alt'] ?>" />
            <?php else : ?>
              <a href="<?php echo get_home_url(); ?>" class="site-brand inline-block text-3xl font-bold px-4 mb-5">
                <?php echo get_bloginfo('name'); ?>
              </a>
            <?php
            endif;

            if ($footer_description) :
            ?>
              <p><?php echo $footer_description ?></p>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <div class="flex justify-center flex-wrap gap-12">
          <?php if ($showContactUs) : ?>
            <div>
              <h3 class="text-xl font-bold mb-4"><?php echo esc_html__("Contact Us", "my-theme-child"); ?></h3>
              <div class="flex flex-col gap-4">
                <?php if ($phone) : ?>
                  <a dir="ltr" class="rtl:text-end" href="tel:<?php echo $phone ?>"><?php echo $phone ?></a>
                <?php endif; ?>

                <?php if ($email) : ?>
                  <a dir="ltr" class="rtl:text-end" href="mailto:<?php echo $email ?>"><?php echo $email ?></a>
                <?php endif; ?>

                <?php if ($address) : ?>
                  <a href="<?php echo $address['url'] ?>" target="<?php echo $address['target'] ? $address['target'] : '_self' ?>"><?php echo esc_attr($address['title']) ?></a>
                <?php endif; ?>
              </div>
            </div>
          <?php endif; ?>

          <?php
          $footer_menu = jumla_footer_menu();
          if (!empty($footer_menu)) :
          ?>
            <div>
              <h3 class="text-xl font-bold mb-4"><?php echo esc_html__("Quick Links", "my-theme-child"); ?></h3>
              <?php echo $footer_menu; ?>
            </div>
          <?php endif; ?>

          <?php if ($showSocialLinks) : ?>
            <div class="h-fit flex gap-4">
              <?php
              foreach ($social_links as $social_link) {
              ?>
                <a class="w-8" href="<?php echo $social_link['url'] ?>" target="_blank" rel="noopener noreferrer">
                  <img src="<?php echo $social_link['icon']['url'] ?>" alt="<?php echo $social_link['icon']['alt'] ?>" />
                </a>
              <?php
              }
              ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <?php if ($footer_copyright) : ?>
        <div class="mt-8 text-center">
          <p><?php echo $footer_copyright; ?></p>
        </div>
      <?php endif; ?>
    </div>
  </footer>
<?php
}
