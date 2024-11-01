<?php
/**
 * App Name tabs.
 */

if (!class_exists('EnUvsTab')) {
    /**
     * Tabs show on admin side.
     * Class EnUvsTab
     */
    class EnUvsTab extends WC_Settings_Page
    {
        /**
         * Hook for call.
         */
        public function en_load()
        {
            $this->id = 'uvs';
            add_filter('woocommerce_settings_tabs_array', [$this, 'add_settings_tab'], 50);
            add_action('woocommerce_sections_' . $this->id, [$this, 'output_sections']);
            add_action('woocommerce_settings_' . $this->id, [$this, 'output']);
            add_action('woocommerce_settings_save_' . $this->id, [$this, 'save']);
        }

        /**
         * Setting Tab For Woocommerce
         * @param $settings_tabs
         * @return string
         */
        public function add_settings_tab($settings_tabs)
        {
            $settings_tabs[$this->id] = __('ShipEngine', 'woocommerce-settings-uvs');
            return $settings_tabs;
        }

        /**
         * Setting Sections
         * @return array
         */
        public function get_sections()
        {
            $sections = array(
                '' => __('Connection Settings', 'woocommerce-settings-uvs'),
                'section-2' => __('Quote Settings', 'woocommerce-settings-uvs'),
                'section-3' => __('Warehouses', 'woocommerce-settings-uvs'),
                'shipping-rules' => __('Shipping Rules', 'woocommerce-settings-uvs'),
                'section-4' => __('FreightDesk Online', 'woocommerce-settings-uvs'),
                'section-5' => __('Validate Addresses', 'woocommerce-settings-uvs'),
                'section-6' => __('User Guide', 'woocommerce-settings-uvs'),
            );

            $sections = apply_filters('en_woo_addons_sections', $sections, EN_UVS_PLUGIN_ID);
            return apply_filters('woocommerce_get_sections_' . $this->id, $sections);
        }


        /**
         * Display all pages on wc settings tabs
         * @param $section
         * @return array
         */
        public function get_settings($section = null)
        {
            ob_start();
            switch ($section) {

                case 'section-2' :
                    $settings = \EnUvs\EnUvsQuoteSettings::Load();
                    break;

                case 'section-3':
                    EnLocation::en_load();
                    $settings = [];
                    break;

                case 'section-4' :
                    $this->freightdesk_online_section();
                    $settings = [];
                    break;

                case 'section-5' :
                    $this->validate_addresses_section();
                    $settings = [];
                    break;

                case 'section-6' :
                    \EnUvs\EnUvsUserGuide::en_load();
                    $settings = [];
                    break;
                case 'shipping-rules' :
                    include_once('shipping-rules/shipping-rules-template.php');
                    $settings = [];
                    break;
                default:
                    $settings = \EnUvs\EnUvsConnectionSettings::en_load();
                    break;
            }

            $settings = apply_filters('en_woo_addons_settings', $settings, $section, EN_UVS_PLUGIN_ID);
            $settings = $this->check_addon_availability($settings);
            return apply_filters('woocommerce-settings-uvs', $settings, $section);
        }

        /**
         * RAD addon activated or not
         * @param array type $settings
         * @return array type
         */
        function check_addon_availability($settings)
        {
            if (!function_exists('is_plugin_active')) {
                require_once(EN_UVS_ABSPATH . '/wp-admin/includes/plugin.php');
            }

            if (is_plugin_active('standard-box-sizes/standard-box-sizes.php') || is_plugin_active('standard-box-sizes/en-standard-box-sizes.php')) {
                unset($settings['availability_box_sizing']);
            }

            return $settings;
        }

        /**
         * WooCommerce Settings Tabs
         * @global $current_section
         */
        public function output()
        {
            global $current_section;
            $settings = $this->get_settings($current_section);
            WC_Admin_Settings::output_fields($settings);
        }

        /**
         * Woocommerce Save Settings
         * @global $current_section
         */
        public function save()
        {
            global $current_section;
            $settings = $this->get_settings($current_section);
            if (isset($_POST['en_uvs_cutt_off_time']) && strlen($_POST['en_uvs_cutt_off_time']) > 0) {
                $_POST['en_uvs_cutt_off_time'] = $this->get_time_in_24_hours(sanitize_text_field($_POST['en_uvs_cutt_off_time']));
            }
            WC_Admin_Settings::save_fields($settings);
        }

        /**
         * Change time format.
         * @param $timeStr
         * @return false|string
         */
        public function get_time_in_24_hours($time_str)
        {
            $cutOffTime = explode(' ', $time_str);
            $hours = $cutOffTime[0];
            $separator = $cutOffTime[1];
            $minutes = $cutOffTime[2];
            $meridiem = $cutOffTime[3];
            $cutOffTime = "{$hours}{$separator}{$minutes} $meridiem";
            return date("H:i", strtotime($cutOffTime));
        }
        
        /**
         * FreightDesk Online section
        */
        public function freightdesk_online_section()
        {
            include_once('fdo/freightdesk-online-section.php');
        }

        /**
         * Validate Addresses Section
        */
        public function validate_addresses_section()
        {
            include_once('fdo/validate-addresses-section.php');
        }
    }

    $en_tab = new EnUvsTab();
    return $en_tab->en_load();
}
