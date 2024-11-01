<?php

namespace EnUvs;

use EnUvs\EnUvsCurl;

if (!class_exists('EnUvsDistance')) {

    class EnUvsDistance
    {
        static public function get_address($map_address, $en_access_level, $en_destination_address = [])
        {
            $post_data = array(
                'acessLevel' => $en_access_level,
                'address' => $map_address,
                'originAddresses' => $map_address,
                'destinationAddress' => (isset($en_destination_address)) ? $en_destination_address : '',
                'eniureLicenceKey' => get_option('uvs_small_licence_key'),
                'ServerName' => EN_UVS_SERVER_NAME,
            );

            return EnUvsCurl::en_uvs_sent_http_request(EN_UVS_ADDRESS_HITTING_URL, $post_data, 'POST', 'Address');
        }
    }
}