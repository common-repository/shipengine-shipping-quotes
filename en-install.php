<?php
/**
 * App install hook
 */

use EnUvs\EnUvsConfig;
use EnUvs\EnUvsWarehouse;

if (!function_exists('en_uvs_installation')) {

    function en_uvs_installation() {
        apply_filters('en_register_activation_hook', false);
    }

    register_activation_hook(EN_UVS_MAIN_FILE, 'en_uvs_installation');
}

/**
 * App load admin side files of css and js hook
 */
if (!function_exists('en_uvs_admin_enqueue_scripts')) {

    function en_uvs_admin_enqueue_scripts() {

        wp_enqueue_script('EnWickedPicker', EN_UVS_DIR_FILE . '/admin/assets/en-wicked-picker.js', [], '1.0.1');
        wp_enqueue_script('EnUvsTagging', EN_UVS_DIR_FILE . '/admin/tab/location/assets/js/en-uvs-tagging.js', [], '1.0.1');
        
        wp_enqueue_script('EnUvsAdminJs', EN_UVS_DIR_FILE . '/admin/assets/en-uvs-admin.js', [], '1.0.3');
        wp_localize_script('EnUvsAdminJs', 'script', [
            'pluginsUrl' => EN_UVS_PLUGIN_URL,
        ]);

        wp_enqueue_script('EnUvsLocationScript', EN_UVS_DIR_FILE . '/admin/tab/location/assets/js/en-uvs-location.js', [], '1.0.1');
        wp_localize_script('EnUvsLocationScript', 'script', array(
            'pluginsUrl' => EN_UVS_PLUGIN_URL,
        ));

        wp_register_style('EnWickedPickerCss', EN_UVS_DIR_FILE . '/admin/assets/en-wicked-picker.css', [], '1.0.1');
        wp_enqueue_style('EnWickedPickerCss');

        wp_register_style('EnUvsLocationStyle', EN_UVS_DIR_FILE . '/admin/tab/location/assets/css/en-uvs-location.css', false, '1.0.1');
        wp_enqueue_style('EnUvsLocationStyle');

        wp_register_style('EnUvsAdminCss', EN_UVS_DIR_FILE . '/admin/assets/en-uvs-admin.css', false, '1.0.2');
        wp_enqueue_style('EnUvsAdminCss');

        // Shipping rules script and styles
        wp_enqueue_script('EnUvsShippingRulesStyle', EN_UVS_DIR_FILE . 'admin/tab/shipping-rules/assets/js/shipping_rules.js', array(), '1.0.1');
        wp_localize_script('EnUvsShippingRulesStyle', 'script', array(
            'pluginsUrl' => EN_UVS_PLUGIN_URL,
        ));
        wp_register_style('EnUvsShippingRulesScript', EN_UVS_DIR_FILE . 'admin/tab/shipping-rules/assets/css/shipping_rules.css', false, '1.0.0');
        wp_enqueue_style('EnUvsShippingRulesScript');
    }

    add_action('admin_enqueue_scripts', 'en_uvs_admin_enqueue_scripts');
}

/**
 * App load front-end side files of css and js hook
 */
if (!function_exists('en_uvs_frontend_enqueue_scripts')) {

    function en_uvs_frontend_enqueue_scripts() {
        wp_enqueue_script('EnUvsFrontEnd', EN_UVS_DIR_FILE . '/admin/assets/en-uvs-frontend.js', ['jquery'], '1.0.0');
        wp_localize_script('EnUvsFrontEnd', 'script', [
            'pluginsUrl' => EN_UVS_PLUGIN_URL,
        ]);
    }

    add_action('wp_enqueue_scripts', 'en_uvs_frontend_enqueue_scripts');
}

/**
 * Load tab file
 * @param $settings
 * @return array
 */
if (!function_exists('en_uvs_shipping_sections')) {

    function en_uvs_shipping_sections($settings) {
        $settings[] = include('admin/tab/en-tab.php');
        return $settings;
    }

    add_filter('woocommerce_get_settings_pages', 'en_uvs_shipping_sections', 10, 1);
}

/**
 * Show action links on plugins page
 * @param $actions
 * @param $plugin_file
 * @return array
 */
if (!function_exists('en_uvs_freight_action_links')) {

    function en_uvs_freight_action_links($actions, $plugin_file) {
        static $plugin;
        if (!isset($plugin)) {
            $plugin = EN_UVS_BASE_NAME;
        }

        if ($plugin == $plugin_file) {
            $settings = array('settings' => '<a href="admin.php?page=wc-settings&tab=uvs">' . __('Settings', 'General') . '</a>');
            $site_link = array('support' => '<a href="' . EN_UVS_SUPPORT_URL . '" target="_blank">Support</a>');
            $actions = array_merge($settings, $actions);
            $actions = array_merge($site_link, $actions);
        }

        return $actions;
    }

    add_filter('plugin_action_links', 'en_uvs_freight_action_links', 10, 2);
}

/**
 * globally script variable
 */
if (!function_exists('en_uvs_admin_inline_js')) {

    function en_uvs_admin_inline_js() {
        ?>
        <script>
            let EN_UVS_DIR_FILE
            = "<?php echo esc_js(EN_UVS_DIR_FILE); ?>";
        </script>
        <?php
    }

    add_action('admin_print_scripts', 'en_uvs_admin_inline_js');
}

/**
 * Transportation insight action links
 * @staticvar $plugin
 * @param $actions
 * @param $plugin_file
 * @return array
 */
if (!function_exists('en_uvs_admin_action_links')) {

    function en_uvs_admin_action_links($actions, $plugin_file) {
        static $plugin;
        if (!isset($plugin))
            $plugin = plugin_basename(__FILE__);
        if ($plugin == $plugin_file) {
            $settings = array('settings' => '<a href="admin.php?page=wc-settings&tab=uvs">' . __('Settings', 'General') . '</a>');
            $site_link = array('support' => '<a href="' . EN_UVS_SUPPORT_URL . '" target="_blank">Support</a>');
            $actions = array_merge($settings, $actions);
            $actions = array_merge($site_link, $actions);
        }
        return $actions;
    }

    add_filter('plugin_action_links_' . EN_UVS_BASE_NAME, 'en_uvs_admin_action_links', 10, 2);
}

/**
 * Transportation insight method in woo method list
 * @param $methods
 * @return string
 */
if (!function_exists('en_uvs_add_shipping_app')) {

    function en_uvs_add_shipping_app($methods) {
        $methods['EnUvsShippingRates'] = 'EnUvsShippingRates';
        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'en_uvs_add_shipping_app', 10, 1);
}
/**
 * The message show when no rates will display on the cart page
 */
if (!function_exists('en_none_shipping_rates')) {

    function en_none_shipping_rates() {
        $en_eniture_shipment = apply_filters('en_eniture_shipment', []);
        if (isset($en_eniture_shipment['LTL'])) {
            return esc_html("<div><p>There are no shipping methods available. 
                    Please double check your address, or contact us if you need any help.</p></div>");
        }
    }

    add_filter('woocommerce_cart_no_shipping_available_html', 'en_none_shipping_rates');
}

/**
 * Transportation insight plan status
 * @param array $plan_status
 * @return array
 */
if (!function_exists('en_uvs_plan_status')) {

    function en_uvs_plan_status($plan_status) {

        $features = ['hazardous_material', 'insurance'];
        foreach($features as $key => $feature){
            $response = apply_filters("uvs_plans_suscription_and_features", $feature);
            if (is_array($response)) {
                $plan_required = '1';
                $feature_status = EN_UVS_NAME . ': Upgrade to Standard Plan to enable.';
            }else{
                $plan_required = '0';
                $feature_status = EN_UVS_NAME . ': Enabled.';
            }

            $plan_status[$feature]['uvs'][] = 'uvs';
            $plan_status[$feature]['plan_required'][] = $plan_required;
            $plan_status[$feature]['status'][] = $feature_status;
        }

        return $plan_status;
    }

    add_filter('en_app_common_plan_status', 'en_uvs_plan_status', 10, 1);
}

/**
 * Filter to check ground transit status
 */
if (!function_exists('en_uvs_check_ground_transit_restrict_status')) {

    function en_uvs_check_ground_transit_restrict_status($ground_transit_statuses)
    {
        $ground_restrict_value = (false !== get_option('en_uvs_transit_days')) ? get_option('en_uvs_transit_days') : '';
        if ('' !== $ground_restrict_value && strlen(trim($ground_restrict_value)) 
            && true === apply_filters('uvs_plans_suscription_and_features', 'transit_days')) {
            $ground_transit_statuses['uvs'] = '1';
        }

        return $ground_transit_statuses;
    }

    add_filter('en_check_ground_transit_restrict_status', 'en_uvs_check_ground_transit_restrict_status', 11, 1);
}

if (!function_exists('en_uvs_add_product_details')) {

    function en_uvs_add_product_details() {
        new \EnUvs\EnUvsProductDetail();
    }

    en_uvs_add_product_details();
}

/**
 * Hide third party shipping rates
 * @param mixed $available_methods
 * @return mixed
 */
if (!function_exists('en_uvs_hide_shipping')) {

    function en_uvs_hide_shipping($available_methods) {
        $en_eniture_shipment = apply_filters('en_eniture_shipment', []);
        $en_shipping_applications = apply_filters('en_shipping_applications', []);
        $eniture_old_plugins = get_option('EN_Plugins');
        $eniture_old_plugins = $eniture_old_plugins ? json_decode($eniture_old_plugins, true) : [];
        $en_eniture_apps = array_merge($en_shipping_applications, $eniture_old_plugins);

        if (get_option('en_uvs_allow_other_plugin_quotes') == 'no' &&
                (isset($en_eniture_shipment['LTL'])) && count($available_methods) > 0) {
            foreach ($available_methods as $index => $method) {
                if (!in_array($method->method_id, $en_eniture_apps)) {
                    unset($available_methods[$index]);
                }
            }
        }

        return $available_methods;
    }

    add_filter('woocommerce_package_rates', 'en_uvs_hide_shipping', 99, 1);
}

/**
 * Eniture save app name
 * @param array $en_applications
 * @return array
 */
if (!function_exists('en_uvs_shipping_applications')) {

    function en_uvs_shipping_applications($en_applications) {
        return array_merge($en_applications, ['uvs']);
    }

    add_filter('en_shipping_applications', 'en_uvs_shipping_applications', 10, 1);
}

/**
 * Eniture admin notices
 */
if (!function_exists('en_uvs_admin_notices')) {

    function en_uvs_admin_notices() {
        $en_uvs_tabs = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : '';
        if (isset($en_uvs_tabs) && ($en_uvs_tabs == "uvs")) {
            echo '<div class="notice notice-success is-dismissible"> <p>' . EN_UVS_PLAN_MESSAGE . '</p> </div>';
        }
    }

    add_filter('admin_notices', 'en_uvs_admin_notices');
}

/**
 * Custom error message.
 * @param string $message
 * @return string|void
 */
if (!function_exists('en_uvs_error_message')) {

    function en_uvs_error_message($message) {
        $en_eniture_shipment = apply_filters('en_eniture_shipment', []);
        $reasons = apply_filters('en_uvs_reason_quotes_not_returned', []);

        if (isset($en_eniture_shipment['SPQ']) || !empty($reasons)) {
            $en_settings = json_decode(EN_UVS_SET_QUOTE_SETTINGS, true);
            $message = (isset($en_settings['custom_error_message'])) ? $en_settings['custom_error_message'] : '';
            $custom_error_enabled = (isset($en_settings['custom_error_enabled'])) ? $en_settings['custom_error_enabled'] : '';

            switch ($custom_error_enabled) {
                case 'prevent':
                    remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20, 2);
                    break;
                case 'allow':
                    add_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20, 2);
                    break;
                default:
                    $message = '<div><p>There are no shipping methods available. Please double check your address, or contact us if you need any help.</p></div>';
                    break;
            }

            $message = !empty($reasons) ? implode(", ", $reasons) : $message;
        }

        return __($message);
    }

    add_filter('woocommerce_cart_no_shipping_available_html', 'en_uvs_error_message', 999, 1);
    add_filter('woocommerce_no_shipping_available_html', 'en_uvs_error_message', 999, 1);
}

// Add plugin to active Enitures Plugins
if (!function_exists('en_uvs_create_option')) {

    function en_uvs_create_option() {
        $eniture_plugins = get_option('EN_Plugins');
        if (!$eniture_plugins) {
            add_option('EN_Plugins', wp_json_encode(array('EnUvsShippingRates')));
        } else {
            $plugins_array = json_decode($eniture_plugins, true);
            if (!in_array('EnUvsShippingRates', $plugins_array)) {
                array_push($plugins_array, 'EnUvsShippingRates');
                update_option('EN_Plugins', json_encode($plugins_array));
            }
        }
    }

    en_uvs_create_option();
}

/**
 * Filter For CSV Import
 */
if (!function_exists('en_import_dropship_location_csv')) {

    /**
     * Import drop ship location CSV
     * @param $data
     * @param $this
     * @return array
     */
    function en_import_dropship_location_csv($data, $parseData) {
        $en_product_freight_class = $en_product_freight_class_variation = '';
        $en_dropship_location = $locations = [];
        foreach ($data['meta_data'] as $key => $metaData) {
            $location = explode(',', trim($metaData['value']));
            switch ($metaData['key']) {
                // Update new columns
                case '_product_freight_class':
                    $en_product_freight_class = trim($metaData['value']);
                    unset($data['meta_data'][$key]);
                    break;
                case '_product_freight_class_variation':
                    $en_product_freight_class_variation = trim($metaData['value']);
                    unset($data['meta_data'][$key]);
                    break;
                case '_dropship_location_nickname':
                    $locations[0] = $location;
                    unset($data['meta_data'][$key]);
                    break;
                case '_dropship_location_zip_code':
                    $locations[1] = $location;
                    unset($data['meta_data'][$key]);
                    break;
                case '_dropship_location_city':
                    $locations[2] = $location;
                    unset($data['meta_data'][$key]);
                    break;
                case '_dropship_location_state':
                    $locations[3] = $location;
                    unset($data['meta_data'][$key]);
                    break;
                case '_dropship_location_country':
                    $locations[4] = $location;
                    unset($data['meta_data'][$key]);
                    break;
                case '_dropship_location':
                    $en_dropship_location = $location;
            }
        }

        // Update new columns
        if (strlen($en_product_freight_class) > 0) {
            $data['meta_data'][] = [
                'key' => '_ltl_freight',
                'value' => $en_product_freight_class,
            ];
        }

        // Update new columns
        if (strlen($en_product_freight_class_variation) > 0) {
            $data['meta_data'][] = [
                'key' => '_ltl_freight_variation',
                'value' => $en_product_freight_class_variation,
            ];
        }

        if (!empty($locations) || !empty($en_dropship_location)) {
            if (isset($locations[0]) && is_array($locations[0])) {
                foreach ($locations[0] as $key => $location_arr) {
                    $metaValue = [];
                    if (isset($locations[0][$key], $locations[1][$key], $locations[2][$key], $locations[3][$key])) {
                        $metaValue[0] = $locations[0][$key];
                        $metaValue[1] = $locations[1][$key];
                        $metaValue[2] = $locations[2][$key];
                        $metaValue[3] = $locations[3][$key];
                        $metaValue[4] = $locations[4][$key];
                        $dsId[] = en_serialize_dropship($metaValue);
                    }
                }
            } else {
                $dsId[] = en_serialize_dropship($en_dropship_location);
            }

            $sereializedLocations = maybe_serialize($dsId);
            $data['meta_data'][] = [
                'key' => '_dropship_location',
                'value' => $sereializedLocations,
            ];
        }
        return $data;
    }

    add_filter('woocommerce_product_importer_parsed_data', 'en_import_dropship_location_csv', '99', '2');
}

/**
 * Serialize drop ship
 * @param $metaValue
 * @return string
 * @global $wpdb
 */
if (!function_exists('en_serialize_dropship')) {

    function en_serialize_dropship($metaValue) {
        global $wpdb;
        $dropship = (array) reset($wpdb->get_results(
                                "SELECT id
                        FROM " . $wpdb->prefix . "warehouse WHERE nickname='$metaValue[0]' AND zip='$metaValue[1]' AND city='$metaValue[2]' AND state='$metaValue[3]' AND country='$metaValue[4]'"
        ));

        $dropship = array_map('intval', $dropship);

        if (empty($dropship['id'])) {
            $data = en_csv_import_dropship_data($metaValue);
            $wpdb->insert(
                    $wpdb->prefix . 'warehouse', $data
            );

            $dsId = $wpdb->insert_id;
        } else {
            $dsId = $dropship['id'];
        }

        return $dsId;
    }

}

/**
 * Filtered Data Array
 * @param $metaValue
 * @return array
 */
if (!function_exists('en_csv_import_dropship_data')) {

    function en_csv_import_dropship_data($metaValue) {
        return array(
            'city' => $metaValue[2],
            'state' => $metaValue[3],
            'zip' => $metaValue[1],
            'country' => $metaValue[4],
            'location' => 'dropship',
            'nickname' => (isset($metaValue[0])) ? $metaValue[0] : "",
        );
    }

}

// Define reference
if (!function_exists('en_uvs_plugin')) {

    function en_uvs_plugin($plugins) {
        $plugins['spq'] = (isset($plugins['spq'])) ? array_merge($plugins['spq'], ['uvs' => 'EnUvsShippingRates']) : ['uvs' => 'EnUvsShippingRates'];
        return $plugins;
    }

    add_filter('en_plugins', 'en_uvs_plugin');
}

/**
 * To export order 
 */
if (!function_exists('en_export_order_on_order_place')) {
	
	function en_export_order_on_order_place()
	{
        new \EnUvsOrderExport\EnUvsOrderExport();
	}
	
	en_export_order_on_order_place();
}

/**
 * uvs small plugin update now
 */
if(!function_exists('en_uvs_small_update_now')) {
    function en_uvs_small_update_now($upgrader_object, $options)
    {
        $en_uvs_small_path_name = EN_UVS_BASE_NAME;
        $plugin_info = get_plugins();
        $plugin_version = (isset($plugin_info[$en_uvs_small_path_name]['Version'])) ? $plugin_info[$en_uvs_small_path_name]['Version'] : '';

        if ($options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
            foreach($options['plugins'] as $each_plugin) {
                if ($each_plugin == $en_uvs_small_path_name) {
                    $en_uvs_wh = new EnUvsWarehouse();
                    $en_uvs_wh->create_warehouse_table();
                    $en_uvs_wh->create_uvs_shipping_rules_table();

                    update_option('en_uvs_small_update_now', $plugin_version);
                }
            }
        }
    }
    
    add_action( 'upgrader_process_complete', 'en_uvs_small_update_now', 10, 2);
}

$en_uvs_wh = new EnUvsWarehouse();
add_action('admin_init', [$en_uvs_wh, 'create_uvs_shipping_rules_table']);

// fdo va
add_action('wp_ajax_nopriv_uvs_s_fd', 'uvs_s_fd_api');
add_action('wp_ajax_uvs_s_fd', 'uvs_s_fd_api');
/**
 * UVS AJAX Request
*/
function uvs_s_fd_api()
{
    $store_name = EnUvsConfig::en_get_server_name();
    $company_id = $_POST['company_id'];
    $data = [
        'plateform'  => 'wp',
        'store_name' => $store_name,
        'company_id' => $company_id,
        'fd_section' => 'tab=uvs&section=section-4',
    ];

    if (is_array($data) && count($data) > 0) {
        $url = $_POST['disconnect'] != 'disconnect' ?  'https://freightdesk.online/validate-company' : 'https://freightdesk.online/disconnect-woo-connection';
        $response = wp_remote_post($url, [
                'method' => 'POST',
                'timeout' => 60,
                'redirection' => 5,
                'blocking' => true,
                'body' => $data,
            ]
        );
        $response = wp_remote_retrieve_body($response);
    }

    if ($_POST['disconnect'] == 'disconnect') {
        $result = json_decode($response);
        if ($result->status == 'SUCCESS') {
            update_option('en_fdo_company_id_status', 0);
        }
    }
    
    echo $response;
    exit();
}

add_action('rest_api_init', 'en_rest_api_init_status_uvs_s');
function en_rest_api_init_status_uvs_s()
{
    register_rest_route('fdo-company-id', '/update-status', array(
        'methods' => 'POST',
        'callback' => 'en_uvs_s_fdo_data_status',
        'permission_callback' => '__return_true'
    ));
}

/**
 * Update FDO coupon data
 * @param array $request
 * @return array|void
 */
function en_uvs_s_fdo_data_status(WP_REST_Request $request)
{
    $status_data = $request->get_body();
    $status_data_decoded = json_decode($status_data);
    if (isset($status_data_decoded->connection_status)) {
        update_option('en_fdo_company_id_status', $status_data_decoded->connection_status);
        update_option('en_fdo_company_id', $status_data_decoded->fdo_company_id);
    }

    return true;
}
