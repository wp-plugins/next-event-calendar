<?php

/* ------------------------------------------------------------------------- *
 *  Create the widget Next Event
/* ------------------------------------------------------------------------- */


/**
 * Adds widget.
 */
class Cl_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'Cl_Widget', // Base ID
			__('Next Event Calendar Category', 'fullby'), // Name
			array( 'description' => __( 'Display latest event from a Next Event Calendar Category.', 'Nec' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
	
	
	
		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		
		$show_post_num = ( ! empty( $instance['show_post_num'] ) ) ? $instance['show_post_num'] : '';
		
		$nec_category_sel = ( ! empty( $instance['nec_category_sel'] ) ) ? $instance['nec_category_sel'] : '';
        
		// these are the widget options
		$title = apply_filters('widget_title', $instance['title']);
		echo $args['before_widget'];
		$show_post_num = $instance['show_post_num'];
		
		$nec_category_sel = $instance['nec_category_sel'];
		$term = get_term( $nec_category_sel, 'nec_cat_events' );
		$selterm = $term->slug;

		if ( ! empty( $title ) )
			
			echo $args['before_title'] . $title . $args['after_title'];

		?>
		
		<?php
		
		global $post;
	
		$todays_date = date('Y/m/d H i');
		
		// Define custom query parameters
		$custom_query_args = array(
							 'meta_key' => 'data_nec_events',
							 'meta_compare'=>'>=',
							 'meta_value' =>  $todays_date,
							 'orderby' => 'meta_value',
							 'order'=>'ASC',
							 'showposts' => $show_post_num, 
							 'post_type' => 'nec_events',
							 'nec_cat_events' => $selterm,
						
						);
		
		// Instantiate custom query
		$custom_query = new WP_Query( $custom_query_args );

		?>
		
		<p><?php //echo $selterm; ?></p>
	
		<?php if ($custom_query->have_posts()) : while($custom_query->have_posts()) : $custom_query->the_post(); ?>
		
			<?php  // Extratct day, month, year, hour, min
			
			 	
			 	$time_zone_selected = get_option('timezone_option');
		
				if ($time_zone_selected == ''){
					
					$time_zone_selected = 'Europe/London';
				} 
		
		
				date_default_timezone_set($time_zone_selected);
		
				$current_month_save = date('M');
				$current_day = date('j');
				
				$original_date = get_post_meta($post->ID, 'data_nec_events', TRUE); 
				$my_day = date("j", strtotime($original_date ));
				$my_month = date("M", strtotime($original_date ));
				$my_month_full = date("F", strtotime($original_date ));
				$my_year = date("Y", strtotime($original_date ));  
				$my_hour = date("H", strtotime($original_date )); 
				$my_min = date("i", strtotime($original_date )); 
			
			?>
			
			<?php // Determinate the Event Link
							
			$custom_link = get_post_meta($post->ID, 'link_nec_events', true);
			
			if($custom_link != ''){					
				$event_link = $custom_link;
			} else {
				$event_link = get_permalink();	
			} ?>
			 
			<a href="<?php echo $event_link; ?>" class="nec-wd-element">
				
				<div class="nec-img">
					
					<?php if ( has_post_thumbnail() ) {
					
					the_post_thumbnail('thumbnail');
					
					} else { 
						
						echo '<img src="' . plugins_url( 'img/image-fallback.png', __FILE__ ) . '" > ';
	
					 } ?>  
				
				</div>
					
				<div class="nec-head">
									
					<span class="nec-info">
						<?php echo $my_day; ?> <?php echo $my_month; ?> <?php echo '<img class="nec-clock" src="' . plugins_url( 'img/clock.png', __FILE__ ) . '" > '; echo $my_hour; ?>:<?php echo $my_min; ?>
					</span>
				
					<div class="nec-tit"><?php the_title(); ?></div>
					
					<?php // If the day is today, show the word Today
						
							if( ($my_day == $current_day) && ($my_month == $current_month_save)):
								echo '<span class="nec-today-single">'.__('TODAY', 'next-event-calendar').'</span>';
							endif;
						
						?>
				
				</div>
							
			</a>
	
		<?php endwhile; ?>

		<?php else : ?>

				<p><?php _e('No events are scheduled.', 'next-event-calendar');?></p>

		<?php endif; ?>
	
		<?php wp_reset_postdata(); ?>
	
	<?php
	
	echo $args['after_widget'];
		
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
	
	// Check Values 
	
	if( $instance) { 
	     $title = esc_attr($instance['title']); 
	     $show_post_num = esc_attr($instance['show_post_num']); 
	     $nec_category_sel = esc_attr($instance['nec_category_sel']); 
	} else { 
	     $title = ''; 
	     $show_post_num = ''; 
	     $nec_category_sel = ''; 
	} 
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'next-event-calendar'); ?></label><br/>
			<input style="width:100%"id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		
		<p><?php _e('Choose a category to show:', 'next-event-calendar');?>
            <select id="<?php echo $this->get_field_id('nec_category_sel'); ?>" name="<?php echo $this->get_field_name('nec_category_sel'); ?>" class="widefat" style="width:100%;">
                <?php foreach(get_terms('nec_cat_events') as $term) { ?>
                <option <?php selected($nec_category_sel, $term->term_id ); ?> value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
                <?php } ?>      
            </select>
        </p>

		
		<p>
			<label for="<?php echo $this->get_field_id('show_post_num'); ?>"><?php _e('Number Post to show:', 'next-event-calendar'); ?></label><br/>
			<input style="width:40px"id="<?php echo $this->get_field_id('show_post_num'); ?>" name="<?php echo $this->get_field_name('show_post_num'); ?>" type="text" value="<?php echo $show_post_num; ?>" />
		</p>
	
	<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['show_post_num'] = ( ! empty( $new_instance['show_post_num'] ) ) ? strip_tags( $new_instance['show_post_num'] ) : '';
		$instance['nec_category_sel'] = ( ! empty( $new_instance['nec_category_sel'] ) ) ? strip_tags( $new_instance['nec_category_sel'] ) : '';
		return $instance;
	}

} // class Cl_Widget


// register Cl_Widget widget
function register_Cl_Widget() {
    register_widget( 'Cl_Widget' );
}
add_action( 'widgets_init', 'register_Cl_Widget' );

?>