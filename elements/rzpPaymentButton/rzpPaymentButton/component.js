import React from 'react'
import { getService } from 'vc-cake'

const vcvAPI = getService('api')

export default class RzpPaymentButton extends vcvAPI.elementComponent {

  render() {
    const {id, atts, editor} = this.props
    const doAll = this.applyDO('all')
    const {sourceDropdown} = this.props.atts

    if (sourceDropdown && sourceDropdown !== '0') {
      return (
          <div {...editor} id={`el-${id}`} {...doAll}>
            Use below button to pay
            <form>
              <script src="https://cdn.razorpay.com/static/widget/payment-button.js" data-plugin="wordpress-payment-button-visual-composer-1.0"
                      data-payment_button_id={sourceDropdown} ></script>
            </form>
          </div>
      )
    } else {
      return (
          <div {...editor} id={`el-${id}`} {...doAll}>
            Please select payment button
          </div>
      )
    }
  }
}
