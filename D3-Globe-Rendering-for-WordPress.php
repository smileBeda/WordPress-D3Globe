<?php
/*
Plugin Name: D3 Globe Rendering for WordPress
Plugin URI: https://github.com/TukuTuru/
Author: bedas
Description: Spinning, Draggable, Pannable & Zoomable Globe with clickable & hoverable countries
Version: 1.1
Author URI: https://wordpress.org/support/profile/bedas
License: GNU General Public License v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: D3-Globe-Rendering-for-WordPress
*/

//Call some scripts

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

include( plugin_dir_path( __FILE__ ) . 'functions.php');

add_shortcode('D3-Globe','load_all');
