var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'Codilar_AdvancedShipping/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'Codilar_AdvancedShipping/js/action/set-payment-information-mixin': true
            }
        }
    }
};