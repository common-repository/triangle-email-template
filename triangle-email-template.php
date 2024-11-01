<?php
/*
* Plugin Name:       Triangle - Email Template Builder
* Plugin URI:        https://agungsundoro.blogspot.com
* Description:       Drag and drop email template editor for wordpress.
* Version:           1.1.0
* Author:            Agung Sundoro
* Author URI:        https://agungsundoro.blogspot.com
* License:           GPL-3.0
* License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, write to the Free Software
Foundation, Inc. 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.

Copyright 2002-2015 Automattic, Inc.
*/

!defined( 'WPINC ' ) or die;

/**
 * Load Composer Vendor
 */
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Initiate Plugin
 */
function Triangle() {
    $config = file_get_contents(dirname(__FILE__) . '/config.json');
    $config = json_decode($config);
    $config->path = __FILE__;
    $plugin = new Triangle\Plugin($config);
    $plugin->run();
}
add_action('init', 'Triangle');

/**
 * Lifecycle Hooks
 * @activate
 */
function Triangle_activate(){
    $config = file_get_contents(dirname(__FILE__) . '/config.json');
    $config = json_decode($config);
    $config->path = __FILE__;
    new Triangle\Lifecycle\Activate($config);
}
register_activation_hook( __FILE__, 'Triangle_activate');