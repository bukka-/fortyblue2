function ContentCtrl($scope, $http) {
    "use strict";

    $scope.url = './timetable.json';
    $scope.content = [];

    $scope.fetchContent = function() {
        $http.get($scope.url).then(function(result){
            $scope.content = result.data;
        });
    }

    $scope.fetchContent();
}