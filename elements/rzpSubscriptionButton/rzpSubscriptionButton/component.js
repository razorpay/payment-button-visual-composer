import React from 'react'
import { getService } from 'vc-cake'
const vcvAPI = getService('api')

export default class RzpSubscriptionButton extends vcvAPI.elementComponent {
    render() {
        const {id, atts, editor} = this.props
        const doAll = this.applyDO('all')
        const {source} = this.props.atts

        if (source && source !== '0') {
            return (
                <div {...editor} id={`el-${id}`} {...doAll}>
                    Use below button to pay
                    <form>
                        <script src="https://cdn.razorpay.com/static/widget/subscription-button.js"
                                data-subscription_button_id={source}
                                data-plugin="wordpress-subscription-button-visual-composer-1.0"></script>
                    </form>
                </div>
            )
        } else {
            return (
                <div {...editor} id={`el-${id}`} {...doAll}>
                    Please select subscription button
                </div>
            )
        }
    }
}
