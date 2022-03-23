<?php
/*
Plugin Name: D3 Globe Rendering for WordPress
Plugin URI: https://github.com/TukuTuru/
Author: bedas
Description: Spinning, Draggable, Pannable & Zoomable Globe with clickable & hoverable countries
Version: 1.3
Author URI: https://wordpress.org/support/profile/bedas
License: GNU General Public License v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: D3-Globe-Rendering-for-WordPress
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'D3_FILE_PATH', plugin_dir_path( __FILE__ ) );
define( 'D3_FILE_URL', plugin_dir_url( __FILE__ ) );

include( D3_FILE_PATH . 'functions.php');

add_shortcode('d3_globe','d3_globe_callback');

add_shortcode('svg_single_country','svg_single_country_callback');
