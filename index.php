<?php

/**
Plugin Name: Razorpay Payment Button for Visual Composer
Plugin URI: https://github.com/razorpay/payment-button-visual-composer
Description: Razorpay Payment Button for Visual Composer plugin accepts to collect one time and recurring payments on your website.
Version: 1.0.1
Author: Razorpay
Author URI: https://razorpay.com
*/

// don't load directly
if (!defined('ABSPATH'))
{
    die('-1');
}

require_once __DIR__.'/razorpay-sdk/Razorpay.php';
require_once __DIR__.'/includes/rzp-btn-settings.php';
require_once __DIR__.'/includes/rzp-payment-buttons.php';
require_once __DIR__.'/includes/rzp-subscription-buttons.php';
require_once __DIR__.'/includes/rzp-btn-view.php';
require_once __DIR__.'/includes/rzp-btn-action.php';

use Razorpay\Api\Api;
use Razorpay\Api\Errors;

add_action('admin_enqueue_scripts', 'bootstrap_scripts_enqueue_visual_composer', 0);
add_action('admin_post_rzp_btn_visual_composer_action', 'razorpay_payment_button_visual_composer_action', 0);

function bootstrap_scripts_enqueue_visual_composer($hook_suffix)
{
    if ($hook_suffix != 'admin_page_rzp_button_view_visual_composer')
    {
        return;
    }
    wp_register_style('bootstrap-css-visual-composer', plugin_dir_url(__FILE__)  . 'public/css/bootstrap.min.css',
        null, null);
    wp_register_style('button-css-visual-composer', plugin_dir_url(__FILE__)  . 'public/css/button.css',
        null, null);
    wp_enqueue_style('bootstrap-css-visual-composer');
    wp_enqueue_style('button-css-visual-composer');

    wp_enqueue_script('jquery');
}

/**
 * This is the RZP Payment button loader class.
 */
if (!class_exists('RZP_VC_Payment_Button_Loader'))
{

    // Adding constants
    if (!defined('RZP_VC_BASE_NAME'))
    {
        define('RZP_VC_BASE_NAME', plugin_basename(__FILE__));
    }

    if (!defined('RZP_REDIRECT_URL'))
    {
        // admin-post.php is a file that contains methods for us to process HTTP requests
        define('RZP_REDIRECT_URL', esc_url(admin_url('admin-post.php')));
    }

    class RZP_VC_Payment_Button_Loader
    {
        /**
         * Start up
         */
        public function __construct()
        {
            add_action('admin_menu', array($this, 'rzp_add_plugin_page'));

            add_filter('plugin_action_links_' . RZP_VC_BASE_NAME, array($this, 'razorpay_plugin_links'));

            $this->settings = new RZP_Payment_Button_Visual_Composer_Setting();
        }

        /**
         * Creating the menu for plugin after load
         **/

        public function rzp_add_plugin_page()
        {
            /* add pages & menu items */
            add_menu_page(esc_attr__('Razorpay Payment Button', 'textdomain'), esc_html__('Razorpay Buttons for Visual Composer', 'textdomain'),
                'administrator','razorpay_payment_button_visual_composer',array($this, 'rzp_payment_buttons_page'), '', 10);

            add_submenu_page(esc_attr__('razorpay_payment_button_visual_composer', 'textdomain'), esc_html__('Payment Buttons', 'textdomain'),
                'Payment Buttons', 'administrator','razorpay_payment_button_visual_composer', array($this, 'rzp_payment_buttons_page'),0);

            add_submenu_page(esc_attr__('razorpay_payment_button_visual_composer', 'textdomain'), esc_html__('Razorpay Settings', 'textdomain'),
                'Settings', 'administrator','razorpay_visual_composer_settings', array($this, 'razorpay_visual_composer_settings'));

            add_submenu_page(esc_attr__('', 'textdomain'), esc_html__('Razorpay Buttons for Visual Composer', 'textdomain'),
                'Payment Buttons', 'administrator','rzp_button_view_visual_composer', array($this, 'rzp_button_view_visual_composer'));

            add_submenu_page(esc_attr__('razorpay_payment_button_visual_composer', 'textdomain'), esc_html__('Razorpay Subscription Buttons', 'textdomain'),
                'Subscription Buttons', 'administrator','razorpay_subscription_button_visual_composer', array($this, 'rzp_subscription_buttons_page'),1);

            add_submenu_page(esc_attr__('', 'textdomain'), esc_html__('Razorpay Subscription Button', 'textdomain'),
                'Subscription Buttons', 'administrator','rzp_button_view_visual_composer',array($this, 'rzp_button_view_visual_composer'));
        }

        /**
         * Initialize razorpay api instance
         **/
        public function get_razorpay_api_instance()
        {
            $key = get_option('key_id_field');

            $secret = get_option('key_secret_field');

            if (empty($key) === false and empty($secret) === false)
            {
                return new Api($key, $secret);
            }

            wp_die('<div class="error notice">
                        <p>RAZORPAY ERROR: Please set Razorpay Key Id and Secret in plugin settings.</p>
                     </div>');
        }

        /**
         * Creating the settings link from the plug ins page
         **/
        function razorpay_plugin_links($links)
        {
            $pluginLinks = array(
                'settings' => '<a href="'. esc_url(admin_url('admin.php?page=razorpay_visual_composer_settings')) .'">Settings</a>',
                'Docs' => '<a href="https://razorpay.com/docs/payments/payment-button/supported-platforms/wordpress/visual-composer/">Docs</a>',
                'support'  => '<a href="https://razorpay.com/contact/">Support</a>'
            );

            $links = array_merge($links, $pluginLinks);

            return $links;
        }

        /**
         * Razorpay Payment Button Page
         */
        public function rzp_payment_buttons_page()
        {
            $rzp_payment_buttons = new RZP_Payment_Buttons_Visual_Composer();

            $rzp_payment_buttons->rzp_buttons();
        }

        /**
         * Razorpay Subscription Button Page
         */
        public function rzp_subscription_buttons_page()
        {
            $rzp_subscription_buttons = new RZP_Subscription_Buttons_Visual_Composer();

            $rzp_subscription_buttons->subscription_buttons();
        }

        /**
         * Razorpay Setting Page
         */
        public function razorpay_visual_composer_settings()
        {
            $this->settings->razorpaySettings();
        }

        /**
         * Razorpay button detail page
         */
        public function rzp_button_view_visual_composer()
        {
            $new_button = new RZP_View_Button_Visual_Composer();

            $new_button->razorpay_view_button();
        }
    }
}
$RZP_Payment_Button_Visual_Composer_Loader = new RZP_VC_Payment_Button_Loader();

function razorpay_payment_button_visual_composer_action()
{
    $btn_action = new RZP_Button_Action_Visual_Composer();

    $btn_action->process();
}

add_action(
/**
 * @param $api \VisualComposer\Modules\Api\Factory
 */
    'vcv:api',
    function ($api)
    {
        $elementsToRegister = [
            'rzpPaymentButton',
            'rzpSubscriptionButton'
        ];
        $pluginBaseUrl = rtrim(plugins_url(basename(__DIR__)), '\\/');
        /** @var \VisualComposer\Modules\Elements\ApiController $elementsApi */
        $elementsApi = $api->elements;
        foreach ($elementsToRegister as $tag)
        {
            $manifestPath = __DIR__ . '/elements/' . $tag . '/manifest.json';
            $elementBaseUrl = $pluginBaseUrl . '/elements/' . $tag;
            $elementsApi->add($manifestPath, $elementBaseUrl);
        }
    }
);
