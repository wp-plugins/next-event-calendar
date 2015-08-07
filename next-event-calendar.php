<?php
/*
Plugin Name: 	Next Event Calendar
Plugin URI:     http://www.marchettidesign.net/next-event-calendar
Description: 	Easily add Events to WordPress and display them in a beautiful clean layout Responsive. Organize Events in category and filter them with an ajax category Menù. Show events from specific category with the Category Widget Events.  The layout autohide passed events and show if a event is today. Manage the date and hour easly thanks to a cool Datepicker.  
Text Domain:    next-event-calendar
Version: 		1.2
License: 		GPL
Author: 		Andrea Marchetti
Author URI: 	http://www.marchettidesign.net
License: GPL2

*/

/*  Copyright 2014  Andrea Marchetti  (email : afmarchetti@gmail.com)

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

?>
<?php

/* ------------------------------------------------------------------------- *
 *  Make plugin available for translation
/* ------------------------------------------------------------------------- */		


function nec_init() {
	load_plugin_textdomain('next-event-calendar', false, dirname( plugin_basename( __FILE__ ) ) . '/languages'  );
}
add_action('plugins_loaded', 'nec_init');



/* ------------------------------------------------------------------------- *
 *  Create the Post Type Event
/* ------------------------------------------------------------------------- */	


add_action('init', 'create_nec_events');
function create_nec_events() {

    $labels = array(
        'name'               => __('Events', 'next-event-calendar'),
        'singular_name'      => __('Event', 'next-event-calendar'),
        'add_new'            => __('Add New', 'next-event-calendar'),
        'add_new_item'       => __('Add New Event', 'next-event-calendar'),
        'edit_item'          => __('Edit Event', 'next-event-calendar'),
        'new_item'           => __('New Event', 'next-event-calendar'),
        'all_items'          => __('All Events', 'next-event-calendar'),
        'view_item'          => __('View Event', 'next-event-calendar'),
        'search_items'       => __('Search Events', 'next-event-calendar'),
        'not_found'          => __('Event not Found', 'next-event-calendar'),
        'not_found_in_trash' => __('Event not Found in Trash', 'next-event-calendar'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true, 
        'has_archive'        => true,
        'hierarchical'       => true,
        'query_var' 		 => true,
        'menu_position'      => 5,
        'supports'           => array(
                                'title',
                                'editor',
                                'thumbnail',
                                'excerpt',
                                'page-attributes' 
                                ),

    );

   register_post_type('nec_events', $args);
}



/* ------------------------------------------------------------------------- *
 *  Create the Tax Category  Events
/* ------------------------------------------------------------------------- */


add_action( 'init', 'create_nec_cat' );
function create_nec_cat() {

    $labels = array(
         
        'name'              => __('Event Categories', 'next-event-calendar'),
		'singular_name'     => __('Event Category', 'next-event-calendar'),
		'search_items'      => __('Search Event Category ', 'next-event-calendar'),
		'all_items'         => __('All Events Categories', 'next-event-calendar'),
		'edit_item'         => __('Edit Event Categories', 'next-event-calendar'),
		'update_item'       => __('Update Event Category', 'next-event-calendar'),
		'add_new_item'      => __('Add New Event Category', 'next-event-calendar'),
		'new_item_name'     => __('New Event Category Name', 'next-event-calendar'),
		'menu_name'         => __('Event Categories', 'next-event-calendar'),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical'  => true,
        'query_var' 	=> true,
    );
    
    register_taxonomy('nec_cat_events','nec_events', $args);
}

function set_custom_post_types_admin_order($wp_query) {
  if (is_admin()) {

    // Get the post type from the query
    $post_type = $wp_query->query['post_type'];

    if ( $post_type == 'nec_events') {

      // 'orderby' value can be any column name
      $wp_query->set('orderby', 'ID');

      // 'order' value can be ASC or DESC
      $wp_query->set('order', 'DESC');
    }
  }
}
add_filter('pre_get_posts', 'set_custom_post_types_admin_order');



/* ------------------------------------------------------------------------- *
 *  Create the metabox for the event
/* ------------------------------------------------------------------------- */

 
/**
 * Adds a box to the Custom Post.
 */
function nec_events_add_custom_box() {

    $screens = array( 
    			'nec_events', 		// The post type you want this to show up on, can be post, page, or custom post type
	            'normal', 			// The placement of your meta box, can be normal or side
	            'high' );			// The priority in which this will be displayed

    foreach ( $screens as $screen ) {

        add_meta_box(
            	'meta-box-nec-events',
            	 __('Next Event Calendar', 'next-event-calendar'),
            	'nec_events_inner_custom_box',
            $screen
        );
    }
}
add_action( 'add_meta_boxes', 'nec_events_add_custom_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function nec_events_inner_custom_box( $post ) {

// Add an nonce field so we can check for it later.
  wp_nonce_field( 'nec_events_inner_custom_box', 'nec_events_inner_custom_box_nonce' );

  /*
   * Use get_post_meta() to retrieve an existing value
   * from the database and use the value for the form.
   */
 
   $data_nec_events = get_post_meta($post->ID, 'data_nec_events', true);
   $link_nec_events = get_post_meta($post->ID, 'link_nec_events', true);
	
   ?>

	<p><strong><?php _e('Date of the Event', 'next-event-calendar');?></strong> - <?php _e('Click in the box and select a date.', 'next-event-calendar'); ?> <br/><input name="data_nec_events" id="datetimepicker" value="<?php echo $data_nec_events; ?>" style="border: 1px solid #ccc; margin: 10px 10px 0 0"/> <?php _e('Event Date');?></p>
	
	<p><strong><?php _e('Custom Link of the Event', 'next-event-calendar');?></strong> - <?php _e('If you want you can insert custom link.', 'next-event-calendar'); ?> <br/><input name="link_nec_events" id="cl-link" value="<?php echo $link_nec_events; ?>" style="border: 1px solid #ccc; margin: 10px 10px 0 0"/><?php _e('Custom Link');?></p>

  <?php

}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function nec_events_save_postdata( $post_id ) {

  /*
   * We need to verify this came from the our screen and with proper authorization,
   * because save_post can be triggered at other times.
   */

  // Check if our nonce is set.
  if ( ! isset( $_POST['nec_events_inner_custom_box_nonce'] ) )
    return $post_id;

  $nonce = $_POST['nec_events_inner_custom_box_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'nec_events_inner_custom_box' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

  // Check the user's permissions.
  if ( 'nec_events' == $_POST['post_type'] ) {

    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id;
  
  } else {

    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */

  // Sanitize user input.
  $mydata = sanitize_text_field( $_POST['data_nec_events'] );
  $mylink = sanitize_text_field( $_POST['link_nec_events'] );
 

  // Update the meta field in the database.
  update_post_meta( $post_id, 'data_nec_events', $mydata );
  update_post_meta( $post_id, 'link_nec_events', $mylink );

     
}
add_action( 'save_post', 'nec_events_save_postdata' );



/* ------------------------------------------------------------------------- *
 *  Create the Shortcode to show the Calendar
/* ------------------------------------------------------------------------- */


add_shortcode('nec_events', 'nec_events_render');

function nec_events_render($attr) {
$output = '<div>'; 

?>


<?php // Check if Menu should be displayed

if( get_option('hidemenu_option') == "no") {

	// hide menu
	
} else { ?>
			
	<div id="nec-menu">
	   
		<?php 
		$args = array(
			'orderby'       => 'count', 
			'order'         => 'ASC',
		); 
		
		$terms = get_terms("nec_cat_events", $args);
			if ( !empty( $terms ) && !is_wp_error( $terms ) ){
		
				echo "<ul><li><a href='#' id='reset'>". __("All"). "</a></li>";
				
				foreach ( $terms as $term ) {
					echo "<li class='lb-color-". $term->slug ."'><a href='#' id='". $term->slug ."'>" . $term->name . "</a></li>";
				}
				
				echo "</ul>";
			}
		?>

	</div>

<?php } ?>

	<div id="loop-events">
	
	</div>


<?php
$output .= '</div>';
return $output;
}



/* ------------------------------------------------------------------------- *
 *  Inseri Script & Style
/* ------------------------------------------------------------------------- */


/**
 *  Front end CSS and JavaScript
 */
 
add_action( 'wp_enqueue_scripts', 'nec_plugin_frontend' );

function nec_plugin_frontend() {
       
    // Check if plug-in style.css should be used
    if( get_option('disable_style') == "yes") {
    	
		wp_register_style( 'nextevent-calendar', get_bloginfo('template_url') .'/next-event-calendar.css', '', '', 'screen' );
		wp_enqueue_style( 'nextevent-calendar' );

	} else {
    	wp_register_style( 'nec-style', plugins_url('css/style.css', __FILE__) );
    	wp_enqueue_style( 'nec-style' );
    }
    
    wp_enqueue_script('jquery');
}

/**
 *  Back end CSS and JavaScript
 */
 
function nec_plugin_admin() {
	
	$pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'datepickerjs', $pluginfolder . '/datetimepicker/jquery.datetimepicker.js' );
	wp_enqueue_style( 'datepickercss', $pluginfolder . '/datetimepicker/jquery.datetimepicker.css');
	
}
add_action('admin_init', 'nec_plugin_admin');

/**
 *  Initialize date time picker
 */

function my_admin_footer() {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#datetimepicker').datetimepicker();
		});
	</script>
	<?php
}
add_action('admin_footer', 'my_admin_footer');

/**
 *   Ajax Loader
 */

function footer_script() {  ?>
   
<script type="text/javascript">
	jQuery(document).ready(function($) {
	
		<?php // set the time zone from option panel
		
		$time_zone_selected = get_option('timezone_option');
		
		if ($time_zone_selected == ''){
			
			$time_zone_selected = 'Europe/London';
		} 

	
		date_default_timezone_set($time_zone_selected);
		
		$todays_date = date('Y/m/d'); ?>
	    
	    // Next Event Calendar Default Settings  
	    $.loopEvents = {
			path_to_template: '<?php echo get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)); ?>/',
			number_of_posts: 100,
			post_type: 'nec_events',
			
		}
		
		// animation for show content element
		function openElement(){
			
			$( ".nec-element").click(function() {
				$(".nec-cont", this).slideToggle( "slow", function() {});	
			});

		}
		
		// do the initial load.
	    $("#loop-events").load($.loopEvents.path_to_template + 'loop-events.php' + '?query=showposts%3D' + $.loopEvents.number_of_posts +  '%26meta_key%3Ddata_nec_events%26orderby%3Dmeta_value%26order%3DASC%26post_type%3Dnec_events%26meta_compare%3D>=%26meta_value%3D<?php echo $todays_date?>', function() {
		     
		     openElement();
		     			
		}); 
	   
	    // functions for the Filtering 
	    $("#nec-menu a").click(function(){
	        var thecat = $(this).attr("id");
	        if (thecat == 'reset') {

	        	//show alla event in program
	        	var query = '?query=showposts%3D' + $.loopEvents.number_of_posts + '%26meta_key%3Ddata_nec_events%26orderby%3Dmeta_value%26order%3DASC%26post_type%3Dnec_events%26meta_compare%3D>=%26meta_value%3D<?php echo $todays_date?>';
	        	
	        } else {
	            
	            // show filtered event in program
	            var query = '?query=showposts%3D' + $.loopEvents.number_of_posts + '%26nec_cat_events%3D' + thecat + '%26meta_key%3Ddata_nec_events%26orderby%3Dmeta_value%26order%3DASC%26post_type%3Dnec_events%26meta_compare%3D>=%26meta_value%3D<?php echo $todays_date?>';
  
	        }
	        
	        $("#loop-events").animate({opacity: 0}, function() {
	            $("#loop-events").load($.loopEvents.path_to_template + 'loop-events.php' + query, function() {
	                height = $("#queryContainer").height() + 'px';
	                $("#loop-events").animate({opacity: 1});
			        
			        openElement();        

	            });
	        });
	
	        return false;
	    });

	});	
</script>
  
<?php
   
}
add_action('wp_footer', 'footer_script', 100);



/* ------------------------------------------------------------------------- *
 *  Include
/* ------------------------------------------------------------------------- */


include( plugin_dir_path( __FILE__ ) . 'admin.php');

include( plugin_dir_path( __FILE__ ) . 'single.php');

include( plugin_dir_path( __FILE__ ) . 'widget.php');

?>