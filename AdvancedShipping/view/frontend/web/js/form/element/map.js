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
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    _self.map = new mapLocation(element, _self.onMapAddressSelect.bind(_self), {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    });
                });
            }
        },
        onMapAddressSelect: function (address) {
            var form = $('#' + this.mapid).closest('form');
            form.get(0).reset();
            this.value(Object.values(address.latlng).join(","));
            for(var item in address) {
                 this.populateField(form, item, address[item]);
            }
        },
        populateField: function (form, field, value) {
            switch (field) {
                case "city":
                    form.find('[name="city"]').val(value);
                    break;
                case "zipcode":
                    form.find('[name="postcode"]').val(value);
                    break;
                case "country":
                    console.log(form.find('[name="country_id"]'), value);
                    form.find('[name="country_id"]').val(value);
                    break;
                case "street":
                    form.find('[name="street[0]"]').val(value[0]);
                    form.find('[name="street[1]"]').val(value[1]);
                    break;
                case "state":
                    var element = form.find('[name="region_id"]');
                    if(element.prop("tagName") === "SELECT") {
                        element.find('option').each(function(key, option) {
                            if ($(option).html().toLowerCase() === value.toLowerCase()) {
                                element.val($(option).attr('value'));
                            }
                        });
                    } else {
                        element.val(value);
                    }
            }
        }
    });
});