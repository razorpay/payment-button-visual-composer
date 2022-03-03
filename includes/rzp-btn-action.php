<?php
use Razorpay\Api\Api;
use Razorpay\Api\Errors;
use Razorpay\PaymentButtonVisualComposer\Errors as BtnErrors;

/**
 * Class RZP_Button_Action_SiteOrigin
 */
class RZP_Button_Action_Visual_Composer
{
    public function __construct()
    {
        $this->razorpay = new RZP_VC_Payment_Button_Loader();

        $this->api = $this->razorpay->get_razorpay_api_instance();
    }

    /**
     * Updates the button status
    **/
    function process()
    {
        $btn_id = sanitize_text_field($_POST['btn_id']);
        $action = sanitize_text_field($_POST['btn_action']);
        $paged = sanitize_text_field($_POST['paged']);
        $type = sanitize_text_field($_POST['type']);
        $page_url = admin_url('admin.php?page=rzp_button_view_visual_composer&btn=' . $btn_id . '&type=' . $type . '&paged=' . $paged);

        try
        {
            $this->api->paymentPage->$action($btn_id);
        }
        catch (Exception $e)
        {
            $message = $e->getMessage();

            throw new Errors\Error(
                $message,
                BtnErrors\Payment_Button_VisualComposer_Error_Code::API_PAYMENT_BUTTON_ACTION_FAILED,
                400
            );
        }
        wp_redirect($page_url);
    }
}
