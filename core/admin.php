<?php

class LCB_Slides_Admin {

    public function __construct() {
        add_action('init', array($this, 'lcb_slides_register'));
        add_action('add_meta_boxes', array($this, 'lcb_slides_url'));
        add_action('save_post', array($this, 'lcb_slides_save_meta_box_data'));
    }

    function lcb_slides_register() {

        $labels = array(
            'name' => _x('Slides', 'Post Type General Name', 'lcb'),
            'singular_name' => _x('Slide', 'Post Type Singular Name', 'lcb'),
            'menu_name' => __('Slides', 'lcb'),
            'parent_item_colon' => __('Parent Slide:', 'lcb'),
            'all_items' => __('All Slides', 'lcb'),
            'view_item' => __('View Slide', 'lcb'),
            'add_new_item' => __('Add New Slide', 'lcb'),
            'add_new' => __('Add New', 'lcb'),
            'edit_item' => __('Edit Slide', 'lcb'),
            'update_item' => __('Update Slide', 'lcb'),
            'search_items' => __('Search Slide', 'lcb'),
            'not_found' => __('Not found', 'lcb'),
            'not_found_in_trash' => __('Not found in Trash', 'lcb'),
        );
        $args = array(
            'label' => __('slides', 'lcb'),
            'description' => __('Slide Description', 'lcb'),
            'labels' => $labels,
            'supports' => array('title', 'excerpt', 'thumbnail', 'page-attributes'),
            'taxonomies' => array('category'),
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'can_export' => false,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => false,
            'capability_type' => 'page',
        );
        register_post_type('slide', $args);
    }

    /* custom links */

    /**
     * Adds a box to the main column on the Post and Page edit screens.
     */
    function lcb_slides_url() {


        add_meta_box(
                'lcb_slides_sectionid', __('Slide options', 'lcb_slides_textdomain'), array($this,'lcb_slides_meta_box_callback'), 'slide'
        );
    }

    /**
     * Prints the box content.
     * 
     * @param WP_Post $post The object for the current post/page.
     */
    function lcb_slides_meta_box_callback($post) {

        // Add an nonce field so we can check for it later.
        wp_nonce_field('lcb_slides_meta_box', 'lcb_slides_meta_box_nonce');

        /*
         * Use get_post_meta() to retrieve an existing value
         * from the database and use the value for the form.
         */
        $value = get_post_meta($post->ID, '_slide_url', true);

        echo '<label for="lcb_slides_url_field">';
        _e('Slide url', 'lcb_slides_textdomain');
        echo '</label> ';
        echo '<input type="text" id="lcb_slides_url_field" name="lcb_slides_url_field" value="' . esc_attr($value) . '" size="25" />';
    }

    /**
     * When the post is saved, saves our custom data.
     *
     * @param int $post_id The ID of the post being saved.
     */
    function lcb_slides_save_meta_box_data($post_id) {

        /*
         * We need to verify this came from our screen and with proper authorization,
         * because the save_post action can be triggered at other times.
         */

        // Check if our nonce is set.
        if (!isset($_POST['lcb_slides_meta_box_nonce'])) {
            return;
        }

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($_POST['lcb_slides_meta_box_nonce'], 'lcb_slides_meta_box')) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions.
        if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {

            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {

            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        /* OK, it's safe for us to save the data now. */

        // Make sure that it is set.
        if (!isset($_POST['lcb_slides_url_field'])) {
            return;
        }

        // Sanitize user input.
        $my_data = sanitize_text_field($_POST['lcb_slides_url_field']);

        // Update the meta field in the database.
        update_post_meta($post_id, '_slide_url', $my_data);
    }

}