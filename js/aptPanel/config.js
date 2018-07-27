var app = angular.module('aptPanel', ['ngRoute']);

app.config([ '$routeProvider', '$locationProvider',
		function($routeProvider, $locationProvider) {

			$routeProvider
			.when("/", {
				templateUrl : "templates/home.html",
				controller : "homePageController",
			}).when("/dashboard",{
				templateUrl : "templates/dashboard.html",
				controller : "dashboardController",
			}).when("/product", {
				templateUrl : "templates/product.html",
				controller : "productController",
			}).when("/order", {
				templateUrl : "templates/order.html",
				controller : "orderController",
			}).when("/productgraph", {
				templateUrl : "templates/productgraph.html",
				controller : "productGraph",
			});

		} ]);
