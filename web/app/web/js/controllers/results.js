DirtyBranding.controller('ResultsController',
    [
        '$scope',
        '$rootScope',
        '$routeParams',
        'Domain',
        'Brand',
        'Idea',
        'SearchFactory',
        function ($scope, $rootScope, $routeParams, Domain, Brand, Idea, SearchFactory )
        {

            $scope.ideas = [];

            $scope.searchForm = SearchFactory.get();

            angular.forEach($scope.searchForm.ideas, function(idea, idea_key) {
                //pour chaque idée soumise
                //on crée un objet pour chaque idée
                $scope
                .ideas[idea_key] = {
                    name: idea,
                    brands: []
                };


                //on récupère via l'API les marques correspondantes à partir de l'idée et des éventuels suffixes / prefixes
                Idea.brands({
                    idea:idea,
                    'suffixes[]':$scope.searchForm.suffixes,
                    'prefixes[]':$scope.searchForm.prefixes,
                },function(brands) {

                    $scope
                    .ideas[idea_key]
                    .brands = brands;

                    //pour chaque marque
                    angular.forEach(
                        $scope
                        .ideas[idea_key]
                        .brands,
                        function(brand, brand_key) {

                        //par défaut non available
                        //Chaque brand est un objet
                        $scope
                        .ideas[idea_key]
                        .brands[brand_key] = {
                            main_name: brand,
                            possible_names: []
                        }



                        //On récupère les noms aternatifs de la marque (qui retournera également la marque de base)
                        Brand.alternatives({
                            brand:$scope.ideas[idea_key].brands[brand_key].main_name,
                            'separators[]':$scope.searchForm.separators
                        },function(brand_alternatives) {
                            $scope
                            .ideas[idea_key]
                            .brands[brand_key]
                            .possible_names = brand_alternatives;

                            angular.forEach(
                                $scope
                                .ideas[idea_key]
                                .brands[brand_key]
                                .possible_names,
                                function(alternative, possible_key){

                                $scope
                                .ideas[idea_key]
                                .brands[brand_key]
                                .possible_names[possible_key] = {
                                    name: alternative,
                                    available: false,
                                    domains: []
                                }

                                //on Check la dispo de la marque, les bureaux de PI sont en dur
                                Brand.available({
                                    brand: $scope.ideas[idea_key].brands[brand_key].possible_names[possible_key].name,
                                    'ipoffices[]':['inpi']
                                },function(brand_alternative_available) {

                                    //Cast ngRessource data to bool
                                    if(brand_alternative_available[0] =="t"
                                        && brand_alternative_available[1] =="r"
                                        && brand_alternative_available[2] =="u"
                                        && brand_alternative_available[3] =="e"){
                                        brand_alternative_available = true;
                                    }
                                    else{
                                        brand_alternative_available = false;
                                    }
                                    $scope
                                    .ideas[idea_key]
                                    .brands[brand_key]
                                    .possible_names[possible_key]
                                    .available = brand_alternative_available;

                                    //On récupère les domaines possibles pour la marque
                                    Brand.domains({
                                        brand:$scope.ideas[idea_key].brands[brand_key].possible_names[possible_key].name,
                                        'extensions[]':$scope.searchForm.extensions
                                    },function(domains) {
                                        $scope
                                        .ideas[idea_key]
                                        .brands[brand_key]
                                        .possible_names[possible_key]
                                        .domains = domains;

                                        angular.forEach(
                                            $scope
                                            .ideas[idea_key]
                                            .brands[brand_key]
                                            .possible_names[possible_key]
                                            .domains,
                                            function(domain, domain_key) {

                                            $scope
                                            .ideas[idea_key]
                                            .brands[brand_key]
                                            .possible_names[possible_key]
                                            .domains[domain_key] = {
                                                name: domain,
                                                available: false
                                            };

                                            Domain.available({
                                                domain:$scope.ideas[idea_key].brands[brand_key].possible_names[possible_key].domains[domain_key].name
                                            },function(domain_available) {

                                                if(domain_available[0] =="t"
                                                    && domain_available[1] =="r"
                                                    && domain_available[2] =="u"
                                                    && domain_available[3] =="e"){
                                                    domain_available = true;
                                                }
                                                else{
                                                    domain_available = false;
                                                }
                                                $scope
                                                .ideas[idea_key]
                                                .brands[brand_key]
                                                .possible_names[possible_key]
                                                .domains[domain_key]
                                                .available = domain_available; //Cast string to bool

                                            });
                                        });
                                    });
                                });
                            });
                        });
                    });
                });
            });
        }
    ]
);
