import React from 'react'
import { getService } from 'vc-cake'
import { renderToString } from 'react-dom/server'

const vcvAPI = getService('api')

export default class RzpPaymentButton extends vcvAPI.elementComponent {

    render() {
        const {id, atts, editor} = this.props
        const doAll = this.applyDO('all')
        const {sourceDropdown} = this.props.atts

        if (sourceDropdown && sourceDropdown !== '0') {
            const buttonHTML = (
                <div {...editor} id={`el-${id}`} {...doAll}>
                    <form>
                        <script src="https://cdn.razorpay.com/static/widget/payment-button.js"
                                data-plugin="wordpress-payment-button-visual-composer-1.0"
                                data-payment_button_id={sourceDropdown}></script>
                    </form>
                </div>
            )
            const buttonHtmlString = renderToString(buttonHTML)

            return (
                <div className='vcvhelper' data-vcvs-html={buttonHtmlString} {...editor} id={`el-${id}`} {...doAll}>
                    <img src='https://cdn.razorpay.com/static/assets/payment-buttons/sdks/payment-button-preview.svg'/>
                </div>
            )
        } else {
            return (
                <div className='vcvhelper' data-vcvs-html="" {...editor} id={`el-${id}`} {...doAll}>
                    Please select payment button
                </div>
            )
        }
    }
}
