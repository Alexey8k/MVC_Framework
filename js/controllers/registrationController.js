angular.module('storeApp').controller('registrationController', function ($scope, $document, $http) {
    $scope.data = {
        login: "",
        password: "",
    };

    $scope.confirmPassword = '';

    $scope.loginLength = { min: 2, max: 16 };

    $scope.passwordLength = {min: 2, max: 16 };

    $scope.registration = function (form) {
        if (form.$valid) {
            $http({
                url: "/Account/Registration",
                method: 'POST',
                data: $scope.data,
            }).then(function success(response) {
                console.log(response.data.result);
                checkAuthorizationResult(response.data.result, form);
            }, function error(response) {
                updateTips("Ошибка подключения к серверу, код ошибки: "+ response.status);
            });
        }
    };

    function checkAuthorizationResult(result, form) {
        switch (result){
            case 0:
                $document.find("#registration-form").dialog("close");
                break;
            case 1:
                updateTips('Данный логин уже используется.');
                break;
            default:
                updateTips('Неизвесная ошибка регистрации.');
                break;
        }
    }

    function updateTips(text) {
        $document.find("#registration-form .validateTips")
            .text(text)
            .css("visibility", "visible")
            .addClass("ui-state-highlight");
        setTimeout(function () {
            $document.find("#registration-form .validateTips").removeClass("ui-state-highlight", 1500);
        }, 500);
    }
});