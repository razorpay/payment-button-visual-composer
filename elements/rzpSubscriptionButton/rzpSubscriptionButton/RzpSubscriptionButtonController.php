<?php
namespace rzpSubscriptionButton\rzpSubscriptionButton;
require_once __DIR__.'/../../../razorpay-sdk/Razorpay.php';
use Razorpay\Api\Api;

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}
use VisualComposer\Framework\Container;
use VisualComposer\Framework\Illuminate\Support\Module;
use VisualComposer\Helpers\Traits\EventsFilters;
use VisualComposer\Helpers\Traits\WpFiltersActions;

class RzpSubscriptionButtonController extends Container implements Module
{
    use EventsFilters;
    use WpFiltersActions;
    public function __construct()
    {
        if (!defined('VCV_SUBSCRIPTION_BUTTON')) {
            $this->addFilter(
                'vcv:editor:variables vcv:editor:variables/rzpSubscriptionButton',
                'getVariables'
            );
            define('VCV_SUBSCRIPTION_BUTTON', true);
        }
    }
    /**
     * @param $variables
     * @param $payload
     *
     * @return array
     */
    protected function getVariables($variables)
    {
        $buttonData = $this->get_subscription_buttons();
        $subscriptionButtons = [];
        $subscriptionButtons[] = ['label' => __('Select subscription button', 'razorpay'), 'value' => 0];
        if ($buttonData) {
            foreach ($buttonData['items'] as $item) {
                $subscriptionButtons[] = [
                    'label' => $item['title'],
                    'value' => $item['id'],
                ];
            }
        } else {
            $subscriptionButtons = [
                ['label' => __('No Subscription buttons found', 'razorpay'), 'value' => 0],
            ];
        }
        $variables[] = [
            'key' => 'rzpSubscriptionBtn',
            'value' => $subscriptionButtons,
        ];
        return $variables;
    }

    /**
     * @return mixed
     */
    public function get_subscription_buttons()
    {
        $api = $this->get_razorpay_api_instance();

        try
        {
            return $items = $api->paymentPage->all(['view_type' => 'subscription_button', "status" => 'active','count'=> 100]);
        }
        catch (\Exception $e)
        {
            $message = $e->getMessage();

            wp_die('<div class="error notice">
                <p>RAZORPAY ERROR: Subscription button fetch failed with the following message: '.$message.'</p>
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
                            <p>RAZORPAY ERROR: Subscription button fetch failed.</p>
                         </div>');
    }
}