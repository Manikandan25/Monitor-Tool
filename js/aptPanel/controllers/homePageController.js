app.controller('homePageController', function($scope, $routeParams, $http,
		$window, $templateCache) {

	if (localStorage.getItem("token"))
		$window.location.href = "#dashboard";

	$scope.login = function() {

		$(".progress").css("display", "block");
		$templateCache.removeAll();
		$(".errorBlock").hide();
		$("#loginButton").prop('disabled', true);
		$http.post(apiUrl + "/users/login", {
			"userName" : $scope.username,
			"password" : $scope.password
		}).success(function(data, status) {
			if (data.status != 200)
				$scope.errorLog("Invalid user");
			else {
				alert("Login successful");
				localStorage.setItem("token", data.token);
				localStorage.setItem("userId", data.userId);
				$window.location.href = "#/dashboard";
			}
			console.log(data);
			$("#loginButton").prop('disabled', false);
			$(".progress").css("display", "none");
		});
	}

	$scope.registration = function() {
		console.log("Calling: " + apiUrl + "/users/createUser");
		$(".progress").css("display", "block");
		console.log("New email: " + $scope.newEmail);
		if ($scope.newEmail) {
			if ($scope.newPassword == $scope.confirmPassword) {
				$http.post(apiUrl + "/users/createUser", {
					"userName" : $scope.newEmail,
					"password" : $scope.newPassword
				}).success(function(data, status) {
					console.log("NEW USER: " + data);
					if (data.status != 200)
						$scope.errorLog("Invalid user");
					else
						alert("User created successfully");
					console.log(data.message);
					$(".progress").css("display", "none");
				});
			} else {
				$scope.errorLog("Confirmation password does not matched.");
			}
		} else {
			$scope.errorLog("Please enter valid email.");
		}

	}

	$scope.errorLog = function(errorMessage) {
		console.log("Error");
		$(".errorBlock").show();
		$(".errorMessage").html(errorMessage);
	}

	$scope.show = function(divToShow, divToHide) {
		console.log("Div to hide: " + divToShow);
		$("#" + divToShow).show();
		$("#" + divToHide).hide();
	}

});
