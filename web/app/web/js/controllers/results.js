DirtyBranding.controller('ResultsController',
    [
        '$scope',
        '$rootScope',
        '$routeParams',
        'Domain',
        'Brand',
        function ($scope, $rootScope, $routeParams, Domain, Brand )
        {
            $scope.idea = $routeParams.idea;
            $scope.available = false;
            $scope.extensions = ['com','fr'];


            //on Check la dispo de la marque
            Brand.available({brand:$scope.idea,'ipoffices[]':['inpi']},function(data) {

                //Cast ngRessource data to bool
                if(data[0] =="t" && data[1] =="r" && data[2] =="u" && data[3] =="e"){
                    data = true;
                }
                else{
                    data = false;
                }
                $scope.available = data;

                console.log($scope.idea);
                console.log($scope.available);

                //ON récupère les domaines possibles pour la marque
                Brand.domains({brand:$scope.idea,'extensions[]':$scope.extensions},function(data) {
                    $scope.domains = [];

                    angular.forEach(data, function(domain, key) {

                         $scope.domains[key] = {
                            name: domain,
                            available: false
                         };
                    });

                    //Pour chaque domaine, on vérifie la dispo
                    angular.forEach($scope.domains, function(brandDomain, key) {
                        Domain.available({domain:brandDomain.name},function(data) {

                            if(data[0] =="t" && data[1] =="r" && data[2] =="u" && data[3] =="e"){
                                data = true;
                            }
                            else{
                                data = false;
                            }
                            $scope.domains[key].available = data; //Cast string to bool

                        });
                    });
                });
            });
        }
    ]
);
