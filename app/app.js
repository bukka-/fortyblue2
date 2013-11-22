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

