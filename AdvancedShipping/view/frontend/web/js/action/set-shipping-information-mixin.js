define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();
            var latlng = null;

            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }

            shippingAddress.customAttributes.forEach(function (attribute) {
                if (attribute.attribute_code === "lat_lng") {
                    latlng = attribute.value;
                }
            });

            shippingAddress['extension_attributes']['lat_lng'] = latlng;
            return originalAction();
        });
    };
});