<?php
/*
 * Plugin Name: Lookery Amplifier
 * Version: 1.0
 * Plugin URI: http://code.google.com/p/lookery-amplifier-wordpress/
 * Description: Adds the necessary JavaScript code to enable <a href="http://www.lookery.com/">Lookery</a> Audience Analytics. After enabling this plugin visit <a href="options-general.php?page=lookery-amplifier.php">the options page</a> and enter your Lookery code and enable logging. Plugin Based on quantcast-quantifier by James Turner. Thanks for the template ;-)
 * Author: David Cancel
 * Author URI: http://www.davidcancel.com
 */

// Constants for enabled/disabled state
define("lk_enabled", "enabled", true);
define("lk_disabled", "disabled", true);

// Defaults, etc.
define("key_lk_status", "lk_status", true);
define("key_lk_admin", "lk_admin_status", true);
define("key_lk_tracker_code", "lk_tracker_code", true);
define("key_lk_footer", "lk_footer", true);

define("lk_status_default", lk_disabled, true);
define("lk_admin_default", lk_enabled, true);
define("lk_extra_default", "", true);
define("lk_footer_default", lk_disabled, true);

// Create the default key and status
add_option(key_lk_status, lk_status_default, 'If Lookery Amplifier logging in turned on or off.');
add_option(key_lk_admin, lk_admin_default, 'If WordPress admins are counted in Lookery Amplifier.');
add_option(key_lk_tracker_code, lk_extra_default, 'Addition Lookery Amplifier tracking options');
add_option(key_lk_footer, lk_footer_default, 'If Lookery Amplifier is outputting in the footer');

// Create a option page for settings
add_action('admin_menu', 'add_lk_option_page');

// Hook in the options page function
function add_lk_option_page() {
	add_options_page('Lookery Amplifier Options', 'Lookery Amplifier', 8, basename(__FILE__), 'lk_options_page');
}

function lk_options_page() {
	// If we are a postback, store the options
 	if (isset($_POST['info_update'])) {
		// Update the status
		$lk_status = $_POST[key_lk_status];
		if (($lk_status != lk_enabled) && ($lk_status != lk_disabled))
			$lk_status = lk_status_default;
		update_option(key_lk_status, $lk_status);

		// Update the admin logging
		$lk_admin = $_POST[key_lk_admin];
		if (($lk_admin != lk_enabled) && ($lk_admin != lk_disabled))
			$lk_admin = lk_admin_default;
		update_option(key_lk_admin, $lk_admin);

		// Update the extra tracking code
		$lk_tracker_code = $_POST[key_lk_tracker_code];
		update_option(key_lk_tracker_code, $lk_tracker_code);

		// Update the footer
		$lk_footer = $_POST[key_lk_footer];
		if (($lk_footer != lk_enabled) && ($lk_footer != lk_disabled))
			$lk_footer = lk_footer_default;
		update_option(key_lk_footer, $lk_footer);

		// Give an updated message
		echo "<div class='updated fade'><p><strong>Lookery Amplifier settings saved.</strong></p></div>";
	}
	// Output the options page
	?>

		<div class="wrap">
		<form method="post" action="options-general.php?page=lookery-amplifier.php">
		<?php //ga_nonce_field(); ?>
			<h2>Lookery Amplifier Options</h2>
			<h3>Basic Options</h3>
			<?php if (get_option(key_lk_status) == lk_disabled) { ?>
				<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				Lookery Amplifier integration is currently <strong>DISABLED</strong>.
				</div>
			<?php } ?>
			<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
				<tr>
					<th width="30%" valign="top" style="padding-top: 10px;">
						<label for="<?php echo key_lk_status ?>">Lookery Amplifier logging is:</label>
					</th>
					<td>
						<?php
						echo "<select name='".key_lk_status."' id='".key_lk_status."'>\n";
						
						echo "<option value='".lk_enabled."'";
						if(get_option(key_lk_status) == lk_enabled)
							echo " selected='selected'";
						echo ">Enabled</option>\n";
						
						echo "<option value='".lk_disabled."'";
						if(get_option(key_lk_status) == lk_disabled)
							echo" selected='selected'";
						echo ">Disabled</option>\n";
						
						echo "</select>\n";
						?>
					</td>
				</tr>
			</table>
			<h3>Advanced Options</h3>
				<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
				<tr>
					<th width="30%" valign="top" style="padding-top: 10px;">
						<label for="<?php echo key_lk_admin ?>">WordPress admin logging:</label>
					</th>
					<td>
						<?php
						echo "<select name='".key_lk_admin."' id='".key_lk_admin."'>\n";
						
						echo "<option value='".lk_enabled."'";
						if(get_option(key_lk_admin) == lk_enabled)
							echo " selected='selected'";
						echo ">Enabled</option>\n";
						
						echo "<option value='".lk_disabled."'";
						if(get_option(key_lk_admin) == lk_disabled)
							echo" selected='selected'";
						echo ">Disabled</option>\n";
						
						echo "</select>\n";
						?>
						<p style="margin: 5px 10px;">Disabling this option will prevent all logged in WordPress admins from showing up on your Lookery Amplifier reports. A WordPress admin is defined as a user with a level 8 or higher. Your user level <?php if ( current_user_can('level_8') ) echo 'is at least 8'; else echo 'is less than 8'; ?>.</p>
					</td>
				</tr>
				<tr>
					<th width="30%" valign="top" style="padding-top: 10px;">
						<label for="<?php echo key_lk_footer ?>">Footer tracking code:</label>
					</th>
					<td>
						<?php
						echo "<select name='".key_lk_footer."' id='".key_lk_footer."'>\n";
						
						echo "<option value='".lk_enabled."'";
						if(get_option(key_lk_footer) == lk_enabled)
							echo " selected='selected'";
						echo ">Enabled</option>\n";
						
						echo "<option value='".lk_disabled."'";
						if(get_option(key_lk_footer) == lk_disabled)
							echo" selected='selected'";
						echo ">Disabled</option>\n";
						
						echo "</select>\n";
						?>
						<p style="margin: 5px 10px;">Enabling this option will insert the Lookery Amplifier tracking code in your site's footer instead of your header. This will speed up your page loading if turned on. Not all themes support code in the footer, so if you turn this option on, be sure to check the Lookery Amplifier code is still displayed on your site.</p>
					</td>
				</tr>
				<tr>
					<th valign="top" style="padding-top: 10px;">
						<label for="<?php echo key_lk_tracker_code; ?>">Tracking code:</label>
					</th>
					<td>
						<?php
						echo "<textarea cols='100' rows='8' ";
						echo "name='".key_lk_tracker_code."' ";
						echo "id='".key_lk_tracker_code."'>";
						echo stripslashes(get_option(key_lk_tracker_code))."</textarea>\n";
						?>
						<p style="margin: 5px 10px;">Enter the tracking code from Lookery.  You can find your <a href="http://www.lookery.com/owner/sites/" target="_blank" title="Open Lookery site">Lookery code here</a>.  Copy and paste the code between these tags &lt;!-- Start Lookery tag --&gt; &lt;!-- End Lookery tag --&gt;.  You will require a Lookery account.</p>
					</td>
				</tr>
				</table>
			<p class="submit">
				<input type='submit' name='info_update' value='Save Changes' />
			</p>
		</div>
		</form>

<?php
}

// Add the script
if (get_option(key_lk_footer) == lk_enabled) {
	add_action('wp_footer', 'add_lookery_amplifier');
} else {
	add_action('wp_head', 'add_lookery_amplifier');
}

// The guts of the Lookery Amplifier script
function add_lookery_amplifier() {
	$extra = stripslashes(get_option(key_lk_tracker_code));
	
	// If GA is enabled and has a valid key
	if (get_option(key_lk_status) != lk_disabled) {
		
		// Track if admin tracking is enabled or disabled and less than user level 8
		if ((get_option(key_lk_admin) == lk_enabled) || ((get_option(key_lk_admin) == lk_disabled) && ( !current_user_can('level_8') ))) {
			
			// Insert tracker code
			if ( '' != $extra ) {
				echo "<!-- Start Lookery By WP-Plugin: Lookery-Amplifier -->\n";
				echo $extra . "\n";
			}
		}
	}
}

?>