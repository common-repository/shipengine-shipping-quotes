<?php
/**
 * User guide page.
 */

namespace EnUvs;

/**
 * User guide add detail.
 * Class EnUvsUserGuide
 * @package EnUvsUserGuide
 */
if (!class_exists('EnUvsUserGuide')) {

    class EnUvsUserGuide {

        /**
         * User Guide template.
         */
        static public function en_load() {
            ?>
            <div class="en_user_guide">
                <p>
                    <?php _e("The User Guide for this application is maintained on the publisher's website. To view it click", 'eniture-technology'); ?>

                    <a href="<?php echo esc_url(EN_UVS_DOCUMENTATION_URL); ?>" target="_blank">
                        <?php _e('here', 'eniture-technology'); ?>
                    </a>
                    <?php _e('or paste the following link into your browser.', 'eniture-technology'); ?>
                </p>
                <?php
                echo esc_url(EN_UVS_DOCUMENTATION_URL);
            }

        }

    }