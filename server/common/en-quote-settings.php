<?php

/**
 * App Name settings.
 */

namespace EnUvs;

/**
 * Get and save settings.
 * Class EnUvsQuoteSettingsDetail
 * @package EnUvsQuoteSettingsDetail
 */
if (!class_exists('EnUvsQuoteSettingsDetail')) {

    class EnUvsQuoteSettingsDetail
    {
        static public $en_uvs_accessorial = [];

        /**
         * Set quote settings detail
         */
        static public function en_uvs_get_quote_settings()
        {
            $accessorials = [];
            $en_settings = json_decode(EN_UVS_SET_QUOTE_SETTINGS, EN_UVS_DECLARED_TRUE);
            
            return $accessorials;
        }

        /**
         * Set quote settings detail
         */
        static public function en_uvs_quote_settings()
        {
            $uvs_shipment_days = ['all', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            $shipment_days = [];
            foreach ($uvs_shipment_days as $key => $day) {
                get_option('en_uvs_' . $day . '_shipment') == 'yes' ? $shipment_days[] = $key : '';
            }
            $quote_settings = [
                // Cut Off Time & Ship Date Offset
                'delivery_estimate_option' => get_option('en_delivery_estimate_options_uvs'),
                'cutt_off_time' => get_option('en_uvs_cutt_off_time'),
                'fulfilment_offset_days' => get_option('en_uvs_fulfilment_offset_days'),
                'shipment_days' => $shipment_days,
                // Ground transit time restriction
                'transit_days' => get_option('en_uvs_transit_days'),
                'transit_day_option' => get_option('en_uvs_transit_day_options'),
                // Hazardous material settings
                'hazardous_material' => get_option('en_uvs_hazardous_material_settings'),
                'hazardous_ground_fee' => get_option('en_uvs_hazardous_material_settings_ground_fee'),
                'hazardous_international_fee' => get_option('en_uvs_hazardous_material_settings_international_fee'),
                'handling_fee' => get_option('en_uvs_handling_fee'),
                'custom_error_message' => get_option('en_uvs_checkout_error_message'),
                'custom_error_enabled' => get_option('en_uvs_unable_retrieve_shipping'),
                'packaging_method' => get_option('en_uvs_packaging_method_options'),
            ];

            return $quote_settings;
        }

        /**
         * Get quote settings detail
         * @param array $en_settings
         * @return array
         */
        static public function en_uvs_compare_accessorial($en_settings)
        {
            self::$en_uvs_accessorial[] = ['S'];
            return self::$en_uvs_accessorial;
        }

    }

}
