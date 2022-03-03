/* eslint-disable import/no-webpack-loader-syntax */
import { getService } from 'vc-cake'
import RzpPaymentButton from './component'

const vcvAddElement = getService('cook').add

vcvAddElement(
  require('./settings.json'),
  // Component callback
  (component) => {
    component.add(RzpPaymentButton)
  },
)
