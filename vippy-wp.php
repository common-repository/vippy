<?php
/*
Plugin Name: Vippy
Plugin URI: http://wordpress.org/extend/plugins/vippy-wp/
Description: Easily manage your Vippy videos on WordPress.
Version: 1.2
Author: Citomedia
Author URI: http://citomedia.no/
License: GPL2

Copyright 2012  Citomedia  (email : post@citomedia.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('VIPPYVERSION', '1.2');
define('VIPPYTEXTDOMAIN', 'vippy');
define('VIPPYBASENAME', plugin_basename(dirname(__FILE__)));
define('VIPPYDIR', plugin_dir_path(__FILE__));
define('VIPPYURL', plugins_url() . '/' . VIPPYBASENAME . '/');

require_once(VIPPYDIR . 'rest/vippy.class.php');
require_once(VIPPYDIR . 'lib/admin.php');
require_once(VIPPYDIR . 'lib/functions.php');
require_once(VIPPYDIR . 'lib/shortcode.php');
require_once(VIPPYDIR . 'lib/controller.php');
require_once(VIPPYDIR . 'lib/frontend.php');
require_once(VIPPYDIR . 'lib/mobile.php');