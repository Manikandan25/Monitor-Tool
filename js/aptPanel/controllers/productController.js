app.controller('productController', function($scope, $routeParams, $http,
		$window,$document) {

			$scope.cnt=0;
      $scope.clientId = localStorage.getItem("clientId");
			//console.log(nextPaginationToken);
      // $http.post("https://4e996kdqs5.execute-api.us-west-2.amazonaws.com/java/")
      //     .then(function (response) {
      //       $scope.names = response.data.records;
      //
      //     });
					$http.post("https://4e996kdqs5.execute-api.us-west-2.amazonaws.com/java/",{
"handler":"listClientProducts",
"clientId":"ab",
"startKey":"0",
"limit": nextPaginationToken.toString()
}).success(function(data,status) {
	$scope.names = data;
	//console.log(data);
	nextPaginationToken = data.nextPaginationToken;
					});
    $scope.change = function(productId){
      $window.location.href = "#productgraph";
			product = $(this,'h4').text();
			alert($('h4').text());
			alert(productId);
			//Samplecall();
			//sampleCall();
    }
		$scope.logout = function() {
		  localStorage.removeItem("clientId");
		  $window.location.href = "#";
		}
		// $document.bind('scroll', function(){
		// 	console.log($('#totalproduct').height());
		// 	console.log($window.scrollY);
		// });


    });
		app.directive("scroll", function ($window) {
		    return function(scope, element, attrs) {
		        angular.element($window).bind("scroll", function() {
		             if (this.pageYOffset >= 500) {
		                 //scope.boolChangeClass = true;
		                 console.log(this.pageYOffset);
										 console.log($(this).height());
		             } else {
		                 //scope.boolChangeClass = false;
		                 //console.log('Header is in view.');
		             }
		            scope.$apply();
		        });
		    };
		});
function sampleCall()
{
	alert('asd');
}
