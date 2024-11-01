<?php
/**
 * Plugin Name: ShipEngine Shipping Quotes
 * Plugin URI: https://eniture.com/products/
 * Description: Dynamically retrieves your discounted shipping rates and displays the results in the WooCommerce shopping cart.
 * Version: 1.0.7
 * Requires at least: 6.4
 * Author: Eniture Technology
 * Author URI: http://eniture.com/
 * Text Domain: eniture-technology
 * License: GPL version 2 or later - http://www.eniture.com/
 */

namespace EnUvs;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

require_once 'vendor/autoload.php';

define('EN_UVS_MAIN_DIR', __DIR__);
define('EN_UVS_MAIN_FILE', __FILE__);

add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

if (empty(\EnUvs\EnUvsGuard::en_check_prerequisites('ShipEngine Shipping Rates', '5.6', '4.0', '5.7'))) {
    require_once 'en-install.php';
}
