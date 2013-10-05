var myApp = angular.module('myApp', []);

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

