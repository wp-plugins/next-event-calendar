<?php 

/* ------------------------------------------------------------------------- *
 *  Page Options
/* ------------------------------------------------------------------------- */


// create custom plugin settings menu
add_action('admin_menu', 'nec_create_menu');

function nec_create_menu() {

	//create new top-level menu
	add_menu_page('NEC Plugin Settings', 'NEC Settings', 'administrator', __FILE__, 'nec_settings_page',plugins_url('/img/calendar.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
}

function register_mysettings() {
	
	//register our settings
	register_setting( 'nec-settings-group', 'timezone_option' );
	register_setting( 'nec-settings-group', 'hidemenu_option' );
	register_setting( 'nec-settings-group', 'columns_option' );
	register_setting( 'nec-settings-group', 'disable_style' );
}

function nec_settings_page() {

?>
<div class="wrap">
<h2 style="margin-top:10px"><img style="margin: -5px 10px 0 10px" class="icon32" src="<?php echo plugins_url( 'img/calendar_2.png', __FILE__ ); ?>" >Next Event Calendar</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'nec-settings-group' ); ?>
    <?php do_settings_sections( 'nec-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><?php _e('Select your Timezone: ','next-event-calendar');?></th>
        <td>
            <select name="timezone_option">
                  <option value="Pacific/Wake" <?php if(get_option('timezone_option') == "Pacific/Wake") echo "selected";?>>(GMT -12:00) International Date Line West</option>
                  <option value="Pacific/Apia" <?php if(get_option('timezone_option') == "Pacific/Apia") echo "selected";?>>(GMT -11:00) Midway Island, Samoa</option>
                  <option value="Pacific/Honolulu" <?php if(get_option('timezone_option') == "Pacific/Honolulu") echo "selected";?>>(GMT -10:00) Hawaii</option>
                  <option value="America/Anchorage" <?php if(get_option('timezone_option') == "America/Anchorage") echo "selected";?>>(GMT -9:00) Alaska</option>
                  <option value="America/Los_Angeles" <?php if(get_option('timezone_option') == "America/Los_Angeles") echo "selected";?>>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
                  <option value="America/Phoenix" <?php if(get_option('timezone_option') == "America/Phoenix") echo "selected";?>>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
                  <option value="America/Chicago" <?php if(get_option('timezone_option') == "America/Chicago") echo "selected";?>>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
                  <option value="America/New_York" <?php if(get_option('timezone_option') == "America/New_York") echo "selected";?>>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
                  <option value="America/Caracas" <?php if(get_option('timezone_option') == "America/Caracas") echo "selected";?>>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
                  <option value="America/St_Johns" <?php if(get_option('timezone_option') == "America/St_Johns") echo "selected";?>>(GMT -3:30) Newfoundland</option>
                  <option value="America/Argentina/Buenos_Aires" <?php if(get_option('timezone_option') == "America/Argentina/Buenos_Aires") echo "selected";?>>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
                  <option value="America/Noronha" <?php if(get_option('timezone_option') == "America/Noronha") echo "selected";?>>(GMT -2:00) Mid-Atlantic</option>
                  <option value="Atlantic/Azores" <?php if(get_option('timezone_option') == "Atlantic/Azores") echo "selected";?>>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
                  <option value="Europe/London" <?php if(get_option('timezone_option') == "Europe/London") echo "selected";?>>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
                  <option value="Europe/Berlin" <?php if(get_option('timezone_option') == "Europe/Berlin") echo "selected";?>>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris, Berlin , Rome</option>
                  <option value="Europe/Istanbul" <?php if(get_option('timezone_option') == "Europe/Istanbul") echo "selected";?>>(GMT +2:00) Kaliningrad, South Africa</option>
                  <option value="Asia/Baghdad" <?php if(get_option('timezone_option') == "Asia/Baghdad") echo "selected";?>>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
                  <option value="Asia/Tehran" <?php if(get_option('timezone_option') == "Asia/Tehran") echo "selected";?>>(GMT +3:30) Tehran</option>
                  <option value="Asia/Tbilisi" <?php if(get_option('timezone_option') == "Asia/Tbilisi") echo "selected";?>>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
                  <option value="Asia/Kabul" <?php if(get_option('timezone_option') == "Asia/Kabul") echo "selected";?>>(GMT +4:30) Kabul</option>
                  <option value="Asia/Karachi" <?php if(get_option('timezone_option') == "Asia/Karachi") echo "selected";?>>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
                  <option value="Asia/Calcutta" <?php if(get_option('timezone_option') == "Asia/Calcutta") echo "selected";?>>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
                  <option value="Asia/Katmandu" <?php if(get_option('timezone_option') == "Asia/Katmandu") echo "selected";?>>(GMT +5:45) Kathmandu</option>
                  <option value="Asia/Dhaka" <?php if(get_option('timezone_option') == "Asia/Dhaka") echo "selected";?>>(GMT +6:00) Almaty, Dhaka, Colombo</option>
                  <option value="Asia/Bangkok" <?php if(get_option('timezone_option') == "Asia/Bangkok") echo "selected";?>>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
                  <option value="Asia/Hong_Kong" <?php if(get_option('timezone_option') == "Asia/Hong_Kong") echo "selected";?>>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
                  <option value="Asia/Tokyo" <?php if(get_option('timezone_option') == "Asia/Tokyo") echo "selected";?>>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
                  <option value="Australia/Darwin" <?php if(get_option('timezone_option') == "Australia/Darwin") echo "selected";?>>(GMT +9:30) Adelaide, Darwin</option>
                  <option value="Australia/Sydney" <?php if(get_option('timezone_option') == "Australia/Sydney") echo "selected";?>>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
                  <option value="Asia/Magadan" <?php if(get_option('timezone_option') == "Asia/Magadan") echo "selected";?>>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
                  <option value="Pacific/Fiji" <?php if(get_option('timezone_option') == "Pacific/Fiji") echo "selected";?>>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
            </select>
        </td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><?php _e('Hide Menu','next-event-calendar');?></th>
        	<td><label><input type="checkbox" name="hidemenu_option" value="no" <?php if(get_option('hidemenu_option') == "no") echo "checked";?> /> <?php _e('Hide Menu in Calendar Page','next-event-calendar');?></label></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Layout Options</th>
        <td>
	        <input type="radio" name="columns_option" id="meta-radio-one" value="radio-one" <?php if(get_option('columns_option') == "radio-one") echo "checked";?> checked> 2 Column Layout <br/>
	        <input type="radio" name="columns_option" id="meta-radio-two" value="radio-two" <?php if(get_option('columns_option') == "radio-two") echo "checked";?>> 3 Column Layout<br/>
        </td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><?php _e('Disable Style','next-event-calendar');?></th>
        	<td><label><input type="checkbox" name="disable_style" value="yes" <?php if(get_option('disable_style') == "yes") echo "checked";?> /> <?php _e('Check this box to use your own CSS styling. <br/><small>Store your styling in a CSS file called <strong>next-event-calendar.css</strong> in your theme\'s folder.</small>','next-event-calendar');?></label></td>
        </tr>

    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>