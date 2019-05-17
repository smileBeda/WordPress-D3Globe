<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function enqueue_tmg_scripts() {

	$scripts_array_urls = [
		"https://cdnjs.cloudflare.com/ajax/libs/d3/4.2.2/d3.min.js", 
		"https://d3js.org/d3-geo.v1.min.js", 
		"https://d3js.org/topojson.v2.min.js", 
		"https://bl.ocks.org/mbostock/raw/7ea1dde508cec6d2d95306f92642bc42/6aac691494f752142a67cc43c51a0fd09896dbd4/versor.js"
	];
	
	foreach ($scripts_array_urls as $scripts_array_url) {
		$script_srcs .= "<script src=" . $scripts_array_url . "></script>";
	}

	return $script_srcs;

}

function return_tmg_canvas() {

	$canvas_html = "<canvas id='globe'></canvas>";
	return $canvas_html;

}

function load_tmg_customized_script() {

	$script_url = plugin_dir_url( 'D3-Globe-Rendering-for-WordPress.php' ) . '/D3-Globe-Rendering-for-WordPress/maps-globus-custom.js';
	$script_src = "<script src=" . $script_url . "></script>";
	return $script_src;

}

function load_all(){
	$out .= enqueue_tmg_scripts();
	$out .= return_tmg_canvas();
	$out .= load_tmg_customized_script();
return $out;
}