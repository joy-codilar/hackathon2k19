define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';

    return function (placeOrderAction) {

        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, messageContainer) {

            console.log(paymentData);
            throw "test error"

            return originalAction(paymentData, messageContainer);
        });
    };
});