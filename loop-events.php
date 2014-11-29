<?php
    // load wordpress into template. dont touch me!
    $path = $_SERVER['DOCUMENT_ROOT'];
    define('WP_USE_THEMES', false);
   
    $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
    require_once( $parse_uri[0] . 'wp-load.php' );
    // ah, wordpress is loaded. balance has been restored.
    query_posts($_GET["query"]);
?>
 
<div id="nec-container">
		
	<?php // set the time zone from option panel
	
		$time_zone_selected = get_option('timezone_option');
		
		if ($time_zone_selected == ''){
			
			$time_zone_selected = 'Europe/London';
		} 

	
		date_default_timezone_set($time_zone_selected);
		
		$current_month = date('M'); 
		$current_month_save = date('M');
		$current_day = date('j');
		
	
	?>
	
	<p class="tit-month"><?php _e('<strong>This</strong> Month', 'next-event-calendar'); ?></p>
	
    <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
    
			
			<?php // Extratct day, month, year, hour, min
			
				$original_date = get_post_meta($post->ID, 'data_nec_events', TRUE); 
				$my_day = date("j", strtotime($original_date ));
				$my_month = date("M", strtotime($original_date ));
				$my_month_full = date("F", strtotime($original_date ));
				$my_year = date("Y", strtotime($original_date ));  
				$my_hour = date("H", strtotime($original_date )); 
				$my_min = date("i", strtotime($original_date ));  
			?>
				 

			<?php // If is not this month, show the month
			
				if( $my_month != $current_month ):
				    $current_month = $my_month_full;
				    echo '<p class="tit-month"><strong>' . $current_month .'</strong>&nbsp; '. $my_year .'</p>';
				endif;	 
			?>
	 
			<div class="nec-element <?php if(get_option('columns_option') == "radio-two"){ ?> nec-col3 <?php } ?>">
				
				<div class="nec-img">
				
					<div class="nec-day"><div class="nec-d"><?php echo $my_day; ?></div> <div class="nec-m"><?php echo $my_month; ?></div></div>
					 		
					<?php if ( has_post_thumbnail() ) {
					
					the_post_thumbnail('thumbnail');
					
					} else { 
						
						echo '<img src="' . plugins_url( 'img/image-fallback.png', __FILE__ ) . '" > ';
	
					 } ?> 
				 		
					<?php // If the day is today, show the word Today
						
						if( ($my_day == $current_day) && ($my_month == $current_month_save)):
							echo '<div class="nec-today">'.__('TODAY', 'next-event-calendar').'</div>';
						endif;
					
					?>
				 	
				</div>
				 	
				<div class="nec-head">
					
					<div class="nec-info"> 
						
						<?php echo '<img class="nec-clock" src="' . plugins_url( 'img/clock.png', __FILE__ ) . '" > '; echo $my_hour; ?>:<?php echo $my_min; ?>
						
						<?php // Show the Event Category
	
						$terms = get_the_terms( $post->ID, 'nec_cat_events' );
						
						if (is_array($terms)) {
							foreach ($terms as $term){
								echo  '- <span class="nec-label nec-item-'. $term->slug .'"> ' . $term->name . '</span>';
							}
						}
	
						?>
					
					</div>
				
					<div class="nec-tit"><?php the_title(); ?></div>

				</div>
				
				<div class="nec-popup">
					
					<div class="nec-cont">
							
						<div class="in-cont">
							
							<?php  $content = get_the_excerpt();
								echo substr($content, 0, strpos($content, ' ', 260)) ?>
							
							<?php // Determinate the Event Link
							
							$custom_link = get_post_meta($post->ID, 'link_nec_events', true);
							
							if($custom_link != ''){					
								$event_link = $custom_link;
							} else {
								$event_link = get_permalink();	
							} ?>
								
							<a class="nec-show" href="<?php echo $event_link ?>"><?php _e('Show the event', 'next-event-calendar'); ?></a>
							
						</div>
						
					</div>
				
				</div>
				
			</div>
				
    
    <?php endwhile; ?>
    <?php endif;  wp_reset_query(); ?>
    
</div>