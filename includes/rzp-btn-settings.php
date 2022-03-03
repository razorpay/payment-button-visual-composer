<?php
require_once __DIR__.'/../templates/razorpay-settings-templates.php';

class RZP_Payment_Button_Visual_Composer_Setting
{
    public function __construct()
    {
        // Initializes display options when admin page is initialized
        add_action('admin_init', array($this, 'displayOptions'));

        // initializing our object with all the setting variables
        $this->keyID = get_option('key_id_field');
        $this->keySecret = get_option('key_secret_field');

        $this->template = new RZP_Payment_Button_Visual_Composer_Templates;
    }

    /**
     * Generates admin page options using Settings API
    **/
    function razorpaySettings()
    {
        $this->template->razorpaySettings();
    }

	/**
     * Uses Settings API to create fields
    **/
    function displayOptions()
    {
        $this->template->displayOptions();
    }

    /**
     * Settings page header
    **/        
    function displayHeader()
    {
        $this->template->displayHeader();
    }

    /**
     * Key ID field of settings page
    **/
    function display_key_id()
    {
        $this->template->displayKeyID();
    }

    /**
     * Key secret field of settings page
    **/
    function displayKeySecret()
    {
        $this->template->displayKeySecret();
    }
}
