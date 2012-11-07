<?php
/*
Plugin Name: QuickTags
Plugin URI: http://www.rafilabs.com/quicktags
Description: Provides the current logged in user to easily tag their posts from the front end.
Version: 0.0.2
Author: RafiLabs
Author URI: http://www.rafilabs.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if($_SERVER['SERVER_NAME']=='localhost'){;
	require_once($_SERVER['DOCUMENT_ROOT'].'/wp_dev/wp-includes/pluggable.php');
}else{
	require_once ($_SERVER['DOCUMENT_ROOT'].'/wp-includes/pluggable.php');
}

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
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-widget');
			wp_enqueue_script('jquery-ui-autocomplete');
		}
		
	}//end quicktags class
				
	if(class_exists('quicktags')){
		$new_qt = new quicktags();
	}
	
	if( isset($new_qt) ){
		add_action('widgets_init', create_function('', 'return register_widget("qt_optionsWidget");'));
	}
	
}