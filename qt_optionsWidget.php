<?php
/**
 * qt_optionsWidget used to add administer the widget from the dashboard and
 * render it in the front end.
 */
class qt_optionsWidget extends WP_Widget {
    /** constructor */
    function __construct() {
					parent::__construct(
				 		'quick_tags', // Base ID
						 'QuickTags', // Name
						  array( 'description' => __( 'Quickly add tags to a post in the front end.', 'text_domain' ), ) // Args
					);     
    }

				static function init(){
						require_once('qt_optionsWidgetAJAX.php');
						if(class_exists('qt_optionsWidgetAJAX')){
							qt_optionsWidgetAJAX::init();
						}
						self::enqueueCSS();
						self::enqueueJS();
				}
				
				static function enqueueCSS(){
					wp_register_style('qt_optionsWidgetCSS', plugins_url('/css/qt_optionsWidgetCSS.css', __FILE__));					
					wp_register_style('qt-jquery-ui-css', plugins_url('/css/jquery-ui-theme/jquery-ui-qt.css', __FILE__));
					wp_enqueue_style('qt-jquery-ui-css');
					wp_enqueue_style('qt_optionsWidgetCSS');					
				}
				
				static function enqueueJS(){
	    	wp_register_script('qt_optionsWidgetJS', plugins_url('js/qt_optionsWidget.js', __FILE__), array( 'jquery' ));
	    	wp_enqueue_script( 'qt_optionsWidgetJS' );
	    	wp_enqueue_script( 'jquery-ui-core' );
	    	wp_enqueue_script( 'jquery-ui-widget');
	    	wp_enqueue_script( 'jquery-ui-position');	    	
	    	wp_enqueue_script( 'jquery-ui-autocomplete', array(), array( 'jquery-ui-core') );
						wp_localize_script( 'qt_optionsWidgetJS', 'qtJS', array( 
								'ajaxurl' => admin_url( 'admin-ajax.php' ),
								'qtNonce' => wp_create_nonce( 'qt_nonce' ),
								'IMAGE_PATH' => plugins_url( 'images/', __FILE__)
						));
				}

    /** @see WP_Widget::widget */
   	function widget($args, $instance) {
					global $post;
					global $current_user;
					if(is_single()){
						extract( $args );
						$owner = current_user_can('edit_post', $post->ID);
	    	$title = apply_filters('widget_title', $instance['title']);
								
						$html = $owner ? 'Search tags: <input id="qt_addTags"	name="qt_addTags" value="" />' : '';
						$html .= '<div class="clear"></div>';
	
						$html .= '<div class="qt_postTags">';
						$posttags = get_the_tags();
						if ($posttags) {
						  foreach($posttags as $tag) {
						    $html .= '<span id="tag-' . $tag->term_id . '" class="qt_postTag">';
						     $html .= '<a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a> ';
						     if($owner){
							     $html .= '<img src="' . plugins_url('images/no-nohover.png', __FILE__) . '" class="removeTag" data-tagid="' . $tag->term_id . '" alt="Remove tag" title="Remove tag" />';
						     }
						    $html .= '</span> '; 
						  }
						}
				  $html .= '</div>';
				  $html .= '<input type="hidden" id="qt_postID" name="qt_postID" value="' . $post->ID . '" />';
						$html .= '<div class="clear"></div>';
								
						//Render the widget
	     echo $before_widget;
	    	echo $before_title . $title . $after_title;
		  		echo $html;
	     echo $after_widget;
     }
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {															
					$instance = array();
					$instance['title'] = strip_tags( $new_instance['title'] );		
					return $instance;
    }

			/**
			 * Back-end widget form.
			 *
			 * @see WP_Widget::form()
			 *
			 * @param array $instance Previously saved values from database.
			 */
			public function form( $instance ) {
				if ( isset( $instance[ 'title' ] ) ) {
					$title = $instance[ 'title' ];
				}
				else {
					$title = __( 'New title', 'text_domain' );
				}
				?>
				<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
				</p>
				<?php 
	 }
}
?>