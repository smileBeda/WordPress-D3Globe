<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function countries_visited(){
	
	$countries_visited = array(
		'posts_per_page'   => -1,
		'post_type'        => 'country',
		'post_status'      => 'publish',
		'suppress_filters' => true,
		);
	$posts_array = get_posts( $countries_visited );
	
	$country_ids = array();

	foreach ( $posts_array as $country_id ) {
		$country_ids[] = get_post_meta( $country_id->ID, 'wpcf-country-json-id', true );
	}
	
	$country_ids = '["'.implode('","', $country_ids).'"]';
	
	return $country_ids;
}

function js_php_api(){
	$api_array = array(
	    'siteURL' => site_url(),
	    'post_id' => get_the_ID(),
	    'countries_visited' => countries_visited(),
		'page_slug' => 'country'
	);
	return $api_array;
}

function enqueue_tmg_scripts() {

	$scripts_array_urls = [
		"https://cdnjs.cloudflare.com/ajax/libs/d3/4.2.2/d3.min.js", 
		"https://d3js.org/d3-geo.v1.min.js", 
		"https://d3js.org/topojson.v2.min.js", 
		"https://bl.ocks.org/mbostock/raw/7ea1dde508cec6d2d95306f92642bc42/6aac691494f752142a67cc43c51a0fd09896dbd4/versor.js"
	];

	$urls_string = implode("></script><script src=", $scripts_array_urls);
	$urls_string_finished = "<script src=" . $urls_string . "></script>";

	return $urls_string_finished;


}

function return_tmg_canvas() {

	$canvas_html = "<canvas id='globe'></canvas>";
	return $canvas_html;

}

function load_tmg_customized_script() {

	wp_register_script( 'maps_custom_globus', plugin_dir_url( 'D3-Globe-Rendering-for-WordPress.php' ) . '/D3-Globe-Rendering-for-WordPress/maps-globus-custom.js', array("jquery") );
 
	// Localize the script with new data
	wp_localize_script( 'maps_custom_globus', 'php_api_object', js_php_api() );
	wp_enqueue_script( 'maps_custom_globus', plugin_dir_url( 'D3-Globe-Rendering-for-WordPress.php' ) . '/D3-Globe-Rendering-for-WordPress/maps-globus-custom.js', array("jquery"), 1.0, false );

}

function d3_globe(){
	$out = enqueue_tmg_scripts();
	$out .= return_tmg_canvas();
	$out .= load_tmg_customized_script();
return $out;
}

function load_single_country_svg($country){
	
	$country_svg_file_name = match_country_code_svg($country);

	$file = file_get_contents(plugin_dir_url( 'D3-Globe-Rendering-for-WordPress.php' ) . '/D3-Globe-Rendering-for-WordPress/country-svg-maps/' . $country_svg_file_name . '.svg');

	return $file;
}

function apply_static_styles($areas, $css) {
	$areas = explode(',',$areas);
	foreach ($areas as $area) {
		$styles[] = '#' . $area . '{' . $css . ';}';
	}
	$all_area_styles = '<style>' . implode('</style><style>', $styles) . '</style>';
	return $all_area_styles;
}

function apply_hover_styles($areas, $css) {
	$areas = explode(',', $areas);
	foreach ($areas as $area) {
		$styles[] = '#' . $area . ':hover{' . $css . ';}';
	}
	$all_area__hover_styles = '<style>' . implode('</style><style>', $styles) . '</style>';
	return $all_area__hover_styles;
}

function match_country_code_svg($country){
	$decoded = json_decode( file_get_contents(plugin_dir_url( 'D3-Globe-Rendering-for-WordPress.php' ) . '/D3-Globe-Rendering-for-WordPress/country-codes.json', true));
	foreach ($decoded as $value) {
		if ($value->id == $country) {
			$country = str_replace(' ', '-', strtolower($value->name));
		}
	}

	return $country;
}

function svg_single_country( $atts ) {
	$atts = shortcode_atts( array(
		'country' => '',
		'country_css' => '',
		'borders_css' => '',
		'active_areas' => '',//we need comma separated values like 1,2,3
		'active_areas_css' => '',
		'active_areas_hover_css' => '',
		'active_areas_click_script' => '',
		'active_areas_hover_script' => '',
		'active_areas_link' => '',
		'width' => '',
		'height' => ''
	), $atts, 'bartag' );

	$svg = load_single_country_svg($atts['country']);
	$svg .= apply_static_styles($atts['active_areas'], $atts['active_areas_css']);
	$svg .= apply_hover_styles($atts['active_areas'], $atts['active_areas_hover_css']);
	return $svg;
}
