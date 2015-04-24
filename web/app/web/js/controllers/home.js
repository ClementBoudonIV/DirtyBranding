DirtyBranding.controller('HomeController',
    [
        '$scope',
        '$rootScope',
        '$location',
        '$routeParams',
        'SearchFactory',
        function ($scope, $rootScope, $location, $routeParams, SearchFactory){

            $scope.searchForm = SearchFactory.get();

            $scope.search = function(){
                SearchFactory.save($scope.searchForm);
                $location.path("/results/"+$scope.searchForm.ideas);
            };


}]);
