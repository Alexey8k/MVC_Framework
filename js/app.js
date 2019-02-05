angular.module("storeApp", ['ngMessages', 'ngAnimate', 'ngSanitize', 'ui.bootstrap'])
    .config(function ($httpProvider) {
        // send all requests payload as query string
        $httpProvider.defaults.transformRequest = function (data) {
            if (data === undefined) {
                return data;
            }
            return jQuery.param(data);
        };

        // set all post requests content type
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
    })
    .directive("ngFormSubmit", function(){
        return {
            require:"form",
            link: function($scope, $el, $attr, $form) {
                $form.submit = $el[0].submit.bind($el[0]);
            }
        };
    })
    .directive('loginValid', function($q, $http) {
        return {
            require: 'ngModel',
            link: function(scope, elm, attrs, ctrl) {

                ctrl.$asyncValidators.loginValid = function(modelValue, viewValue) {

                    if (ctrl.$isEmpty(modelValue)) {
                        // consider empty model valid
                        return $q.resolve();
                    }

                    let def = $q.defer();

                    $http({
                        url: '/Account/CheckLogin',
                        method: 'POST',
                        data: {login: viewValue}
                    }).then(function success(response) {
                        response.data.result ? def.reject() : def.resolve();
                    });

                    return def.promise;
                };
            }
        };
    })
    .directive('compareTo', function() {
        return {
            require: "ngModel",
            scope: {
                otherModelValue: "=compareTo"
            },
            link: function(scope, element, attributes, ctrl) {
                ctrl.$validators.compareTo = function(modelValue, viewValue) {
                    return modelValue === scope.otherModelValue.$modelValue;
                };


            }
        };
    })
    .directive('mpValueCopy', function($parse) {
        return function(scope, element, attrs) {
            if (attrs.ngModel) {
                if (element[0].type === "radio") {
                    if (element[0].checked === true) {
                        $parse(attrs.ngModel).assign(scope, element.val());
                    }
                } else {
                    $parse(attrs.ngModel).assign(scope, element.val());
                }
            }
        };
    })
    .run();