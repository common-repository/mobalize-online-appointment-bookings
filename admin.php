<?php

function load_mobalize_plugin_textdomain() {
    load_plugin_textdomain('mobalize-online-appointment-bookings', FALSE, basename( dirname( __FILE__ ) ) . '/languages/');
}
  
add_action('plugins_loaded', 'load_mobalize_plugin_textdomain');

function mobalize_setup_menu() {
    add_menu_page( 'Mobalize Settings', 'Mobalize', 'manage_options', 'mobalize-online-appointment-bookings', 'mobalize_admin_init' );
}

add_action('admin_menu', 'mobalize_setup_menu');

function mobalize_admin_init() {

    echo "<h1>";
    esc_html_e('Mobalize button settings', 'mobalize-online-appointment-bookings');
    echo "</h1>";
    
    if (
        $_SERVER['REQUEST_METHOD'] == 'POST' &&
        isset($_POST['mobalize_nonce']) &&
        wp_verify_nonce($_POST['mobalize_nonce'], 'mobalize_nonce')
    ) {

        // Don't actually read so we don't need to sanitize
        if (isset($_POST['enabled'])) {
            update_option("mobalize-button-enabled", "yes");
        } else {
            update_option("mobalize-button-enabled", "no");
        }

        $clientHash = sanitize_text_field($_POST['clientHash']);
        if (isset($clientHash)) {
            update_option("mobalize-button-client-hash", $clientHash);
        } else {
            update_option("mobalize-button-client-hash", "");
        }
    }

    $clientHash = get_option("mobalize-button-client-hash", "");
    $enabled = get_option("mobalize-button-enabled", "no");

    mobalize_renderForm($enabled, $clientHash);
}

function mobalize_renderForm($enabled, $clientHash) {
    ?>
    <style>
        input[type=submit] {
            margin-top: 10px;
            margin-left: 145px;
            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
        }
        .formRow {
            margin-top: 10px;            
        }
        label {
            display: inline-block;
            width: 140px;
            text-align: right;
        }​
    </style>
    <form method="post">
        <input type="hidden" name="mobalize_nonce" value="<?php echo wp_create_nonce('mobalize_nonce') ?>">
        <div class="formRow">
            <label><?php esc_html_e('Enabled', 'mobalize-online-appointment-bookings'); ?></label>
            <input type="checkbox" name="enabled" value="<?php echo $enabled; ?>" <?php if (mobalize_isEnabled($enabled)) { echo "checked"; } ?>> 
        </div>
        <div class="formRow">
            <label><?php esc_html_e('Api key', 'mobalize-online-appointment-bookings'); ?></label>
            <input type="text" name="clientHash" size="50" value="<?php echo $clientHash; ?>">
        </div>
        <input type="submit" value="<?php esc_html_e('Save changes', 'mobalize-online-appointment-bookings'); ?>" style="font-size: 16px">
    </form>
    <p>
        <?php 
            printf(
                __(
                    'You can find your Api key on this page in Mobalize: <a href=%s target="_blank">Your website</a>',
                    'mobalize-online-appointment-bookings'
                ),
                'https://app.mobalize.nl/settings/website'
            );
        ?>
    <p>
    <?php
}

function mobalize_isEnabled($enabled) {
    return $enabled == "yes";
}