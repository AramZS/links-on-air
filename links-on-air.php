<?php
/*
Plugin Name: Links on Air
Plugin URI: http://onair.cc/
Description: This WP plugin is a tool for portable internal crosslinking.
Version: 0.0.1
Author: Aram Zucker-Scharff
Author URI: http://aramzs.me
License: GPL2
*/

/*  Developed for the onAir LLC.

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

//Set up some constants
define( 'LA_SLUG', 'la' );
define( 'LA_TITLE', 'Links onAir' );
define( 'LA_MENU_SLUG', LA_SLUG . '-menu' );
define( 'LA_ROOT', dirname(__FILE__) );
define( 'LA_FILE_PATH', LA_ROOT . '/' . basename(__FILE__) );
define( 'LA_URL', plugins_url('/', __FILE__) );

class LinksOnAir {

}

/**
 * Bootstrap
 *
 * You can also use this to get a value out of the global, eg
 *
 *    $foo = linksOnAir()->bar;
 *
 * @since 0.0.1
 */
function linksOnAir() {
	global $la;
	if ( ! is_a( $la, 'LinksOnAir' ) ) {
		$la = new LinksOnAir();
	}
	return $la;
}

// Start me up!
linksOnAir();