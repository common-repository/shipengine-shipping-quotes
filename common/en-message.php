<?php

/**
 * All App Name messages
 */

namespace EnUvs;

/**
 * Messages are relate to errors, warnings, headings
 * Class EnUvsMessage
 * @package EnUvsMessage
 */
if (!class_exists('EnUvsMessage')) {

    class EnUvsMessage
    {

        /**
         * Add all messages
         * EnUvsMessage constructor.
         */
        public function __construct()
        {
            if (!defined('EN_UVS_ROOT_URL')){
                define('EN_UVS_ROOT_URL', esc_url('https://eniture.com'));
            }
            define('EN_UVS_700', "You are currently on the Trial Plan. Your plan will be expire on ");
            define('EN_UVS_701', "You are currently on the Basic Plan. The plan renews on ");
            define('EN_UVS_702', "You are currently on the Standard Plan. The plan renews on ");
            define('EN_UVS_703', "You are currently on the Advanced Plan. The plan renews on ");
            define('EN_UVS_PLANS_URL', EN_UVS_ROOT_URL . '/woocommerce-shipengine-shipping-rates/');
            define('EN_UVS_704', "Your currently plan subscription is inactive <a href='javascript:void(0)' data-action='en_uvs_get_current_plan' onclick='en_uvs_update_plan(this);'>Click here</a> to check the subscription status. If the subscription status remains 
                inactive. Please activate your plan subscription from <a target='_blank' href='" . EN_UVS_PLANS_URL . "'>here</a>");

            define('EN_UVS_715', "<a target='_blank' class='en_uvs_plan_notification' href='" . EN_UVS_PLANS_URL . "'>
                        Basic Plan required
                    </a>");
            define('EN_UVS_705', "<a target='_blank' class='en_uvs_plan_notification' href='" . EN_UVS_PLANS_URL . "'>
                        Standard Plan required
                    </a>");
            define('EN_UVS_706', "<a target='_blank' class='en_uvs_plan_notification' href='" . EN_UVS_PLANS_URL . "'>
                        Advanced Plan required
                    </a>");
            define('EN_UVS_707', "Please verify credentials at connection settings panel.");
            define('EN_UVS_708', "Please enter valid US or Canada zip code.");
            define('EN_UVS_709', "Success! The test resulted in a successful connection.");
            define('EN_UVS_710', "Zip code already exists.");
            define('EN_UVS_711', "Connection settings are missing.");
            define('EN_UVS_712', "Shipping parameters are not correct.");
            define('EN_UVS_713', "Origin address is missing.");
            define('EN_UVS_714', ' <a href="javascript:void(0)" data-action="en_uvs_get_current_plan" onclick="en_uvs_update_plan(this);">Click here</a> to refresh the plan');
        }

    }

}
