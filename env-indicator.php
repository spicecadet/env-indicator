<?php
/*
Plugin Name: WP Engine Environment Indicator
Plugin URI: http://edmundturbin.com
Description: Changes the WordPress Toolbar color depending on the current WP Engine environment. Green indicates Production, Yellow indicates Staging and Red indicates Development.
Author: Edmund Turbin
Version: 0.1
Author URI: http://edmundturbin.com
License: GPLv2 or later.
 
WP Engine Environment Incicator is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
WP Engine Environment Incicator is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with WP Engine Environment Incicator. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/

include_once __DIR__.'/class-env-indicator.php';

$wpengine_env_indicator = wpengine\EnvIndicator::get_instance();

add_action( 'admin_body_class', array( $wpengine_env_indicator, 'add_admin_body_class' ));
add_action( 'body_class', array( $wpengine_env_indicator, 'add_body_class' ));
add_action( 'admin_enqueue_scripts',  array( $wpengine_env_indicator, 'add_stylesheet' ));
add_action( 'wp_enqueue_scripts', array( $wpengine_env_indicator, 'add_stylesheet' ));
add_action( 'admin_bar_menu', array( $wpengine_env_indicator, 'update_site_name' ), 99 ); 