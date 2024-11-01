<?php

/**
 * App Name load classes.
 */

namespace EnUvs;

use EnUvs\EnUvsCsvExport;
use EnUvs\EnUvsOrderWidget;
use EnUvs\EnUvsConfig;
use EnUvs\EnUvsLocationAjax;
use EnUvs\EnUvsMessage;
use EnUvs\EnUvsOrderRates;
use EnUvs\EnUvsOrderScript;
use EnUvs\EnUvsPlans;
use EnUvs\EnUvsWarehouse;
use EnUvs\EnUvsTestConnection;
use EnUvsShippingRulesAjaxReq\EnUvsShippingRulesAjaxReq;

/**
 * Load classes.
 * Class EnUvsLoad
 * @package EnUvsLoad
 */
if (!class_exists('EnUvsLoad')) {

    class EnUvsLoad
    {
        /**
         * Load classes of App Name plugin
         */
        static public function Load()
        {
            new EnUvsMessage();
            new EnUvsPlans();
            EnUvsConfig::do_config();
            new \WC_EnUvsShippingRates();
            if (is_admin()) {
                new EnUvsWarehouse();
                new EnUvsTestConnection();
                new EnUvsLocationAjax();
                new EnUvsOrderRates();
                new EnUvsOrderScript();
                !class_exists('EnOrderWidget') ? new EnUvsOrderWidget() : '';
                !class_exists('EnCsvExport') ? new EnUvsCsvExport() : '';
                new EnUvsShippingRulesAjaxReq();
            }
        }
    }
}