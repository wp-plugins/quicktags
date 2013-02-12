<?php
/*
Plugin Name: QuickTags
Plugin URI: http://www.rafilabs.com/quicktags
Description: Provides the current logged in user to easily tag their posts from the front end.
Version: 0.0.4
Author: RafiLabs
Author URI: http://www.rafilabs.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

require_once(ABSPATH . 'wp-includes/pluggable.php');

if ( !class_exists("quicktags") ) {

	class quicktags{
		function __construct(){
			$this->qt_init();
			require_once( 'qt_optionsWidget.php' );
			if(class_exists('qt_optionsWidget')){
				qt_optionsWidget::init();
			}			
		}
		
		function qt_init(){
			self::enqueueJS();
		}
		
		static function enqueueJS(){
			wp_enqueue_script('jquery');
		}
		
	}//end quicktags class
				
	if(class_exists('quicktags')){
		$new_qt = new quicktags();
	}
	
	if( isset($new_qt) ){
		add_action('widgets_init', create_function('', 'return register_widget("qt_optionsWidget");'));
	}
	
}