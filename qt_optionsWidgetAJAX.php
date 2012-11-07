<?php
/**
 * Handles JS AJAX requests coming from the front end.
 */
if(!class_exists('qt_optionsWidgetAJAX')){
	class qt_optionsWidgetAJAX{
		
		static public function init(){
		 add_action('wp_ajax_searchTagsAJAX', array('qt_optionsWidgetAJAX', 'searchTagsAJAX'));
		 add_action('wp_ajax_addNewTagAJAX', array('qt_optionsWidgetAJAX', 'addNewTagAJAX'));
		 add_action('wp_ajax_removeTagAJAX', array('qt_optionsWidgetAJAX', 'removeTagAJAX'));		 
		}
		
		/**
		 * Searches all the tags in the WP instance
		 */
		function searchTagsAJAX(){
			$nonce = $_POST['nonce'];
			if( !wp_verify_nonce($nonce, 'qt_nonce') ){
				header("HTTP/1.0 409 Security Check.");
				exit;
			}
			
			if( empty($_POST['tagRequest']) ){
				header("HTTP/1.0 409 Could not find tag search request.");
				exit;
			}
			
			$searchTerm = (string) stripslashes_deep( $_POST['tagRequest']['term'] );
			
			$args = array(
			         'orderby'    => 'name',
			         'search'     => $searchTerm,
			         'hide_empty' => false
			        );
			
		 $tags = get_tags($args);
		 $results = array();
		 foreach($tags as $tag){
		 	array_push($results, $tag->name);
		 }
			
			echo json_encode( $results );
			exit;
		}
		
		/**
		 * Adds a new tag to a post
		 */
		function addNewTagAJAX(){
			$nonce = $_POST['nonce'];
			if( !wp_verify_nonce($nonce, 'qt_nonce') ){
				header("HTTP/1.0 409 Security Check.");
				exit;
			}
			
			if( empty($_POST['postID']) ){
				header("HTTP/1.0 409 Could not find postID.");
				exit;
			}

			if( empty($_POST['tag']) ){
				header("HTTP/1.0 409 Could not find tag request.");
				exit;
			}
			
			if(!current_user_can('edit_posts')){
				header("HTTP/1.0 409 Security Check.");
				exit;			
			}			
			
			$postID = (int) $_POST['postID'];
			$tag    = (string) stripslashes_deep( $_POST['tag'] );
			
			$success = wp_set_post_tags($postID, array($tag), true);
			
			if($success === false){
				header("HTTP/1.0 409 Could not add tag to post.");
				exit;
			}else{
				$results = get_tags( array('search' => $tag, 'number' => 1) );
				$theTag  = $results[0];
				echo json_encode( array('success' => true, 'tag' => $tag, 'tagID' => $theTag->term_id, 'tagLink' => get_tag_link($theTag->term_id)) );
			}
			
			exit;
		}
		
		/**
		 * Removes a tag from a post
		 */
		 function removeTagAJAX(){
				$nonce = $_POST['nonce'];
				if( !wp_verify_nonce($nonce, 'qt_nonce') ){
					header("HTTP/1.0 409 Security Check.");
					exit;
				}
				
				if( empty($_POST['postID']) ){
					header("HTTP/1.0 409 Could not find postID.");
					exit;
				}

				if( empty($_POST['tagID']) ){
					header("HTTP/1.0 409 Could not find tagID.");
					exit;
				}
				
				if(!current_user_can('edit_posts')){
					header("HTTP/1.0 409 Security Check.");
					exit;
				}				
				
				$postID = (int) $_POST['postID'];
				$tagID  = (string) stripslashes_deep( $_POST['tagID'] );
				
				$tags = get_the_tags($postID);
				$newTagArray = array();
				foreach($tags as $tag){
					if($tag->term_id != $tagID){
						array_push($newTagArray, $tag->name);
					}
				}
				
				$success = wp_set_post_tags($postID, $newTagArray, false);

				if($success === false){
					header("HTTP/1.0 409 Could not remove tag.");
					exit;
				}else{
					echo json_encode( array('success' => true) );
				}
							
				exit;
		 }
		
	}//end qt_optionsWidgetAJAX class
}
?>