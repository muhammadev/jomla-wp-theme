<?php
function jumla_register_widget_areas()
{
  register_sidebar(array(
    'name'          => __('Footer About Area', 'jumla-footer-widgets'),
    'id'            => 'footer-about-area',
    'description'   => __('Add website information here.', 'jumla-footer-widgets'),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
  ));
}
add_action('widgets_init', 'jumla_register_widget_areas');

class Contact_Us_Footer extends WP_Widget
{

  public function __construct()
  {
    parent::__construct(
      'contact_us_footer',
      esc_html__('Contact Us Footer', 'jumla-footer-widgets'),
      array('description' => esc_html__('A widget that displays contact information.', 'jumla-footer-widgets'))
    );
  }

  // Output the widget content on the front-end.
  public function widget($args, $instance)
  {
    ob_start();

    echo $args['before_widget'];
    echo '<div class="flex flex-col items-start">';

    if (! empty($instance['title'])) {
      echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
    }

    if (! empty($instance['address'])) {
      echo '<p>' . esc_html(apply_filters('widget_address', $instance['address'])) . '</p>';
    }

    if (! empty($instance['phone'])) {
      echo '<a href="tel:' . esc_attr(apply_filters('widget_phone', $instance['phone'])) . '">' . esc_html(apply_filters('widget_phone', $instance['phone'])) . '</a>';
    }

    if (! empty($instance['email'])) {
      echo '<a href="mailto:' . esc_attr(apply_filters('widget_email', $instance['email'])) . '">' . esc_html(apply_filters('widget_email', $instance['email'])) . '</a>';
    }

    echo '</div>';
    echo $args['after_widget'];

    echo ob_get_clean();
  }

  // Output the widget settings form in the admin.
  public function form($instance)
  {
    $defaults = array(
      'title'   => esc_html__('New title', 'jumla-footer-widgets'),
      'address' => esc_html__('New address', 'jumla-footer-widgets'),
      'phone'   => esc_html__('New phone', 'jumla-footer-widgets'),
      'email'   => esc_html__('New email', 'jumla-footer-widgets'),
    );

    $instance = wp_parse_args((array) $instance, $defaults);
?>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
        <?php esc_attr_e('Title:', 'jumla-footer-widgets'); ?>
      </label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
        name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
        value="<?php echo esc_attr($instance['title']); ?>">
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('address')); ?>">
        <?php esc_attr_e('Address:', 'jumla-footer-widgets'); ?>
      </label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('address')); ?>"
        name="<?php echo esc_attr($this->get_field_name('address')); ?>" type="text"
        value="<?php echo esc_attr($instance['address']); ?>">
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('phone')); ?>">
        <?php esc_attr_e('Phone:', 'jumla-footer-widgets'); ?>
      </label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('phone')); ?>"
        name="<?php echo esc_attr($this->get_field_name('phone')); ?>" type="text"
        value="<?php echo esc_attr($instance['phone']); ?>">
    </p>
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('email')); ?>">
        <?php esc_attr_e('Email:', 'jumla-footer-widgets'); ?>
      </label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>"
        name="<?php echo esc_attr($this->get_field_name('email')); ?>" type="text"
        value="<?php echo esc_attr($instance['email']); ?>">
    </p>
<?php
  }

  // Process widget options to be saved.
  public function update($new_instance, $old_instance)
  {
    $instance = array();
    $instance['title']   = ! empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
    $instance['address'] = ! empty($new_instance['address']) ? sanitize_text_field($new_instance['address']) : '';
    $instance['phone']   = ! empty($new_instance['phone']) ? sanitize_text_field($new_instance['phone']) : '';
    $instance['email']   = ! empty($new_instance['email']) ? sanitize_email($new_instance['email']) : '';
    return $instance;
  }
}

// Register the widget.
function register_contact_us_footer()
{
  register_widget('Contact_Us_Footer');
}
add_action('widgets_init', 'register_contact_us_footer');
