define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (placeOrderAction) {

        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, messageContainer) {

            var billingAddress = quote.billingAddress();
            var latlng = null;

            if (billingAddress['extension_attributes'] === undefined) {
                billingAddress['extension_attributes'] = {};
            }

            billingAddress.customAttributes.forEach(function (attribute) {
                if (attribute.attribute_code === "lat_lng") {
                    latlng = attribute.value;
                }
            });

            billingAddress['extension_attributes']['lat_lng'] = latlng;

            return originalAction(paymentData, messageContainer);
        });
    };
});