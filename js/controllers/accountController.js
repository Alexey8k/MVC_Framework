angular.module('storeApp').controller('accountController', function ($scope, $http, $document) {
    $scope.authorizationView = '';
    $scope.registrationView = '';

    $scope.authorizationDialog = function() {
        let returnUrl = encodeURIComponent(window.location.pathname);
        $scope.authorizationView = "/Account/Login?returnUrl=" + returnUrl;
        $document.find("#authorization-form").dialog("open")
            .on('dialogclose', function () {
                $scope.$apply(() => $scope.authorizationView = '');
            });
    };

    $scope.registrationDialog = function() {
        $scope.registrationView = "/Account/Registration";
        $document.find("#registration-form").dialog("open")
            .on('dialogclose', function () {
                $scope.$apply(() => $scope.registrationView = '');
            });
    };
});