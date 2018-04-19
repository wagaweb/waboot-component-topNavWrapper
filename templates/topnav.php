<div id="topnav__wrapper" class="topnav__wrapper">
    <div id="topnav__inner" class="topnav__inner <?php echo $topnav_width ?>">
        <?php
        // Top Nav widgets
        \Waboot\functions\print_widgets_in_area('topnav');
        ?>
        <?php do_action('waboot/component/topnav/after_widgets')?>
    </div>
</div>