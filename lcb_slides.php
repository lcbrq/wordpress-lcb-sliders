<?php

/*
* Plugin Name: LCB Slides
* Description: Simple slides management for wordpress
* Version: 1.0.0
* Author: Tomasz Gregorczyk
* License: GPL2
*/ 

include(dirname(__FILE__) . '/core/admin.php');

new LCB_Slides('flexslider');
new LCB_Slides_Admin;

class LCB_Slides {

    public function __construct($type = null) {
        add_action('wp_enqueue_scripts', array($this, 'lcb_slides_styles'));
        if ($type == "flexslider") {
            add_action('wp_enqueue_scripts', array($this, 'lcb_slides_flexslider'));
        }
    }

    public function lcb_slides_styles() {
        $path = get_stylesheet_directory_uri() . '/includes/lcb_slides/';
        wp_enqueue_style('slides', $path . 'slides.css');
    }

    public function lcb_slides_flexslider() {
        $path = get_stylesheet_directory_uri() . '/includes/lcb_slides/extra/';
        wp_enqueue_style('flexslider', $path . 'flexslider.css');
        wp_enqueue_script('flexslider', $path . 'flexslider.js', array(), '1.0.0', true);
    }

    function getSlides() {
        $args = array(
            'post_type' => 'slide',
            'post_parent' => 0
        );
        return get_posts($args);
    }

    function getSlide($slide) {
        $slide->url = wp_get_attachment_url(get_post_thumbnail_id($slide->ID));
        $slide->link = get_post_meta($slide->ID, '_slide_url', true);
        return $slide;
    }

}