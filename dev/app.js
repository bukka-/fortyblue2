var myApp = angular.module('myApp', ['ngRoute'])
	.config(function ($routeProvider, $locationProvider) {
		$routeProvider
			.when('/', {
				templateUrl: 'app/views/table_show.html',
				controller: 'DropdownCtrl'
			})
			.when('/edit', {
				templateUrl: 'app/views/table_edit.html',
				controller: 'EditCtrl'
			})
			.otherwise({
				redirectTo: '/'
			});
		$locationProvider.html5Mode(true);
});


function DropdownCtrl($scope) {
	$scope.items = [
		"IV-3 IB",
		"IV-2 IB",
		"IV-1 IB"
	];

	$scope.display = $scope.items[0];

	$scope.select = function(choice){
		$scope.display = choice;
	}
}
