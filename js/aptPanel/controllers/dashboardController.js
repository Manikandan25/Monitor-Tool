app.controller('dashboardController', function($scope, $routeParams, $http,
		$window) {

	$scope.dashboardPrefix = "http://172.104.45.18:3000/dashboard/db/";

	$scope.filePrefix = host;

	console.log("CHECK");

	var cpuCheck = "http://172.104.45.18:8086/query?pretty=true&db="
			+ localStorage.getItem("userId")
			+ "&q=SELECT%20count(*)%20from%20cpu%20limit%201";
	console.log("CPU: " + cpuCheck);

	$http.get(cpuCheck).then(function(response) {
		console.log(response);
	});

	var xhr = new XMLHttpRequest();
	$scope.token = localStorage.getItem("token");
	$scope.apiKey = localStorage.getItem("userId");
	if ($scope.apiKey != null) {
		$scope.configFile = host + "/api/clientConfig/" + $scope.apiKey
				+ ".conf";
	} else {
		$scope.configFile = "NO";
	}

	if (localStorage.getItem("token") == null)
		$window.location.href = "#";

	$scope.logout = function() {
		localStorage.removeItem("token");
		$window.location.href = "#";
	}

	$scope.show = function(classToHide, divToShow) {
		console.log("Div to hide: " + divToShow);
		$("." + classToHide).hide();
		$("#" + divToShow).show();

	}

	$scope.createDashboard = function(dashboardType) {
		url = apiUrl + "/grafana/createDashboard/" + $scope.apiKey + "/"
				+ dashboardType;
		$http.get(url).then(function(response) {
			console.log(response);
		});
		alert($scope.apiKey + "-" + dashboardType);
	}

	$scope.showDashboard = function(dashboardType) {
		$scope.modalHeaderTitle = $scope.apiKey + "-" + dashboardType;
		var $iframe = $('#grafaDashboard');
		$scope.iframeSrc = $scope.dashboardPrefix + $scope.apiKey + "-"
				+ dashboardType + "?theme=light&from=now-5m&to=now&refresh=5s";
		$iframe.attr('src', $scope.iframeSrc);
	}

});
