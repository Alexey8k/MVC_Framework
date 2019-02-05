angular.module('storeApp').controller('miniCartController', function ($scope, $document, $http) {


    $scope.cart = {};

    $scope.$on('loaded', () => {
        $http({
            url: '/Cart/GetCart',
            method: 'GET',
        }).then(function success(response) {
            $scope.cart = response.data;
        });
    });

    $scope.$on('foo', ()=>{
        console.log("shift");
        $scope.cart.lineCollection.shift();
    });



    $scope.closeMiniCart = function () {
        $document.find("#mini-cart").dialog("close");
    }
});