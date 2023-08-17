<?php
// Handle form submission
/*
function image_optimizer_handle_form_submission()
{
    if (isset($_POST['optimize_images'])) {
        $selected_images = isset($_POST['selected_images']) ? $_POST['selected_images'] : array();

        foreach ($selected_images as $image_id) {
            // Perform image optimization for each selected image
            // Implement your optimization logic here
        }

        // Display a success message
        echo '<div class="notice notice-success"><p>Images optimized successfully.</p></div>';
    }
}
add_action('admin_init', 'image_optimizer_handle_form_submission');

function image_optimizer_display_success_message() {
    echo '<div class="notice notice-success"><p>Images optimized successfully.</p></div>';
}
add_action('admin_notices', 'image_optimizer_display_success_message');

*/

?>

<div class="wrap">
    <h1>Optimize Images</h1>

    <p>
        Great work
    </p>
    <?php
    // Retrieve processed images data from transient
    $processed_images = get_transient('processed_images_data');
    if ($processed_images) {
        foreach ($processed_images as $image_url) {
            echo '<img src="' . esc_url($image_url) . '" width="100" height="100" alt="Processed Image" /><br>';
        }

        // Clear the transient after displaying images
        delete_transient('processed_images_data');
    } else {
        echo 'No processed images available.';
    }
    ?>
</div>

<div class="wrap">
    <h2>Image Optimizer Settings</h2>
    <form method="post" action="<?php echo admin_url('admin.php?page=image-optimizer'); ?>">
        <?php
        // Get all images from media library
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => -1,
        );
        $images = get_posts($args);

        if ($images) {
            foreach ($images as $image) {
                $image_id = $image->ID;
                $image_url = wp_get_attachment_image_src($image_id, 'full')[0];
        ?>
                <label>
                    <input type="checkbox" name="selected_images[]" value="<?php echo $image_url; ?>" />
                    <img src="<?php echo $image_url; ?>" width="100" height="100" alt="Image" />
                </label><br>
        <?php
            }
        } else {
            echo 'No images found.' . count($images);
        }
        ?>
        <input type="submit" name="optimize_images" class="button-primary" value="Optimize Selected Images">
    </form>
</div>
