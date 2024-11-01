<?php

/**
 * Handle table.
 */

namespace EnUvs;

/**
 * Generic class to handle warehouse data.
 * Class EnUvsWarehouse
 * @package EnUvsWarehouse
 */
if (!class_exists('EnUvsWarehouse')) {

    class EnUvsWarehouse
    {

        /**
         * Hook for call.
         * EnUvsWarehouse constructor.
         */
        public function __construct()
        {
            add_filter('en_register_activation_hook', array($this, 'create_table'), 10, 1);
            add_filter('en_register_activation_hook', array($this, 'create_uvs_shipping_rules_table'), 10, 1);
        }

        /**
         * Get dropship list
         * @param array $en_location_details
         * @return array|object|null
         */
        public static function get_data($en_location_details = [])
        {
            global $wpdb;
            $en_where_clause_str = '';
            $en_where_clause_param = [];
            if (isset($en_location_details) && !empty($en_location_details)) {
                foreach ($en_location_details as $index => $value) {
                    if (!empty($value) && is_array($value)) {
                        foreach ($value as $key => $location_id) {
                            $en_where_clause_str .= (strlen($en_where_clause_str) > 0) ? ' OR ' : '';
                            $en_where_clause_str .= $index . ' = %s ';
                            $en_where_clause_param[] = $location_id;
                        }
                    } else {
                        $en_where_clause_str .= (strlen($en_where_clause_str) > 0) ? ' AND ' : '';
                        $en_where_clause_str .= $index . ' = %s ';
                        $en_where_clause_param[] = $value;
                    }
                }
                $en_where_clause_str = (strlen($en_where_clause_str) > 0) ? ' WHERE ' . $en_where_clause_str : '';
            }

            $en_table_name = $wpdb->prefix . 'warehouse';
            $sql = $wpdb->prepare("SELECT * FROM $en_table_name $en_where_clause_str", $en_where_clause_param);
            return (array)$wpdb->get_results($sql, ARRAY_A);
        }

        /**
         * Create table for warehouse, dropship
         */
        public function create_table($network_wide = null)
        {
            if (is_multisite() && $network_wide) {
                foreach (get_sites(['fields' => 'ids']) as $blog_id) {
                    switch_to_blog($blog_id);
                    $respnse = $this->create_warehouse_table();
                    restore_current_blog();
                }

            } else {
                $respnse = $this->create_warehouse_table();
            }

            return $respnse;
        }
        
        /**
         * Creates warehouse table and returns success or fail
         */
        public function create_warehouse_table()
        {
            global $wpdb;
            $en_charset_collate = $wpdb->get_charset_collate();
            $en_table_name = $wpdb->prefix . 'warehouse';
            if ($wpdb->query("SHOW TABLES LIKE '" . $en_table_name . "'") === 0) {
                $en_created_table = 'CREATE TABLE ' . $en_table_name . '( 
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    city varchar(20) NOT NULL,
                    state varchar(20) NOT NULL,
                    zip varchar(20) NOT NULL,
                    address varchar(255) NOT NULL,
                    phone_instore varchar(255) NOT NULL,
                    country varchar(20) NOT NULL,
                    location varchar(20) NOT NULL,
                    nickname varchar(20) NOT NULL,
                    enable_store_pickup VARCHAR(20) NULL,    
                    miles_store_pickup VARCHAR(50) NULL,
                    match_postal_store_pickup VARCHAR(255) NULL,
                    checkout_desc_store_pickup VARCHAR(255) NULL,
                    enable_local_delivery VARCHAR(20) NULL,
                    miles_local_delivery VARCHAR(50) NULL,
                    match_postal_local_delivery VARCHAR(255) NULL,
                    checkout_desc_local_delivery VARCHAR(255) NULL,
                    fee_local_delivery VARCHAR(255) NOT NULL,
                    suppress_local_delivery VARCHAR(255) NULL,
                    origin_markup VARCHAR(255),
                    PRIMARY KEY  (id)        
                    )' . $en_charset_collate;

                $wpdb->query($en_created_table);
                // return is_success bit
                return empty($wpdb->last_error);
            }
        }

        /**
         * Create shipping rules database table
        */
        public function create_uvs_shipping_rules_table($network_wide = null)
        {
            if ( is_multisite() && $network_wide ) {

                foreach (get_sites(['fields'=>'ids']) as $blog_id) {
                    switch_to_blog($blog_id);
                    global $wpdb;
                    $en_charset_collate = $wpdb->get_charset_collate();
                    $shipping_rules_table = $wpdb->prefix . "eniture_uvs_shipping_rules";

                    if ($wpdb->query("SHOW TABLES LIKE '" . $shipping_rules_table . "'") === 0) {
                        $query = 'CREATE TABLE ' . $shipping_rules_table . '(
                            id INT(10) NOT NULL AUTO_INCREMENT,
                            name VARCHAR(50) NOT NULL,
                            type VARCHAR(20) NOT NULL,
                            settings TEXT NULL,
                            is_active TINYINT(1) NOT NULL,
                            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                            PRIMARY KEY (id)
                        )' . $en_charset_collate;

                        $wpdb->query($query);
                        $success = empty($wpdb->last_error);

                        return $success;
                    }

                    restore_current_blog();
                }

            } else {
                global $wpdb;
                $en_charset_collate = $wpdb->get_charset_collate();
                $shipping_rules_table = $wpdb->prefix . "eniture_uvs_shipping_rules";

                if ($wpdb->query("SHOW TABLES LIKE '" . $shipping_rules_table . "'") === 0) {
                    $query = 'CREATE TABLE ' . $shipping_rules_table . '(
                        id INT(10) NOT NULL AUTO_INCREMENT,
                        name VARCHAR(50) NOT NULL,
                        type VARCHAR(20) NOT NULL,
                        settings TEXT NULL,
                        is_active TINYINT(1) NOT NULL,
                        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        PRIMARY KEY (id) 
                    )' . $en_charset_collate;

                    $wpdb->query($query);
                    $success = empty($wpdb->last_error);

                    return $success;
                }
            }
        }

    }

}
