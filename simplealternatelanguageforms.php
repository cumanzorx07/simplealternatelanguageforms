<?php
/*
Plugin Name: Simple Alternate Language for MultiSite WP
Plugin URI: http://www.carlosumanzor.com/project/simple-alternate-language-for-multisite-wp/
Description: A simple plugin that allows you to set the alternate language version of a post/page in a MultiSite environment (based on https://support.google.com/webmasters/answer/189077?hl=en)
Version: 1.0
Author: Carlos Alberto Umanzor Arguedas
Author URI: http://www.carlosumanzor.com
License: GPLv2
*/

/*  Copyright 2015  Carlos Umanzor  (email : shadow.x07@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once('includes/langcodes.php');

register_activation_hook( __FILE__, 'simplealternatelanguageforms_install' );

function simplealternatelanguageforms_install() {
	
}

add_action( 'init', 'simplealternatelanguageforms_init' );

function simplealternatelanguageforms_init() {
	
	add_action( 'add_meta_boxes', 'add_simplealternate_metaboxes' );
	add_action( 'wp_head', 'simplealternate_wp_head' );
}
require_once('includes/simplemetabox.php');

require_once('includes/headercustomization.php');


?>
