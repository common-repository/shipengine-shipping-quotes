<?php

/**
 * Shipping quotes event handler.
 * Class WC_EnUvsShippingRates
 */
if (!class_exists('WC_EnUvsShippingRates')) {

    class WC_EnUvsShippingRates
    {

        /**
         * Hook for call.
         * EnUvsShippingRates constructor.
         */
        public function __construct()
        {
            /**
             * Load class for shipping rates
             */
            add_action('woocommerce_shipping_init', 'en_uvs_shipping_rates');
        }

        /**
         * Hook function for call from other eniture plugins.
         */
        public function calculate_shipping($package = [])
        {
            $shipping_rates = new EnUvsShippingRates();
            return $shipping_rates->calculate_shipping($package);
        }

    }

}

/**
 * Hook function for call.
 */
if (!function_exists('en_uvs_shipping_rates')) {

    function en_uvs_shipping_rates()
    {

        /**
         * Add class for shipping rates
         */
        class EnUvsShippingRates extends WC_Shipping_Method
        {

            public $en_package = [];
            public $small_package = [];
            public $ltl_package = [];
            // FDO
            public $en_fdo_meta_data = [];

            /**
             * Hook for call
             * EnUvsShippingRates constructor.
             * @param int $instance_id
             */
            public function __construct($instance_id = 0)
            {
                $this->id = EN_UVS_PLUGIN_ID;
                $this->instance_id = absint($instance_id);
                $this->method_title = __('ShipEngine Shipping Rates');
                $this->method_description = __('Shipping rates available via the ShipEngine API.');
                $this->supports = array(
                    'shipping-zones',
                    'instance-settings',
                    'instance-settings-modal',
                );
                $this->enabled = "yes";
                $this->title = 'ShipEngine Shipping Rates';
                $this->init();
            }

            /**
             * Let's start init function
             */
            public function init()
            {
                $this->init_form_fields();
                $this->init_settings();
                add_action('woocommerce_update_options_shipping_' . $this->id, [$this, 'process_admin_options']);
                add_filter('woocommerce_package_rates', array($this, 'en_sort_woocommerce_available_shipping_methods'), 10, 2);
            }

            /**
             * Enable woocommerce shipping for Transportation insight
             */
            public function init_form_fields()
            {
                $this->instance_form_fields = [
                    'enabled' => [
                        'title' => __('Enable / Disable', 'uvs'),
                        'type' => 'checkbox',
                        'label' => __('Enable This Shipping Service', 'uvs'),
                        'default' => 'no',
                        'id' => 'en_uvs_enable_disable_shipping'
                    ]
                ];
            }

            /**
             * Calculate shipping rates woocommerce
             * @param array $package
             * @return array|void
             */
            public function calculate_shipping($package = [])
            {
                $en_rates = [];

                // Eniture Debug Mood
                do_action("eniture_debug_mood", EN_UVS_NAME . " Plan ", EN_UVS_PLAN);
                do_action("eniture_debug_mood", EN_UVS_NAME . " Plan Message ", EN_UVS_PLAN_MESSAGE);

                // Eniture Execution Time
                $en_calculate_shipping_start = microtime(true);

                $en_package = apply_filters('en_shipengine_package_converter', []);
                if (empty($en_package)) {
                    $this->en_package = $en_package = \EnUvs\EnUvsPackage::en_package_converter($package);
                    $shipping_rules_applied = \EnUvs\EnUvsPackage::apply_shipping_rules($en_package);
                    if($shipping_rules_applied || empty($en_package)){
                        return [];
                    }
                    add_filter('en_shipengine_package_converter', [$this, 'en_recently_package_converter'], 10, 1);

                    // Eniture Debug Mood
                    do_action("eniture_debug_mood", "Eniture Packages", $en_package);
                }

                $en_package = $this->en_filterd_spq_shipment($en_package);

                $reasons = apply_filters('en_uvs_reason_quotes_not_returned', []);

                $request_from_freight = (isset($package['itemType']) && !empty($package['itemType'])) ? $package['itemType'] : "";
                if (!empty($this->ltl_package) && $request_from_freight != 'ltl') {
                    return [];
                }

                if (!empty($this->small_package) && empty($reasons)) {

                    // Eniture Debug Mood
                    do_action("eniture_debug_mood", EN_UVS_NAME . " Package ", $en_package);

                    add_filter('en_eniture_shipment', [$this, 'en_eniture_shipment']);

                    $en_package = array_merge(json_decode(EN_UVS_GET_CONNECTION_SETTINGS, true), $en_package, json_decode(EN_UVS_GET_QUOTE_SETTINGS, true));
                    
                    $response = \EnUvs\EnUvsCurl::en_uvs_sent_http_request(EN_UVS_HITTING_API_URL, $en_package, 'POST', 'Quotes');
                    
                    $en_rates = \EnUvs\EnUvsResponse::en_rates(json_decode($response, true), $en_package);

                    // Eniture Debug Mood
                    do_action("eniture_debug_mood", EN_UVS_NAME . " Rates ", $en_rates);

                    // Images for FDO
                    $image_urls = apply_filters('en_fdo_image_urls_merge', []);

                    foreach ($en_rates as $accessorial => $rate) {
                        if (isset($rate['label_sufex']) && !empty($rate['label_sufex'])) {
                            // Order widget detail set
                            if (isset($rate['min_prices'], $rate['en_fdo_meta_data'])) {
                                // FDO
                                $en_fdo_meta_data = $rate['en_fdo_meta_data'];
                                $rate['meta_data']['en_fdo_meta_data'] = wp_json_encode(['data' => $en_fdo_meta_data, 'shipment' => 'multiple']);
                                $rate['meta_data']['minPrices'] = $rate['min_prices'];
                                $rate['meta_data']['min_prices'] = wp_json_encode($rate['min_prices']);
                                unset($rate['min_prices']);
                            } else {
                                // FDO
                                $en_fdo_meta_data = (isset($rate['meta_data']['en_fdo_meta_data'])) ? [$rate['meta_data']['en_fdo_meta_data']] : [];
                                $rate['meta_data']['en_fdo_meta_data'] = wp_json_encode(['data' => $en_fdo_meta_data, 'shipment' => 'single']);
                            }

                            // Images for FDO
                            $rate['meta_data']['en_fdo_image_urls'] = wp_json_encode($image_urls);
                        }

                        $rate_id = (isset($rate['id'])) ? $rate['id'] : '';
                        if (isset($rate['cost']) && $rate['cost'] > 0 || $rate_id == 'local-delivery' || $rate_id == 'in-store-pick-up') {
                            $rate['id'] = !empty($rate['id']) && is_string($rate['id']) ? 'EnUvsShippingRates:' . $rate['id'] : $rate['id'];
                            $this->add_rate($rate);
                            $en_rates[$accessorial] = $rate;
                        }
                    }
                }

                // Eniture Execution Time
                $en_calculate_shipping_end = microtime(true) - $en_calculate_shipping_start;
                do_action("eniture_debug_mood", EN_UVS_NAME . " Total Execution Time ", $en_calculate_shipping_end);

                return $en_rates;
            }

            /**
             * final rates sorting
             * @param array type $rates
             * @param array type $package
             * @return array type
             */
            function en_sort_woocommerce_available_shipping_methods($en_rates, $package)
            {
                // If there are no rates don't do anything
                if (!$en_rates) {
                    return [];
                }

                // Check the option to sort shipping methods by price on quote settings
                if (get_option('en_uvs_shipping_methods_do_not_sort_by_price') != 'yes') {
                    $prices = [];
                    foreach ($en_rates as $rate) {
                        $prices[] = $rate->cost;
                    }
                    array_multisort($prices, $en_rates);
                }
                return $en_rates;
            }

            /**
             * List down both ltl or small packages
             * @param array $en_package
             * @return mixed
             */
            public function en_filterd_spq_shipment($en_package)
            {
                if (isset($en_package['shipment_type']) && is_array($en_package['shipment_type'])) {
                    foreach ($en_package['shipment_type'] as $origin_zip => $shipment) {
                        if (isset($shipment['SMALL']) && !isset($shipment['LTL'])) {

                        } elseif (isset($shipment['LTL'])) {
                            $this->ltl_package[$origin_zip] = EN_UVS_DECLARED_TRUE;
                            unset($en_package['commdityDetails'][$origin_zip]);
                        }
                    }
                }

                return $this->small_package = $en_package;
            }

            /**
             * Get last used array of packages
             * @param array $package
             * @return array
             */
            public function en_recently_package_converter($package)
            {
                return array_merge($package, $this->en_package);
            }

            /**
             * Set flag eniture shipment exist or not
             * @param array $eniture_shipment
             * @return array
             */
            public function en_eniture_shipment($eniture_shipment)
            {
                return array_merge($eniture_shipment, ['SPQ' => $this->small_package]);
            }

        }

    }

}
