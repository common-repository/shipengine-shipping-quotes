<?php

/**
 * Filter rates.
 */

namespace EnUvs;

use EnUvs\EnUvsVersionCompact;

/**
 * Rates according selected rating method.
 * Class EnUvsFilterQuotes
 * @package EnUvsFilterQuotes
 */
if (!class_exists('EnUvsFilterQuotes')) {

    class EnUvsFilterQuotes
    {
        static public $quotes;
        static public $quote_settings;
        static public $total_carriers;

        /**
         * Get random id for quote
         * @return string
         */
        static public function rand_string()
        {
            $alphabets = 'abcdefghijklmnopqrstuvwxyz';
            return substr(str_shuffle(str_repeat($alphabets, mt_rand(1, 10))), 1, 10);
        }

    }

}