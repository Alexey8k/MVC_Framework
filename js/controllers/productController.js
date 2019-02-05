angular.module('storeApp').controller('productController', function ($scope) {

    $scope.data = {};

    $scope.addToCart = function () {
        $scope.$emit('addToCart', $scope.data);
    };
});
