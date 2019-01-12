/*jshint browser:true jquery:true*/
define([
    "jquery",
    "Codilar_AdvancedShipping/js/config"
], function ($, Config) {
    "use strict";
    window.AdvancedShipping = {};
    var isScriptTagCreate = {
        isCreated: function () {
            return window.AdvancedShipping.scriptLoaded;
        },
        create: function (apiKey, onLoad) {
            if (!this.isCreated()) {
                var scriptUrl = 'https://maps.googleapis.com/maps/api/js?key=' + apiKey;
                window.AdvancedShipping.scriptLoaded = true;
                $.getScript(scriptUrl).done(function () {
                    onLoad();
                });
            } else {
                onLoad();
            }
        }
    };
    var Map = function (element, onChange, defaultMarker) {
        //window.advancedShipping.apiKey
        this.element = element;
        this.onchange = onChange;
        this.defaultMarker = defaultMarker ? defaultMarker : Config.get('default_marker');
        isScriptTagCreate.create(Config.get('api_key'), this.render.bind(this));
    };

    Map.prototype = {
        map: null,
        marker: false,
        render: function () {
            // Wait until google maps is ready on page
            if (!window.google) {
                return setTimeout(this.render.bind(this), 500);
            }

            this.map = new window.google.maps.Map(this.element, {
                center: {lat: this.defaultMarker.lat, lng: this.defaultMarker.lng},
                zoom: Config.get('zoom')
            });
            this.initMarker(this.defaultMarker);
            if (typeof this.onchange === 'function') {
                this.attachEventListeners();
            }

        },
        attachEventListeners: function () {
            var _self = this;
            window.google.maps.event.addListener(this.map, 'click', function (event) {
                //Get the location that the user clicked.
                var clickedLocation = event.latLng;
                //If the marker hasn't been added.
                if (_self.marker === false) {
                    _self.initMarker(clickedLocation);
                } else {
                    //Marker has already been added, so just change its location.
                    _self.marker.setPosition(clickedLocation);
                }
                //Get the marker's location.
                _self.markerLocation();
            });
        },
        initMarker: function (latlng) {
            var _self = this;
            //Create the marker.
            _self.marker = new google.maps.Marker({
                position: latlng,
                map: _self.map,
                draggable: typeof _self.onchange === "function" //make it draggable
            });
            if (typeof _self.onchange === "function") {
                //Listen for drag events!
                window.google.maps.event.addListener(_self.marker, 'dragend', function (event) {
                    _self.markerLocation();
                });
            }
        },
        markerLocation: function () {
            var _self = this;
            var currentLocation = this.marker.getPosition();
            var geocoder = new google.maps.Geocoder;
            var latLng = {
                lat: currentLocation.lat(),
                lng: currentLocation.lng()
            }
            //Add lat and lng values to a field that we can save.
            geocoder.geocode({'location': latLng}, function (results, status) {
                var address = {
                    zipcode: "",
                    country: "",
                    state: "",
                    city: ""
                };
                if (status === google.maps.GeocoderStatus.OK) {
                    results = [results[0]];
                    address.latlng = {
                        "lat": results[0].geometry.location.lat(),
                        "lng": results[0].geometry.location.lng()
                    }
                    address.street = [];
                    results.forEach(function (result) {
                        result.address_components.forEach(function (component) {
                            Object.assign(address, _self.manageAddressComponent(component, address));
                        });
                        var breakPoint = Math.floor(address.street.length / 2);
                        address.street = [address.street.slice(0, breakPoint).join(", "), address.street.slice(breakPoint + 1).join(", ")];
                    });
                }
                if (typeof _self.onchange === "function") {
                    _self.onchange(address);
                }
            });
        },
        manageAddressComponent: function (component, address) {
            var componentMap = {
                "postal_code": "zipcode",
                "country": "country",
                "administrative_area_level_1": "state",
                "locality": "city"
            };

            for (var item in componentMap) {
                if (component.types.indexOf(item) >= 0) {
                    var result = {};
                    result[componentMap[item]] = (componentMap[item] === 'country') ? component.short_name : component.long_name;
                    return result;
                }
            }


            if (!address.street) address.street = [];
            if (address.street.indexOf(component.long_name) < 0) {
                address.street.push(component.long_name);
            }

            return {};
        }
    }

    return Map;
});