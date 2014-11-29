<?php

/* ------------------------------------------------------------------------- *
 *  Add Date Single Event 
/* ------------------------------------------------------------------------- */


function add_date_event( $content ) {
	if ( is_singular( 'nec_events' ) ) {
	
		global $post;
		
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
		
		<div class="nec-single">
			<div class="nec-day-single"><div class="nec-d"><?php echo $my_day; ?></div> <div class="nec-m"><?php echo $my_month; ?></div></div> 
			<div class="nec-info-single">
			
			<?php echo '<img class="nec-clock" src="' . plugins_url( 'img/clock.png', __FILE__ ) . '" > '; echo $my_hour; ?>:<?php echo $my_min; ?>
			
			<?php // Show the Event Category
	
				$terms = get_the_terms( $post->ID, 'nec_cat_events' );
				
				if (is_array($terms)) {
					foreach ($terms as $term){
						echo  '- <span class="nec-label nec-item-'. $term->slug .'"> ' . $term->name . '</span>';
					}
				}

			?>
						
			<?php // If the day is today, show the word Today
						
				if( ($my_day == $current_day) && ($my_month == $current_month_save)):
					echo '- <span class="nec-today-single">'.__('TODAY', 'next-event-calendar').'</span>';
				endif;
			
			?>
			
			</div>
		</div>
	<?php
	}   
	return $content;
}
add_filter( 'the_content', 'add_date_event' );
?>