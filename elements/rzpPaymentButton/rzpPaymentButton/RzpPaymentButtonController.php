<?php

namespace rzpPaymentButton\rzpPaymentButton;
require_once __DIR__.'/../../../razorpay-sdk/Razorpay.php';
use Razorpay\Api\Api;

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    return;
}

use VisualComposer\Framework\Illuminate\Support\Module;
use VisualComposer\Framework\Container;
use VisualComposer\Helpers\Traits\EventsFilters;
use VisualComposer\Helpers\Traits\WpFiltersActions;

class RzpPaymentButtonController extends Container implements Module
{
    use EventsFilters;
    use WpFiltersActions;

    public function __construct()
    {
        if (!defined('VCV_PAYMENT_BUTTON')) {
            $this->addFilter('vcv:editor:variables vcv:editor:variables/rzpPaymentButton', 'getVariables');
            define('VCV_PAYMENT_BUTTON', true);
        }
    }

    /**
     * @param $variables
     * @return mixed
     */
    protected function getVariables($variables)
    {
        $buttonsData = $this->get_payment_buttons();
        $buttons = [];
        $buttons[] = ['label' => __('Select payment button', 'razorpay'), 'value' => 0];
        if ($buttonsData) {
            foreach ($buttonsData['items'] as $item) {
                $buttons[] = [
                    'label' => $item['title'],
                    'value' => $item['id'],
                ];
            }
        } else {
            $buttons = [
                ['label' => __('No Payment buttons found', 'razorpay'), 'value' => 0],
            ];
        }
        $variables[] = [
            'key' => 'rzpPaymentBtn',
            'value' => $buttons,
        ];
        return $variables;
    }

    /**
     * @return mixed
     */
    public function get_payment_buttons()
    {
        $api = $this->get_razorpay_api_instance();

        try
        {
            return $items = $api->paymentPage->all(['view_type' => 'button', "status" => 'active', 'count'=> 100]);
        }
        catch (\Exception $e)
        {
            $message = $e->getMessage();

            wp_die('<div class="error notice">
                <p>RAZORPAY ERROR: Payment button fetch failed with the following message: '.$message.'</p>
             </div>');
        }
    }

    /**
     * Initialize razorpay api instance
     **/
    public function get_razorpay_api_instance()
    {
        $key = get_option('key_id_field');

        $secret = get_option('key_secret_field');

        if (empty($key) === false && empty($secret) === false) {
            return new Api($key, $secret);
        }

        wp_die('<div class="error notice">
                            <p>RAZORPAY ERROR: Payment button fetch failed.</p>
                         </div>');
    }
}
