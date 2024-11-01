<?php

/**
 * Test connection details.
 */

namespace EnUvs;

/**
 * Add array for test connection.
 * Class EnUvsConnectionSettings
 * @package EnUvsConnectionSettings
 */
if (!class_exists('EnUvsConnectionSettings')) {

    class EnUvsConnectionSettings
    {

        static $get_connection_details = [];

        /**
         * Connection settings template.
         * @return array
         */
        static public function en_load()
        {
            echo  '<div class="en_uvs_connection_settings">';
            
            $start_settings = [
                'en_connection_settings_start_uvs' => [
                    'name' => __('', 'woocommerce-settings-uvs'),
                    'type' => 'title',
                    'id' => 'en_connection_settings_uvs',
                ],
            ];

            // App Name Connection Settings Detail
            $eniture_settings = self::en_set_connection_settings_detail();

            $end_settings = [
                'en_connection_settings_end_uvs' => [
                    'type' => 'sectionend',
                    'id' => 'en_connection_settings_end_uvs'
                ]
            ];

            $settings = array_merge($start_settings, $eniture_settings, $end_settings);

            return $settings;
        }

        /**
         * Connection Settings Detail
         * @return array
         */
        static public function en_get_connection_settings_detail()
        {
            $connection_request = self::en_static_request_detail();
            $en_request_indexing = json_decode(EN_UVS_SET_CONNECTION_SETTINGS, true);
            foreach ($en_request_indexing as $key => $value) {
                $saved_connection_detail = get_option($key);
                if('uvs_small_carrer_id' == $key){
                    $connection_request[$value['eniture_action']] = [$saved_connection_detail];
                }else{
                    $connection_request[$value['eniture_action']] = $saved_connection_detail;
                }
                strlen($saved_connection_detail) > 0 ?
                    self::$get_connection_details[$value['eniture_action']] = $saved_connection_detail : '';
            }

            if(!empty($connection_request['apiKey']) && !empty($connection_request['shipEngineCarrierIds'])){
                $connection_request['myCarriersInShipengine'] = '1';
            }

            add_filter('en_uvs_reason_quotes_not_returned', [__CLASS__, 'en_uvs_reason_quotes_not_returned'], 99, 1);

            return $connection_request;
        }

        /**
         * Saving reasons to show proper error message on the cart or checkout page
         * When quotes are not returning
         * @param array $reasons
         * @return array
         */
        static public function en_uvs_reason_quotes_not_returned($reasons)
        {
            return empty(self::$get_connection_details) ? array_merge($reasons, [EN_UVS_711]) : $reasons;
        }

        /**
         * Static Detail Set
         * @return array
         */
        static public function en_static_request_detail()
        {
            return
                [
                    'serverName' => EN_UVS_SERVER_NAME,
                    'platform' => 'WordPress',
                    'carrierType' => 'small',
                    'carrierName' => 'shipEngine',
                    'carrierMode' => 'pro',
                    'requestVersion' => '2.0',
                    'apiVersion' => '2.0',
                    'requestKey' => time(),
                ];
        }

        /**
         * Connection Settings Detail Set
         * @return array
         */
        static public function en_set_connection_settings_detail()
        {
            return
                [

                    'uvs_small_carrer_id' => [
                        'eniture_action' => 'shipEngineCarrierIds',
                        'name' => __('Carrier ID ', 'woocommerce-settings-uvs'),
                        'type' => 'text',
                        'id' => 'uvs_small_carrer_id',
                        'custom_attributes' => [
                            'data-optional' => '1',
                            'data-semi-optional' => '1',
                            'data-semi-type'     => 'input',
                            'data-depends-on-id' => 'uvs_small_se_api_key',
                            'data-max-length'    => '100'
                        ],
                    ],
                    'uvs_small_se_api_key' => [
                        'eniture_action' => 'apiKey',
                        'name' => __('ShipEngine API Key ', 'woocommerce-settings-uvs'),
                        'type' => 'text',
                        'id' => 'uvs_small_se_api_key',
                        'custom_attributes' => [
                            'data-optional' => '1',
                            'data-semi-optional' => '1',
                            'data-semi-type'     => 'input',
                            'data-depends-on-id' => 'uvs_small_carrer_id',
                            'data-max-length'    => '100'
                        ],
                    ],
                    'uvs_small_licence_key' => [
                        'eniture_action' => 'licenseKey',
                        'name' => __('Eniture API Key ', 'woocommerce-settings-uvs'),
                        'type' => 'text',
                        'desc' => __('Obtain a Eniture API Key from <a href="' . EN_UVS_ROOT_URL_PRODUCTS . '" target="_blank" >eniture.com </a>', 'woocommerce-settings-uvs'),
                        'id' => 'uvs_small_licence_key'
                    ],
                ];
        }

    }

}