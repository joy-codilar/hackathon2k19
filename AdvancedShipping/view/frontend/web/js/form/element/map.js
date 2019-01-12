define([
    'Magento_Ui/js/form/element/abstract',
    'mapLocation',
    'Codilar_AdvancedShipping/js/renderer/form',
    'jquery'
], function (Component, mapLocation, formRenderer, $) {
    return Component.extend({
        mapid: null,
        map: null,
        initialize: function () {
            this._super();
            this.mapid = 'map_' + this.uid;
        },
        renderMap: function(element) {
            var _self = this;
            var latLng = null;
            if (!this.value()) {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        latLng = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        }
                    });
                }
            } else {
                var latLngArray = this.value().split(",").map(function (num) { return Number.parseFloat(num); });
                if (latLngArray.length === 2 && latLngArray.every(function(e) { return !isNaN(e); })) {
                    latLng = {
                        lat: latLngArray[0],
                        lng: latLngArray[1]
                    }
                }
            }
            _self.map = new mapLocation(element, _self.onMapAddressSelect.bind(_self), latLng);
        },
        onMapAddressSelect: function (address) {
            var form = $('#' + this.mapid).closest('form');
            this.value(Object.values(address.latlng).join(","));
            for(var item in address) {
                 this.populateField(form, item, address[item]);
            }
        },
        populateField: function (form, field, value) {
            formRenderer.populateField(form, field, value);
        }
    });
});