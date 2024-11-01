<?php

/**
 * Quote settings detail array.
 */

namespace EnUvs;

if (!class_exists('EnUvsQuoteSettings')) {

    class EnUvsQuoteSettings {

        /**
         * Domestic services
         * @return array
         */
        static public function domestic_services() {
            return
                    [
                        [
                            'id' => 'uvs_ground',
                            'request' => 'ups_ground',
                            'name' => 'UPS Ground',
                        ],
                        [
                            'id' => 'uvs_2nd_day_air',
                            'request' => 'ups_2nd_day_air',
                            'name' => 'UPS 2nd Day Air',
                        ],
                        [
                            'id' => 'uvs_2nd_day_air_am',
                            'request' => 'ups_2nd_day_air_am',
                            'name' => 'UPS 2nd Day Air A.M',
                        ],
                        [
                            'id' => 'uvs_next_day_air_saver',
                            'request' => 'ups_next_day_air_saver',
                            'name' => 'UPS Next Day Air Saver',
                        ],
                        [
                            'id' => 'uvs_next_day_air',
                            'request' => 'ups_next_day_air',
                            'name' => 'UPS Next Day Air',
                        ],
                        [
                            'id' => 'uvs_next_day_air_early',
                            'request' => 'ups_next_day_air_early_am',
                            'name' => 'UPS Next Day Air Early',
                        ],
                        [
                            'id' => 'uvs_3day_select',
                            'request' => 'ups_3_day_select',
                            'name' => 'UPS 3 Day Select',
                        ]
            ];
        }

        /**
         * International services
         * @return array
         */
        static public function international_services() {
            return
                    [
                        [
                            'id' => 'uvs_int_standard',
                            'request' => 'ups_standard_international',
                            'name' => 'UPS Standard',
                        ],
                        [
                            'id' => 'uvs_int_expedited',
                            'request' => 'ups_worldwide_expedited',
                            'name' => 'UPS  Expedited | UPS Worldwide Expedited',
                        ],
                        [
                            'id' => 'uvs_int_saver',
                            'request' => 'ups_worldwide_saver',
                            'name' => 'UPS Express Saver | UPS Worldwide Saver',
                        ],
                        [
                            'id' => 'uvs_int_express',
                            'request' => 'ups_worldwide_express',
                            'name' => 'UPS Express | UPS Worldwide Express',
                        ],
                        [
                            'id' => 'uvs_int_express_plus',
                            'request' => 'ups_worldwide_express_plus',
                            'name' => 'UPS Express Plus | UPS Worldwide Express Plus',
                        ]
            ];
        }

        /**
         * Quote Settings Services
         * @return array
         */
        static public function services() {
            $alphabets = 'abcdefghijklmnopqrstuvwxyz';
            $domestic = self::domestic_services();
            $international = self::international_services();
            $services = [];
            foreach ($domestic as $key => $service) {

                // Domestic checkbox
                $id = $name = '';
                extract($service);
                $indexing = 'en_uvs_checkbox_' . $id;
                $services[$indexing] = [
                    'name' => __($name, 'woocommerce-settings-uvs'),
                    'type' => 'checkbox',
                    'id' => $id,
                    'class' => 'en_uvs_domestic_service en_uvs_service_checkbox',
                ];

                // International checkbox
                $international_service = (isset($international[$key])) ? $international[$key] : [];
                if (!empty($international_service)) {
                    $international_id = $international_service['id'];
                    $international_name = $international_service['name'];
                    $indexing = 'en_uvs_checkbox_' . $international_id;
                    $services[$indexing] = [
                        'name' => __($international_name, 'woocommerce-settings-uvs'),
                        'type' => 'checkbox',
                        'id' => $international_id,
                        'class' => 'en_uvs_international_service en_uvs_service_checkbox',
                    ];
                } else {
                    $rand_string = substr(str_shuffle(str_repeat($alphabets, mt_rand(1, 10))), 1, 5);
                    $services[$rand_string] = [
                        'name' => __('', 'woocommerce-settings-uvs'),
                        'type' => 'checkbox',
                        'id' => $rand_string,
                        'class' => 'en_uvs_international_service hidden en_uvs_service_hide',
                    ];
                }

                // Domestic markup
                $indexing = 'en_uvs_markup_' . $id;
                $services[$indexing] = [
                    'name' => __('', 'woocommerce-settings-uvs'),
                    'type' => 'text',
                    'id' => $indexing,
                    'class' => 'en_uvs_domestic_service en_uvs_service_markup',
                    'desc' => __('Markup (e.g. Currency: 1.00 or Percentage: 5.0%)', 'woocommerce-settings-uvs'),
                    'placeholder' => 'Markup',
                ];

                // International markup
                if (!empty($international_service)) {
                    $indexing = 'en_uvs_markup_' . $international_id;
                    $services[$indexing] = [
                        'name' => __('', 'woocommerce-settings-uvs'),
                        'type' => 'text',
                        'id' => $indexing,
                        'class' => 'en_uvs_international_service en_uvs_service_markup',
                        'desc' => __('Markup (e.g. Currency: 1.00 or Percentage: 5.0%)', 'woocommerce-settings-uvs'),
                        'placeholder' => 'Markup',
                    ];
                } else {
                    $rand_string = substr(str_shuffle(str_repeat($alphabets, mt_rand(1, 10))), 1, 10);
                    $services[$rand_string] = [
                        'name' => __('', 'woocommerce-settings-uvs'),
                        'type' => 'text',
                        'id' => $rand_string,
                        'class' => 'en_uvs_service_hide en_uvs_international_service en_uvs_service_markup hidden',
                    ];
                }
            }

            $services['en_uvs_shipping_methods_do_not_sort_by_price'] = [
                'name' => __("Don't sort shipping methods by price", 'woocommerce-settings-uvs'),
                'type' => 'checkbox',
                'id' => 'en_uvs_shipping_methods_do_not_sort_by_price',
                'desc' => 'By default, the plugin will sort all shipping methods by price in ascending order.',
            ];

            // Package rating method when Standard Box Sizes isn't in use
            $services['en_uvs_packaging_method_label'] = [
                'name' => __("Package rating method when Standard Box Sizes isn't in use", 'woocommerce-settings-uvs'),
                'type' => 'text',
                'class' => 'hidden',
                'id' => 'en_uvs_packaging_method_label'
            ];

            $services['en_uvs_packaging_method_options'] = [
                'name' => '',
                'type' => 'radio',
                'id' => 'en_uvs_packaging_method_options',
                'default' => 'ship_alone',
                'options' => [
                    'ship_alone' => __('Quote each item as shipping as its own package', 'woocommerce-settings-uvs'),
                    'ship_combine_and_alone' => __('Combine the weight of all items without dimensions and quote them as one package while quoting each item with dimensions as shipping as its own package', 'woocommerce-settings-uvs'),
                    'ship_one_package_70' => __('Quote shipping as if all items ship as one package up to 70 LB each', 'woocommerce-settings-uvs'),
                    'ship_one_package_150' => __('Quote shipping as if all items ship as one package up to 150 LB each', 'woocommerce-settings-uvs'),
                ]
            ];

            return $services;
        }

        /**
         * Hazardous material settings
         * @return array
         */
        static public function hazardous_material() {
            $option = $message = '';
            if (isset($_REQUEST['tab'])) {
                $feature_option = apply_filters(sanitize_text_field($_REQUEST['tab']) . "_plans_suscription_and_features", 'hazardous_material');
                if (is_array($feature_option)) {
                    $option = 'en_uvs_disabled';
                    $message = apply_filters(sanitize_text_field($_REQUEST['tab']) . "_plans_notification_link", $feature_option);
                }
            }

            return [
                'en_uvs_hazardous_material_settings_title' => [
                    'name' => __('Hazardous material settings', 'woocommerce-settings-uvs'),
                    'type' => 'text',
                    'class' => 'hidden',
                    'desc' => $message,
                    'id' => 'en_uvs_hazardous_material_settings_title'
                ],
                'en_uvs_hazardous_material_settings' => [
                    'name' => __('', 'woocommerce-settings-uvs'),
                    'type' => 'checkbox',
                    'class' => $option,
                    'desc' => 'Only quote ground service for hazardous materials shipments.',
                    'id' => 'en_uvs_hazardous_material_settings'
                ],
                'en_uvs_hazardous_material_settings_ground_fee' => [
                    'name' => __('Ground Hazardous Material Fee ', 'woocommerce-settings-uvs'),
                    'type' => 'text',
                    'class' => $option,
                    'desc' => 'Enter an amount, e.g 20. or Leave blank to disable.',
                    'id' => 'en_uvs_hazardous_material_settings_ground_fee'
                ],
                'en_uvs_hazardous_material_settings_international_fee' => [
                    'name' => __('Air Hazardous Material Fee ', 'woocommerce-settings-uvs'),
                    'type' => 'text',
                    'class' => $option,
                    'desc' => 'Enter an amount, e.g 20. or Leave blank to disable.',
                    'id' => 'en_uvs_hazardous_material_settings_international_fee'
                ],
            ];
        }

        /**
         * Delivery estimate options
         * @return array
         */
        static public function delivery_estimate_option() {
            $option = $message = '';
            if (isset($_REQUEST['tab'])) {
                $feature_option = apply_filters(sanitize_text_field($_REQUEST['tab']) . "_plans_suscription_and_features", 'delivery_estimate_option');
                if (is_array($feature_option)) {
                    $option = 'en_uvs_disabled';
                    $message = apply_filters(sanitize_text_field($_REQUEST['tab']) . "_plans_notification_link", $feature_option);
                }
            }

            return [
                'delivery_estimate_options' => [
                    'name' => __('Delivery Estimate Options', 'woocommerce-settings-uvs'),
                    'type' => 'text',
                    'class' => 'hidden',
                    'desc' => $message,
                    'id' => 'delivery_estimate_options'
                ],
                'en_delivery_estimate_options_uvs' => [
                    'name' => __('', 'woocommerce-settings-uvs'),
                    'type' => 'radio',
                    'class' => $option,
                    'default' => "dont_show_estimates",
                    'options' => [
                        'dont_show_estimates' => __("Don't display delivery estimates.", 'woocommerce-settings-uvs'),
                        'delivery_days' => __('Display estimated number of days until delivery.', 'woocommerce-settings-uvs'),
                        'delivery_date' => __('Display estimated delivery date.', 'woocommerce-settings-uvs'),
                    ],
                    'id' => 'en_delivery_estimate_options_uvs'
                ],
            ];
        }

        /**
         * Transit days
         * @return array
         */
        static public function transit_days() {
            $option = $message = '';
            if (isset($_REQUEST['tab'])) {
                $feature_option = apply_filters(sanitize_text_field($_REQUEST['tab']) . "_plans_suscription_and_features", 'transit_days');
                if (is_array($feature_option)) {
                    $option = 'en_uvs_disabled';
                    $message = apply_filters(sanitize_text_field($_REQUEST['tab']) . "_plans_notification_link", $feature_option);
                }
            }

            return [
                'ground_transit' => [
                    'name' => __('Ground transit time restriction', 'woocommerce-settings-uvs'),
                    'type' => 'text',
                    'class' => 'hidden',
                    'desc' => $message,
                    'id' => 'ground_transit'
                ],
                'en_uvs_transit_days' => [
                    'name' => __('Enter the number of transit days to restrict ground service to. Leave blank to disable this feature.', 'woocommerce-settings-uvs'),
                    'type' => 'text',
                    'class' => $option,
                    'id' => 'en_uvs_transit_days'
                ],
                'en_uvs_transit_day_options' => [
                    'name' => __('', 'woocommerce-settings-uvs'),
                    'type' => 'radio',
                    'class' => $option,
                    'options' => [
                        'totalTransitTimeInDays' => __('Retrict by the carrier\'s in transit days metric.', 'woocommerce-settings-uvs'),
                        'CalenderDaysInTransit' => __('Restrict by the calendar days in transit.', 'woocommerce-settings-uvs'),
                    ],
                    'id' => 'en_uvs_transit_day_options'
                ],
            ];
        }

        /**
         * Cutt off time
         * @return array
         */
        static public function cutt_off_time() {
            $option = $message = '';
            if (isset($_REQUEST['tab'])) {
                $feature_option = apply_filters(sanitize_text_field($_REQUEST['tab']) . "_plans_suscription_and_features", 'cutt_off_time');
                if (is_array($feature_option)) {
                    $option = 'en_uvs_disabled';
                    $message = apply_filters(sanitize_text_field($_REQUEST['tab']) . "_plans_notification_link", $feature_option);
                }
            }

            return [
                'cutt_off_time_and_ship_date_offset' => [
                    'name' => __('Cut Off Time & Ship Date Offset', 'woocommerce-settings-uvs'),
                    'type' => 'text',
                    'class' => 'hidden',
                    'desc' => $message,
                    'id' => 'cutt_off_time_and_ship_date_offset'
                ],
                'en_uvs_cutt_off_time' => [
                    'name' => __('Order Cut Off Time', 'woocommerce-settings-uvs'),
                    'type' => 'text',
                    'class' => $option,
                    'placeholder' => '--:-- --',
                    'desc' => 'Enter the cut off time (e.g. 2.00) for the orders. Orders placed after this time will be quoted as shipping the next business day.',
                    'id' => 'en_uvs_cutt_off_time'
                ],
                'en_uvs_fulfilment_offset_days' => [
                    'name' => __('Fulfilment Offset Days', 'woocommerce-settings-uvs'),
                    'type' => 'text',
                    'class' => $option,
                    'desc' => 'The number of days the ship date needs to be moved to allow the processing of the order.',
                    'id' => 'en_uvs_fulfilment_offset_days'
                ],
                'en_uvs_all_shipment' => [
                    'name' => __("What days do you ship orders?", 'woocommerce-settings-uvs'),
                    'type' => 'checkbox',
                    'desc' => 'Select All',
                    'id' => 'en_uvs_all_shipment',
                    'class' => 'en_uvs_all_shipment ' . $option,
                ],
                'en_uvs_monday_shipment' => [
                    'name' => __("", 'woocommerce-settings-uvs'),
                    'type' => 'checkbox',
                    'desc' => 'Monday',
                    'id' => 'en_uvs_monday_shipment',
                    'class' => 'en_uvs_shipment_day ' . $option,
                ],
                'en_uvs_tuesday_shipment' => [
                    'name' => __("", 'woocommerce-settings-uvs'),
                    'type' => 'checkbox',
                    'desc' => 'Tuesday',
                    'id' => 'en_uvs_tuesday_shipment',
                    'class' => 'en_uvs_shipment_day ' . $option,
                ],
                'en_uvs_wednesday_shipment' => [
                    'name' => __("", 'woocommerce-settings-uvs'),
                    'type' => 'checkbox',
                    'desc' => 'Wednesday',
                    'id' => 'en_uvs_wednesday_shipment',
                    'class' => 'en_uvs_shipment_day ' . $option,
                ],
                'en_uvs_thursday_shipment' => [
                    'name' => __("", 'woocommerce-settings-uvs'),
                    'type' => 'checkbox',
                    'desc' => 'Thursday',
                    'id' => 'en_uvs_thursday_shipment',
                    'class' => 'en_uvs_shipment_day ' . $option,
                ],
                'en_uvs_friday_shipment' => [
                    'name' => __("", 'woocommerce-settings-uvs'),
                    'type' => 'checkbox',
                    'desc' => 'Friday',
                    'id' => 'en_uvs_friday_shipment',
                    'class' => 'en_uvs_shipment_day ' . $option,
                ],
            ];
        }

        static public function rad_and_sbs_options(){

            $services = [];

            $services['en_uvs_resi_section'] = [
                'name' => __('', 'woocommerce-settings-uvs'),
                'type' => 'title',
                'class' => 'hidden',
                'id' => 'en_uvs_resi_section'
            ];

            /**
             * ==================================================================
             * Standard box sizes notification
             * ==================================================================
             */
            $services['availability_box_sizing'] = [
                'name' => __('Use my standard box sizes ', 'woocommerce-settings-uvs'),
                'type' => 'text',
                'class' => 'hidden',
                'desc' => "Click <a target='_blank' href='" . EN_UVS_SBS_URL . "'>here</a> to add the Standard Box Sizes module. (<a target='_blank' href='https://eniture.com/woocommerce-standard-box-sizes/#documentation'>Learn more</a>)",
                'id' => 'en_quote_settings_availability_sbs_uvs'
            ];

            return $services;
        }

        static public function Load() {
            $services = self::services();
            $delivery_estimate = self::delivery_estimate_option();
            $transit_days = self::transit_days();
            $hazmat = self::hazardous_material();
            $cutt_off_time = self::cutt_off_time();
            $rad_sbs = self::rad_and_sbs_options();

            $settings_start = [
                'en_quote_settings_start_uvs' => [
                    'name' => __('', 'woocommerce-settings-uvs'),
                    'type' => 'title',
                    'id' => 'en_quote_settings_uvs',
                ],
                'en_uvs_domestic_heading' => [
                    'name' => __('Domestic Services', 'woocommerce-settings-uvs'),
                    'type' => 'checkbox',
                    'id' => 'en_uvs_domestic_heading',
                    'class' => 'en_uvs_domestic_service en_uvs_service_heading',
                ],
                'en_uvs_international_heading' => [
                    'name' => __('International Services', 'woocommerce-settings-uvs'),
                    'type' => 'checkbox',
                    'id' => 'en_uvs_international_heading',
                    'class' => 'en_uvs_international_service en_uvs_service_heading',
                ],
                'en_uvs_domestic_selective' => [
                    'name' => __('Select All', 'woocommerce-settings-uvs'),
                    'type' => 'checkbox',
                    'id' => 'en_uvs_domestic_selective',
                    'class' => 'en_uvs_domestic_service en_uvs_service_all_select',
                ],
                'en_uvs_international_selective' => [
                    'name' => __('Select All', 'woocommerce-settings-uvs'),
                    'type' => 'checkbox',
                    'id' => 'en_uvs_international_selective',
                    'class' => 'en_uvs_international_service en_uvs_service_all_select',
                ],
            ];

            $settings_body = [

                'en_uvs_handling_fee' => [
                    'name' => __('Handling Fee / Markup ', 'woocommerce-settings-uvs'),
                    'type' => 'text',
                    'desc' => 'Amount excluding tax. Enter an amount, e.g 3.75, or a percentage, e.g, 5%. Leave blank to disable.',
                    'id' => 'en_uvs_handling_fee'
                ],
                'en_uvs_allow_other_plugin_quotes' => [
                    'name' => __('Show WooCommerce Shipping Options ', 'woocommerce-settings-uvs'),
                    'type' => 'select',
                    'default' => 'yes',
                    'desc' => __('Enabled options on WooCommerce Shipping page are included in quote results.', 'woocommerce-settings-uvs'),
                    'id' => 'en_uvs_allow_other_plugin_quotes',
                    'options' => [
                        'yes' => __('YES', 'YES'),
                        'no' => __('NO', 'NO'),
                    ]
                ],
                /**
                 * ==================================================================
                 * When plugin fail return to rate
                 * ==================================================================
                 */
                'en_quote_settings_clear_both_uvs' => [
                    'title' => __('', 'woocommerce'),
                    'name' => __('', 'woocommerce-settings-uvs'),
                    'desc' => '',
                    'id' => 'en_quote_settings_clear_both_uvs',
                    'css' => '',
                    'type' => 'title',
                ],
                'en_quote_settings_unable_retrieve_shipping_uvs' => [
                    'name' => __('Checkout options if the plugin fails to return a rate ', 'woocommerce-settings-uvs'),
                    'type' => 'title',
                    'desc' => '<span> When the plugin is unable to retrieve shipping quotes and no other shipping options are provided by an alternative source: </span>',
                    'id' => 'en_quote_settings_unable_retrieve_shipping_uvs',
                ],
                'en_uvs_unable_retrieve_shipping' => [
                    'name' => __('', 'woocommerce-settings-uvs'),
                    'type' => 'radio',
                    'id' => 'en_uvs_unable_retrieve_shipping',
                    'default' => 'allow',
                    'options' => [
                        'allow' => __('Allow user to continue to check out and display this message', 'woocommerce-settings-uvs'),
                        'prevent' => __('Prevent user from checking out and display this message', 'woocommerce-settings-uvs'),
                    ]
                ],
                'en_uvs_checkout_error_message' => [
                    'name' => __('', 'woocommerce-settings-uvs'),
                    'type' => 'textarea',
                    'desc' => 'Enter a maximum of 250 characters.',
                    'id' => 'en_uvs_checkout_error_message'
                ],
                'en_quote_settings_end_uvs' => [
                    'type' => 'sectionend',
                    'id' => 'en_quote_settings_end_uvs'
                ],
            ];

            $settings = $settings_start + $services + $delivery_estimate + $cutt_off_time + $transit_days + $hazmat + $rad_sbs + $settings_body;

            return $settings;
        }

    }

}
