<?php

/**
 * Curl http request.
 */

namespace EnUvs;

use EnUvs\EnUvsCurl;

/**
 * Test connection request.
 * Class EnUvsTestConnection
 * @package EnUvsTestConnection
 */
if (!class_exists('EnUvsTestConnection')) {

    class EnUvsTestConnection {

        /**
         * Hook in ajax handlers.
         */
        public function __construct() {
            add_action('wp_ajax_nopriv_en_uvs_test_connection', [$this, 'en_uvs_test_connection']);
            add_action('wp_ajax_en_uvs_test_connection', [$this, 'en_uvs_test_connection']);
        }

        /**
         * Handle Connection Settings Ajax Request
         */
        public function en_uvs_test_connection() {
            $en_post_data = [];
            if (isset($_POST['en_post_data']) && !empty($_POST['en_post_data'])) {
                parse_str($_POST['en_post_data'], $en_post_data);
            }

            $en_request_indexing = json_decode(EN_UVS_SET_CONNECTION_SETTINGS, true);
            $en_connection_request = json_decode(EN_UVS_GET_CONNECTION_SETTINGS, true);

            foreach ($en_post_data as $en_request_name => $en_request_value) {
                if('uvs_small_carrer_id' == $en_request_name){
                    $en_connection_request[$en_request_indexing[$en_request_name]['eniture_action']] = [$en_request_value];
                }else{
                    $en_connection_request[$en_request_indexing[$en_request_name]['eniture_action']] = $en_request_value;
                }
            }

            $en_connection_request['carrierMode'] = 'test';

            if(!empty($en_connection_request['apiKey']) && !empty($en_connection_request['shipEngineCarrierIds'])){
                $en_connection_request['myCarriersInShipengine'] = '1';
            }else{
                $en_connection_request['myCarriersInShipengine'] = '0';
            }

            $en_connection_request = apply_filters('en_uvs_add_connection_request', $en_connection_request);
            echo EnUvsCurl::en_uvs_sent_http_request(
                EN_UVS_HITTING_API_URL, $en_connection_request, 'POST', 'Connection'
            );
            exit;
        }

    }

}