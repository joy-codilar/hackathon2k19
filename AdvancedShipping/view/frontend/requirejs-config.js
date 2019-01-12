var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-shipping-information': {
                'Codilar_AdvancedShipping/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'Codilar_AdvancedShipping/js/action/place-order-mixin': true
            }
        }
    }
};