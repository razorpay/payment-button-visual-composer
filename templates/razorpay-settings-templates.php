<?php

class RZP_Payment_Button_Visual_Composer_Templates
{
    /**
     * Generates admin page options using Settings API
    **/
    function razorpaySettings()
    {
		settings_errors();
		
        echo
            '<div class="wrap">
                <h2>Razorpay Payment Button Settings</h2>
                <form action="options.php" method="POST">';

                    settings_fields('razorpay_fields');
                    do_settings_sections('razorpay_sections');
                    submit_button();

        echo
                '</form>
            </div>';
    }

    /**
     * Uses Settings API to create fields
    **/
    function displayOptions()
    {
        add_settings_section('razorpay_fields', 'Edit Settings', array($this, 'displayHeader'), 'razorpay_sections');

        $settings = $this->get_settings();

        foreach ($settings as $settingField => $settingName)
        {
            $displayMethod = $this->get_display_setting_method($settingField);

            add_settings_field(
                $settingField,
                $settingName,
                array(
                    $this,
                    $displayMethod
                ),
                'razorpay_sections',
                'razorpay_fields'
            );

            register_setting('razorpay_fields', $settingField);
        }
    }

    /**
     * Settings page header
    **/
    function displayHeader()
    {
        $header = '<p>Razorpay is an online payment gateway for India with transparent pricing, seamless integration and great support</p>';

        echo $header;
    }

    /**
     * Key ID field of settings page
    **/
    function displayKeyID()
    {
        $default = get_option('key_id_field');

        $keyID = <<<EOT
<input type="text" name="key_id_field" id="key_id" size="35" value="{$default}" /><br>
<label for ="key_id">The key Id and key secret can be generated from "API Keys" section of Razorpay Dashboard. Use test or live for test or live mode.</label>
EOT;

        $allowed_tags = array(
            'br' => array(),
            'input' => array(
                'type' => array(),
                'name' => array(),
                'id' => array(),
                'size' => array(),
                'value' => array(),
            ),
            'label' => array(
                'for' => array()
            ),
        );
        echo wp_kses($keyID, $allowed_tags);
    }

    /**
     * Key secret field of settings page
    **/
    function displayKeySecret()
    {
        $default = get_option('key_secret_field');

        $keySecret = <<<EOT
<input type="text" name="key_secret_field" id="key_secret" size="35" value="{$default}" /><br>
<label for ="key_id">The key Id and key secret can be generated from "API Keys" section of Razorpay Dashboard. Use test or live for test or live mode.</label>
EOT;

        $allowed_tags = array(
            'br' => array(),
            'input' => array(
                'type' => array(),
                'name' => array(),
                'id' => array(),
                'size' => array(),
                'value' => array(),
            ),
            'label' => array(
                'for' => array()
            ),
        );
        echo wp_kses($keySecret, $allowed_tags);
    }

    protected function get_settings()
    {
        $settings = array(
            'key_id_field'         => 'Key_id',
            'key_secret_field'     => 'Key_secret'
        );

        return $settings;
    }

    protected function get_display_setting_method($settingsField)
    {
        $settingsField = ucwords($settingsField);

        $fieldWords = explode('_', $settingsField);

        array_pop($fieldWords);

        return 'display' . implode('', $fieldWords);
    }
}
