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

	// See http://php.net/manual/en/language.oop5.decon.php to get a better understanding of what's going on here.
	function __construct() {
		add_shortcode('airlink', array($this, 'shortcode_linker'));
	}
	
	function shortcode_linker($args, $content = null){
		ob_start();
		extract( shortcode_atts( array(
			'ref' => '#',
			'target' => '_self',
			'title' => 'Internal link',
			'type' => 'direct',
			'class' => 'airlink',
			'id' => '',
			'posttype' => 'post'
		), $atts ) );		
		
		$detected = 'direct';
		$dType = 'direct';
		
		#Determine type, start with default, then check user setting, then any declared in args.
		$oType = get_option(LA_SLUG . '_link_type');
		if (isset($oType)){
			$dType = $oType;
		}
		
		if (isset($type)){
			switch($type){
				case 'direct':					
					$aType = 'direct';
					break;
				case 'relative':
					$aType = 'relative';
					break;
				case 'rel':
					$aType = 'relative';
					break;
			}
			if ($type != ('direct' || 'relative')){
				$type = $dType;
			} else {
				$type = $aType;
			}
		}
		
		if (!isset($type)){
			$type = $dType;
		}
		
		# Case: Anchor link.
		if (0 === strpos($ref, "#")) {
			$ref = $ref;
			$detected = 'anchor';
		} 
		# Case: relative link
		elseif (0 === strpos($ref, "/")) {
			
			$detected = 'relative';
		}
		# Case: Direct link
		elseif (((0 === strpos($ref, "www."))) || (0 === strpos($ref, "http://"))) {
		
			$detected = 'direct';
		}
		# Case: Post ID
		elseif (is_numeric($ref)) {
			
			$detected = 'pid';
		}
		# Case: slug
		elseif (is_string($ref)) {
		
			$detected = 'slug';
		}
		# Case: other
		else {
			$ref = $ref;
			$detected = 'other';
		}
		
		switch ($type) {
			case 'direct':
				switch ($detected) {
					case 'relative':
						$pObj = get_page_by_path($ref, OBJECT, $posttype);
						$ref = get_post_permalink($pObj->ID);
						break;
					case 'direct':
						$postid = url_to_postid( $ref );
						$ref = get_post_permalink($postid);	
						break;
					case 'pid':
						$ref = get_post_permalink($ref);
						break;
					case 'slug':
						$pObj = get_page_by_path($ref, OBJECT, $posttype);
						$ref = get_post_permalink($pObj->ID);							
						break;
				}
				break;
			case 'relative':
				switch ($detected) {
					case 'relative':
						$pObj = get_page_by_path($ref, OBJECT, $posttype);
						$ref = get_page_uri($pObj->ID);							
						break;
					case 'direct':
						$postid = url_to_postid( $ref );
						$ref = get_page_uri($postid);
						break;
					case 'pid':
						$ref = get_page_uri($ref);
						break;
					case 'slug':
						$pObj = get_page_by_path($ref, OBJECT, $posttype);
						$ref = get_page_uri($pObj->ID);					
						break;
				}
				break;
		}
		
		#ref can be hyperlink, unique number ID, or slug
		$output= '<a href="'.$ref.'" target="'.$target.'" title="'.$title.'" class="'.$class.'" id="'.$id.'" >' . $content . '</a>';
		
		$error = $ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	#Still need to set up options, ability to set default, and a content filter to apply to all links. Also need to set up a method to verify that the link is local. Should be able to do it by calling the site URL and using strpos.

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