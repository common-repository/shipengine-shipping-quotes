<?php

/**
 * App Name details.
 */

namespace EnUvs;

use EnUvs\EnUvsConnectionSettings;
use EnUvs\EnUvsQuoteSettingsDetail;

/**
 * Config values.
 * Class EnUvsConfig
 * @package EnUvsConfig
 */
if (!class_exists('EnUvsConfig')) {

    class EnUvsConfig
    {
        /**
         * Save config settings
         */
        static public function do_config()
        {
            define('EN_UVS_PLAN', get_option('en_uvs_plan_number'));
            !empty(get_option('en_uvs_plan_message')) ? define('EN_UVS_PLAN_MESSAGE', get_option('en_uvs_plan_message')) : define('EN_UVS_PLAN_MESSAGE', EN_UVS_704);
            define('EN_UVS_NAME', 'ShipEngine Shipping Rates');
            define('EN_UVS_PLUGIN_URL', plugins_url());
            define('EN_UVS_ABSPATH', ABSPATH);
            define('EN_UVS_DIR', plugins_url(EN_UVS_MAIN_DIR));
            define('EN_UVS_DIR_FILE', plugin_dir_url(EN_UVS_MAIN_FILE));
            define('EN_UVS_FILE', plugins_url(EN_UVS_MAIN_FILE));
            define('EN_UVS_BASE_NAME', plugin_basename(EN_UVS_MAIN_FILE));
            define('EN_UVS_SERVER_NAME', self::en_get_server_name());

            define('EN_UVS_DECLARED_ZERO', 0);
            define('EN_UVS_DECLARED_ONE', 1);
            define('EN_UVS_DECLARED_ARRAY', []);
            define('EN_UVS_DECLARED_FALSE', false);
            define('EN_UVS_DECLARED_TRUE', true);
            define('EN_UVS_SHIPPING_NAME', 'ShipEngine Shipping Rates');
            define('EN_UVS_PLUGIN_ID', 'EnUvsShippingRates');

            $weight_threshold = get_option('en_weight_threshold_lfq');
            $weight_threshold = isset($weight_threshold) && $weight_threshold > 0 ? $weight_threshold : 150;
            define('EN_UVS_SHIPMENT_WEIGHT_EXCEEDS_PRICE', $weight_threshold);
            define('EN_UVS_SHIPMENT_WEIGHT_EXCEEDS', get_option('en_quote_settings_return_ltl_rates_uvs'));
            if (!defined('EN_UVS_ROOT_URL')) {
                define('EN_UVS_ROOT_URL', esc_url('https://eniture.com'));
            }
            define('EN_UVS_ROOT_URL_QUOTES', esc_url('https://ws102.eniture.com'));
            define('EN_UVS_ROOT_URL_PRODUCTS', EN_UVS_ROOT_URL . '/products/');
            define('EN_UVS_SBS_URL', EN_UVS_ROOT_URL . '/woocommerce-standard-box-sizes/');
            define('EN_UVS_SUPPORT_URL', esc_url('https://support.eniture.com/home'));
            define('EN_UVS_DOCUMENTATION_URL', EN_UVS_ROOT_URL . '/woocommerce-shipengine-shipping-rates/#documentation');
            define('EN_UVS_HITTING_API_URL', EN_UVS_ROOT_URL_QUOTES . '/shipengine/quotes.php');
            define('EN_UVS_ADDRESS_HITTING_URL', EN_UVS_ROOT_URL_QUOTES . '/addon/google-location.php');
            define('EN_UVS_PLAN_HITTING_URL', EN_UVS_ROOT_URL_QUOTES . '/web-hooks/subscription-plans/create-plugin-webhook.php?');
            define('EN_UVS_ORDER_EXPORT_HITTING_URL', 'https://analytic-data.eniture.com/index.php');

            define('EN_UVS_SET_CONNECTION_SETTINGS', wp_json_encode(EnUvsConnectionSettings::en_set_connection_settings_detail()));
            define('EN_UVS_GET_CONNECTION_SETTINGS', wp_json_encode(EnUvsConnectionSettings::en_get_connection_settings_detail()));
            define('EN_UVS_SET_QUOTE_SETTINGS', wp_json_encode(EnUvsQuoteSettingsDetail::en_uvs_quote_settings()));
            define('EN_UVS_GET_QUOTE_SETTINGS', wp_json_encode(EnUvsQuoteSettingsDetail::en_uvs_get_quote_settings()));

            $en_app_set_quote_settings = json_decode(EN_UVS_SET_QUOTE_SETTINGS, true);

            define('EN_UVS_ALWAYS_ACCESSORIAL', wp_json_encode([]));
            define('EN_UVS_ACCESSORIAL', wp_json_encode(EnUvsQuoteSettingsDetail::en_uvs_compare_accessorial($en_app_set_quote_settings)));
        }

        /**
         * Get Host
         * @param type $url
         * @return type
         */
        static public function en_get_host($url)
        {
            $parse_url = parse_url(trim($url));
            if (isset($parse_url['host'])) {
                $host = $parse_url['host'];
            } else {
                $path = explode('/', $parse_url['path']);
                $host = $path[0];
            }
            return trim($host);
        }

        /**
         * Get Domain Name
         */
        static public function en_get_server_name()
        {
            global $wp;
            $wp_request = (isset($wp->request)) ? $wp->request : '';
            $url = home_url($wp_request);
            return self::en_get_host($url);
        }

    }

}
