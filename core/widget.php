<?php

add_action('widgets_init', function() {
    register_widget('LCB_Slides_Widget');
});

class LCB_Slides_Widget extends WP_Widget {

    /**
     * Widget setup
     */
    public function __construct()
    {
        $options = array(
            'classname' => 'lcb-slider',
            'description' => '',
        );

        parent::__construct('lcb_sliders', 'LCB Slides', $options);
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        ob_start();
        include(dirname(__FILE__) . '/../template/flexslider.php');
        echo ob_get_clean();
        echo $args['after_widget'];
    }

}
