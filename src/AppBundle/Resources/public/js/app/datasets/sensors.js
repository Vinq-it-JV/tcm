/**
 * @ngdoc service
 * @author Jeroen Visser
 * @name DS_Stores
 * @description
 *
 * ## TCM V2.0 Sensors
 *
 */
angular
    .module('tcmApp')
    .factory('DS_Sensors', ['$rootScope', '$translate',
        function ($rootScope, $translate) {

            var d_sensors = [];
            var d_sensor = [];
            var d_template = [];
            var d_lists = [];

            function recordOnIndex(record_id) {
                for (index in d_sensors)
                    if (d_sensors[index].Id == record_id)
                        return index;
                return -1;
            }

            function isValidObject(object) {
                if (typeof object !== 'object')
                    return false;
                if (object.length === 0)
                    return false;
                if (Object.keys(object).length === 0)
                    return false;
                return true;
            }

            return {
                sensorsDS: function () {
                    return this;
                },
                sensorsSet: function (data) {
                    if (typeof data == 'undefined')
                        return d_sensors;
                    d_sensors = angular.copy(data);
                    return d_sensors;
                },
                sensors: function () {
                    return d_sensors;
                },
                sensor: function () {
                    return d_sensor;
                },
                templateSet: function (template) {
                    d_template = template;
                },
                template: function () {
                    return d_template;
                },
                listsSet: function (lists) {
                    d_lists = lists;
                },
                lists: function () {
                    return d_lists;
                },
                getRecord: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_sensor = angular.copy(d_sensors[index]);
                    return d_sensor;
                },
                setRecord: function (record_data) {
                    var index = recordOnIndex(record_data.Id);
                    if (index === -1)
                        return null;
                    d_sensors[index] = angular.copy(record_data);
                    return record_data;
                },
                addRecord: function (record_data) {
                    d_sensors.push(record_data);
                    return record_data;
                },
                clrRecord: function () {
                    d_sensor = angular.copy(d_template.sensor);
                    return d_sensor;
                },
                delRecord: function (record_id) {
                    var index = recordOnIndex(record_id);
                    if (index === -1)
                        return null;
                    d_sensors.splice(index, 1);
                    return true;
                },
                updRecord: function (record_data) {
                    d_sensor = angular.copy(record_data);
                    return d_sensor;
                },
                isValidObject: function (object) {
                    return isValidObject(object);
                }
            };
        }]);