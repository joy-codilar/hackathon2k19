define([
    'jquery'
], function ($) {
    return {
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
                    if (form.find('[name="street[]"]')) {
                        form.find('[name="street[]"]').each(function (key, element) {
                            $(element).val(value[key]).trigger('change');
                        })
                    }
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
    }
});