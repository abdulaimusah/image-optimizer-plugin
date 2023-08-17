<?php
/*
Plugin Name: Your Image Optimizer
Description: An image optimization plugin for WordPress.
Version: 1.0
Author: Abdulai Musah
*/

// Define plugin constants
define('IMAGE_OPTIMIZER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('IMAGE_OPTIMIZER_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
// require_once IMAGE_OPTIMIZER_PLUGIN_DIR . 'includes/admin/admin.php';

// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'image_optimizer_plugin_activate');
register_deactivation_hook(__FILE__, 'image_optimizer_plugin_deactivate');

// Activation hook callback
function image_optimizer_plugin_activate()
{
    // Create optimizer database table

    global $wpdb;
    $table_name = $wpdb->prefix . 'optimizer';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT,
        image_url VARCHAR(255) NOT NULL,
        optimized_image_url VARCHAR(255) NOT NULL,
        optimization_date DATETIME NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Insert sample data into the table
    $data = array(
        'image_url' => 'sample-image.jpg',
        'optimized_image_url' => 'sample-image-optimized.jpg',
        'optimization_date' => current_time('mysql'),
    );
    $wpdb->insert($table_name, $data);

    // Example: Set default plugin options
    update_option('image_optimizer_plugin_option', 'default_value');
}


function image_optimizer_plugin_deactivate()
{
    // Perform any necessary cleanup tasks on plugin deactivation

    // Drop optimizer database table
    global $wpdb;
    $table_name = $wpdb->prefix . 'optimizer';
    $dropsql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($dropsql);
}

// Enqueue styles and scripts
function image_optimizer_plugin_enqueue_scripts()
{
    wp_enqueue_style('image-optimizer-plugin-style', IMAGE_OPTIMIZER_PLUGIN_URL . 'assets/css/style.css', array(), '1.0.0');
    wp_enqueue_script('image-optimizer-plugin-script', IMAGE_OPTIMIZER_PLUGIN_URL . 'assets/js/script.js', array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'image_optimizer_plugin_enqueue_scripts');

// Register admin menu page
function image_optimizer_plugin_add_menu_page()
{
    add_menu_page(
        'Image Optimizer',
        'Image Optimizer',
        'manage_options',
        'image-optimizer',
        'image_optimizer_admin_page',
        'dashicons-format-image',
        20
    );
}
add_action('admin_menu', 'image_optimizer_plugin_add_menu_page');

// Admin page callback
function image_optimizer_admin_page()
{
    require_once IMAGE_OPTIMIZER_PLUGIN_DIR . 'includes/admin/admin.php';
}

// Handle form submission
function image_optimizer_handle_form_submission()
{
    if (isset($_POST['optimize_images'])) {
        $selected_images = isset($_POST['selected_images']) ? $_POST['selected_images'] : array();

        foreach ($selected_images as $image_id) {
            // Process each selected image
            // Implement your processing logic here
            // For example, you can use $image_id to manipulate the image data
        }

        $processed_images = $selected_images;

        set_transient('processed_images_data', $processed_images, HOUR_IN_SECONDS);


        // Display a success message
        add_action('admin_notices', 'image_optimizer_display_success_message');
    }
}
add_action('admin_init', 'image_optimizer_handle_form_submission');



// Display admin notice
function image_optimizer_display_success_message() {
    echo '<div class="notice notice-success"><p>Images optimized successfully.</p></div>';
}

