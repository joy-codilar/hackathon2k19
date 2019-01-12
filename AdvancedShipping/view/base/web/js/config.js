define([], function () {
    var _data = {};
    return {
        get: function (key) {
            return _data[key];
        },
        set: function (key, value) {
            _data[key] = value;
            return this;
        }
    }
});