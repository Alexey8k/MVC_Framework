angular.module('storeApp').controller('authorizationController', function ($scope, $http, $document) {
    $scope.data = {
        login: '',
        password: ''
    };

    $scope.authorization = function (form) {
        let bValid = true,
            login = $document.find("input[name = login]"),
            password = $document.find("input[name = password]");
        $document.find("#authorization-form :input[class != submit]").removeClass("ui-state-error");

        bValid = bValid && checkLength(login, "логина", 2, 16);
        bValid = bValid && checkRegexp(login, /^[a-z]([0-9a-z_])+$/, "Логин может состоять из: a-z, 0-9, подчеркивания и начинаться с буквы.");

        bValid = bValid && checkLength(password, "пароля", 3, 16);
        bValid = bValid && checkRegexp(password, /^([0-9a-zA-Z])+$/, "Пароль может состоять из: a-z 0-9");
        if (bValid) {
            $http({
                url: "/Account/Login",
                method: 'POST',
                data: $scope.data,
            }).then(function success(response) {
                checkAuthorizationResult(response.data.result, form);
            }, function error(response) {
                updateTips("Ошибка подключения к серверу, код ошибки: "+ response.status);
            });
        }
    };

    function checkAuthorizationResult(result, form) {
        switch (result){
            case 0:
                form.submit();
                $document.find("#authorization-form").dialog("close");
                break;
            case 1:
                updateTips('Данный пользователь уже в онлайн.');
                break;
            case 2:
                updateTips('Неверный логин или пароль.');
                break;
            default:
                updateTips('Неизвесная ошибка авторизации.');
                break;
        }
    }

    function updateTips(text) {
        $document.find("#authorization-form .validateTips")
            .text(text)
            .css("visibility", "visible")
            .addClass("ui-state-highlight");
        setTimeout(function () {
            $document.find("#authorization-form .validateTips").removeClass("ui-state-highlight", 1500);
        }, 500);
    }

    function checkLength(input, nameField, min, max) {
        if (input.val().length > max || input.val().length < min) {
            input.addClass("ui-state-error");
            updateTips("Длина " + nameField + " должна быть от " + min + " до " + max + ".");
            return false;
        } else {
            return true;
        }
    }

    function checkRegexp(input, regexp, testTips) {
        if (!( regexp.test(input.val()) )) {
            input.addClass("ui-state-error");
            updateTips(testTips);
            return false;
        } else {
            return true;
        }
    }
});