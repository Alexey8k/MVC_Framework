angular.module('storeApp').controller('cartController', function ($scope, $document, $http, $templateCache, $timeout) {
    $scope.cartView = '';

    $scope.loaded = function () {
        $http({
            url: '/Cart/Summary',
            method: 'GET',
        }).then(function success(response) {
            $document.find('#cart-summary').html(response.data);
        });
        $scope.$broadcast('loaded');
    };

    $scope.openCart = function () {
        $scope.cartView = "/Cart/Index1?returnUrl=" + encodeURIComponent(window.location.pathname);
        dialogMiniCartOpen();
    };

    $scope.$on('updateCart', function (event, data) {
        $templateCache.remove($scope.cartView);
        $timeout(() => {
            $scope.cartView = '';
            $scope.$digest();
            $scope.cartView = "/Cart/Index?returnUrl=" + encodeURIComponent(data.returnUrl)
        });
    });

    $scope.$on('addToCart', function (event, data) {
        $scope.cartView = "/Cart/AddToCart?id=" + data.id + "&returnUrl=" + encodeURIComponent(data.returnUrl);
        dialogMiniCartOpen();
    });

    let dialogMiniCartOpen = function () {
        $document.find("#mini-cart").dialog("open")
            .on('dialogclose', function () {
                $templateCache.remove($scope.cartView);
                $scope.cartView = '';
                $scope.loaded();
            });
    }
});