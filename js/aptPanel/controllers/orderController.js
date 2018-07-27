app.controller('orderController', function($scope, $routeParams, $http,
		$window) {
$scope.clientId = localStorage.getItem("clientId");
//$scope.productValue = product;

$scope.logout = function() {
  localStorage.removeItem("clientId");
  $window.location.href = "#";
}
    });
