<?php
/**
 * Plugin Name: Carrier Setup Form by BrokerCarrier
 * Description: Add your carrier setup form to any WordPress page. Requires an active BrokerCarrier account.
 * Version: 1.1
 * Requires at least: 4.7
 * Tested up to: 5.5
 * Requires PHP: 7.0
 * Text Domain: brokercarrier
 * Author: BrokerCarrier
 * Author URI: https://brokercarrier.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit();
}

/** Settings Page in WP Admin **/
function brokerCarrier_register_settings() {
    register_setting('brokercarrier_options', 'brokercarrier_options', 'brokerCarrier_options_validate' );
    add_settings_section('api_settings', '', 'brokerCarrier_section_text', 'brokercarrier');
    add_settings_field('brokercarrier_setting_account_number', 'Account Number', 'brokerCarrier_setting_account_number', 'brokercarrier', 'api_settings');
    add_settings_field('brokercarrier_setting_api_key', 'API Key', 'brokerCarrier_setting_api_key', 'brokercarrier', 'api_settings');
}
add_action('admin_init', 'brokerCarrier_register_settings');

function brokerCarrier_options_validate($input) {
    $newinput['account_number'] = trim($input['account_number']);
    $newinput['api_key'] = trim($input['api_key']);
    return $newinput;
}

function brokerCarrier_section_text() {
    echo '<div style="background-color: #fff; border: 1px solid #aaa; border-radius: 16px; padding: 12px 0 12px 28px;">';
    echo '<p>Your 9-digit account number and API key are available in the <span style="font-weight: bold;">Website Integration</span> section of your <span style="font-weight: bold;"><a href="https://brokercarrier.com/dashboard/onboarding/setup#websiteIntegration" target="_blank">Onboarding Setup</a> <span style="color: #777;">(&nearr;)</span></span> screen.</p>';
    echo '<p>If you are logged out of your BrokerCarrier account, please log in and proceed to <span style="font-weight: bold;">Onboarding Setup &rarr; Website Integration</span>.</p>';
    echo '<h3>To add your carrier setup form to any page:</h3>';
    echo '<ol><li>Paste your BrokerCarrier account number in the first field below.</li><li>Paste your API key in the second field below and click <span style="font-weight: bold;">Save</span>.</li><li>Add the following shortcode to any page: <span style="font-weight: bold;">[brokercarrier]</span></li></ol>';
    echo '</div>';
}

function brokerCarrier_setting_account_number() {
    $options = get_option('brokercarrier_options');
    echo '<input id="brokercarrier_setting_account_number" name="brokercarrier_options[account_number]" type="number" value="';
    echo esc_attr($options['account_number']);
    echo '" />';    
}

function brokerCarrier_setting_api_key() {
    $options = get_option('brokercarrier_options');
    echo '<input id="brokercarrier_setting_api_key" name="brokercarrier_options[api_key]" type="text" size="40" value="';
    echo esc_attr($options['api_key']);
    echo '" />';    
}

function brokerCarrier_render_plugin_settings_page() {
    ?>
    <h1>BrokerCarrier Settings</h1>
    <form action="options.php" method="post">
        <?php 
        settings_fields('brokercarrier_options');
        do_settings_sections('brokercarrier'); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
    </form>
    <?php
}

function brokerCarrier_add_settings_page() {
    add_options_page('BrokerCarrier for WordPress', 'BrokerCarrier', 'manage_options', 'brokercarrier', 'brokerCarrier_render_plugin_settings_page');
}
add_action('admin_menu', 'brokerCarrier_add_settings_page');


/** Shortcode display on front end **/
function brokerCarrier_embed_code() {

    $bcSettings = get_option('brokercarrier_options');

    $content = '<iframe id="ifBrokerCarrier" src="https://brokercarrier.com/carrier/setup?join=' . $bcSettings['account_number'];
    $content .= '&kmkr=' . $bcSettings['api_key'];
    $content .= '" style="width:100%;height:100%;min-height:800px;border:0;" frameborder="0" allowfullscreen="" aria-hidden="false"></iframe>';
    $content .= '<script>window.addEventListener("message",function(e){if(e.origin.includes("://brokercarrier")){document.getElementById("ifBrokerCarrier").style.height=e.data.height+"px";}else{return;}},{passive: false});</script>';
    
    return $content;

}
add_shortcode('brokercarrier', 'brokerCarrier_embed_code');