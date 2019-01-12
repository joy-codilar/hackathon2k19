define([
    'Magento_Ui/js/form/element/abstract',
    'mapLocation',
    'jquery'
], function (Component, mapLocation, $) {
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
            switch (field) {
                case "city":
                    form.find('[name="city"]').val(value).trigger('change');
                    break;
                case "zipcode":
                    form.find('[name="postcode"]').val(value).trigger('change');
                    break;
                case "country":
                    form.find('[name="country_id"]').val(value).trigger('change');
                    break;
                case "street":
                    form.find('[name="street[0]"]').val(value[0]).trigger('change');
                    form.find('[name="street[1]"]').val(value[1]).trigger('change');
                    break;
                case "state":
                    var element = form.find('[name="region_id"]');
                    if(element.prop("tagName") === "SELECT") {
                        element.find('option').each(function(key, option) {
                            if ($(option).html().toLowerCase() === value.toLowerCase()) {
                                element.val($(option).attr('value')).trigger('change');
                            }
                        });
                    } else {
                        element.val(value).trigger('change');
                    }
            }
        }
    });
});