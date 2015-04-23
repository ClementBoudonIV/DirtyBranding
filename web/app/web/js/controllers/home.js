DirtyBranding.controller('HomeController', ['$scope','$rootScope','$location','$routeParams', function ($scope, $rootScope, $location, $routeParams){

    $scope.search = function(){
        $location.path("/results/"+$scope.search.idea);
    };

}]);
