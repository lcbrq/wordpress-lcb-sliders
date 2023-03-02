<?php
$slider = new LCB_Slides('flexslider');
$slides = $slider->getSlides();

if (count($slides)):
    ?>
    <div id="lcb-slider" class="flexslider">
        <ul class="slides">
            <?php
            foreach ($slides as $slide):
                $slide = $slider->getSlide($slide);
                ?>

                <li>
                    <img src="<?php echo $slide->url; ?>" alt=""/>
                    <?php if($slide->post_title): ?>
                    <h2><?php echo $slide->post_title; ?></h2>
                    <?php endif; ?>
                </li>

            <?php endforeach; ?>
        </ul>
    </div>
    <?php
endif;
?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#lcb-slider').flexslider({
            animation: "slide"
        });
    });
</script>
