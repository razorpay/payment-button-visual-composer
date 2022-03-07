import React from 'react'
import { getService } from 'vc-cake'
const vcvAPI = getService('api')
import { renderToString } from 'react-dom/server'

export default class RzpSubscriptionButton extends vcvAPI.elementComponent {
    render() {
        const {id, atts, editor} = this.props
        const doAll = this.applyDO('all')
        const {source} = this.props.atts

        if (source && source !== '0') {
            const buttonHTML = (
                <div {...editor} id={`el-${id}`} {...doAll}>
                    <form>
                        <script src="https://cdn.razorpay.com/static/widget/subscription-button.js"
                                data-plugin="wordpress-subscription-button-visual-composer-1.0"
                                data-subscription_button_id={source}></script>
                    </form>
                </div>
            )
            const buttonHtmlString = renderToString(buttonHTML)

            return (
                <div className='vcvhelper' data-vcvs-html={buttonHtmlString} {...editor} id={`el-${id}`} {...doAll}>
                    <img src='https://cdn.razorpay.com/static/assets/payment-buttons/sdks/subscription-button-preview.svg'/>
                </div>
            )
        } else {
            return (
                <div className='vcvhelper' data-vcvs-html="" {...editor} id={`el-${id}`} {...doAll}>
                    Please select subscription button
                </div>
            )
        }
    }
}
