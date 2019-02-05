angular.module('storeApp').controller('cartLineController', function ($scope, $document, $http, $timeout) {
    $scope.data = {
        id: null,
        quantity: null,
        returnUrl: null
    };

    $scope.amountMinus = function ($event) {
        $scope.data.quantity--;
        submit($event);
    };

    $scope.amountPlus = function ($event) {
        $scope.data.quantity++;
        submit($event);
    };

    $scope.removeLine = function ($event) {
        $scope.data.quantity = 0;
        submit($event);
    };

    $scope.changeQuantity = function (event) {
        $http({
            url: '/Cart/ChangeQuantity',
            method: 'POST',
            params: $scope.data
        }).then(function success(response) {
            if (response.data.totalCount === 0)
                $document.find("#mini-cart").dialog("close");
            else
                $scope.$emit('updateCart', $scope.data);
        });
    };

    function submit($event) {
        $timeout(function() {
            angular.element($event.currentTarget.form).triggerHandler('submit');
        });
    }
});