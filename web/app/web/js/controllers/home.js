DirtyBranding.controller('HomeController',
    [
        '$scope',
        '$rootScope',
        '$location',
        '$routeParams',
        'SearchFactory',
        function ($scope, $rootScope, $location, $routeParams, SearchFactory){

            $scope.searchForm = SearchFactory.get($scope.searchForm);

            $scope.optionPanel = false;

            $scope.change = function(){
                //va mettre à jour les tab de la factory avelc le sinline du formulaire de recherche
                SearchFactory.changeForm($scope.searchForm);
            }

            $scope.search = function(){
                SearchFactory.save($scope.searchForm);
                $location.path("/results/"+$scope.searchForm.ideas);
            };

            $scope.removeIndex = function(index, array_name){
                $scope.searchForm[array_name].splice(index, 1);
                $scope.change();
            }

            $scope.addElmt = function(array_name){
                $scope.searchForm[array_name].unshift($scope['new_'+array_name]);
                $scope['new_'+array_name] = '';
                $scope.change();
            }




}]);
