<?php
/**
 * Plugin Name:       Mobalize Online Appointment Bookings
 * Plugin URI:        https://www.mobalize.nl/wordpress.html
 * Description:       Add the Mobalize Book now button to your website. Accept bookings everywhere on your site.
 * Version:           1.3
 * Requires at least: 4.7
 * Requires PHP:      5.2
 * Author:            Marco Stuijvenberg
 * Author URI:        https://www.mobalize.nl/en
 * License:           GPL v2 or later
 * License URI:       https://www.mobalize.nl/en/terms.html
 * Text Domain:       mobalize-online-appointment-bookings
 * Domain Path:       /languages/
 */

include plugin_dir_path(__FILE__) . "admin.php";

function add_mobalize_button() {
    
    $clientHash = get_option("mobalize-button-client-hash", "");
    $enabled = get_option("mobalize-button-enabled", "no");

    if ($enabled == "yes") {

            wp_register_script('mobalizeButton','https://cdn.mobalize.nl/button.js', null, null, true);
            wp_enqueue_script('mobalizeButton');

        ?>
            <div id="mobalizePluginButton" hash="<?php echo $clientHash; ?>"></div>
        <?php
    }    
}

add_action('wp_footer', 'add_mobalize_button');