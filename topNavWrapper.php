<?php
/**
Component Name: Top Nav Wrapper
Description: Top Nav Wrapper Component
Category: Layout
Tags: Navigation
Version: 1.1.1
Author: Waboot Team <info@waboot.io>
Author URI: http://www.waboot.io
 */

if(!class_exists("\\Waboot\\Component")){
	require_once get_template_directory().'/inc/Component.php';
}

class TopNavWrapperComponent extends \Waboot\Component{

    var $default_zone = "header";
    var $default_priority = 1;

    /**
     * This method will be executed at Wordpress startup (every page load)
     */
    public function setup(){
        parent::setup();
        Waboot()->add_component_style('topnav_style', $this->directory_uri . '/assets/dist/css/topNavWrapper.css');
	    add_filter('waboot/theme_options_css_file/content',[$this,'inject_theme_options_css_properties']);
    }
    
    public function run(){
        parent::run();
	    $display_zone = $this->get_display_zone();
	    if(\method_exists($this,'add_zone_action')){
		    $this->add_zone_action([$this,'display_tpl']);
	    }elseif($display_zone !== '__none'){
		    $display_priority = $this->get_display_priority();
		    WabootLayout()->add_zone_action($display_zone,[$this,'display_tpl'],intval($display_priority));
	    }
    }

    public function widgets() {
        add_filter("waboot/widget_areas/available",function($areas){
            $areas['topnav'] = [
                'name' => __('Top Nav {{ n }} (Component)', 'waboot'),
                'description' => __( 'The widget areas registered by Top Nav', 'waboot' ),
                'type' => 'multiple',
                'subareas' => 2
            ];
            return $areas;
        });
    }

    public function display_tpl(){
        $v = new \WBF\components\mvc\HTMLView($this->theme_relative_path."/templates/topnav.php");
        $args = [
            "topnav_width" => WabootLayout()->get_container_grid_class(\Waboot\functions\get_option( 'topnav_width') ),
        ];
        $v->clean()->display($args);
    }

    public function register_options(){
        parent::register_options();
        $orgzr = \WBF\modules\options\Organizer::getInstance();

        $imagepath = get_template_directory_uri()."/assets/images/options/";

        $orgzr->set_group($this->name."_component");

        $orgzr->add_section("layout",_x("Layout","Theme options section","waboot"));
        $orgzr->add_section("header",_x("Header","Theme options section","waboot"));

        $orgzr->add([
            'name' => __('Top Nav Wrapper Width', 'waboot'),
            'desc' => __('Select Top Nav Wrapper width. Fluid or Boxed?', 'waboot'),
            'id' => 'topnav_width',
            'std' => \Waboot\Layout::GRID_CLASS_CONTAINER,
            'type' => 'images',
            'options' => [
                \Waboot\Layout::GRID_CLASS_CONTAINER_FLUID => [
                    'label' => 'Fluid',
                    'value' => $imagepath . 'layout/top-nav-fluid.png'
                ],
                \Waboot\Layout::GRID_CLASS_CONTAINER => [
                    'label' => 'Boxed',
                    'value' => $imagepath . 'layout/top-nav-boxed.png'
                ]
            ]
        ],"header");

        $orgzr->reset_group();
        $orgzr->reset_section();
    }

	public function inject_theme_options_css_properties($content){
		ob_start();
		?>
		.topnav__wrapper {
			background-color: {{ topnav_bgcolor }};
		}
		<?php
		$output = trim(ob_get_clean());
		$content .= $output;
		return $content;
	}
}