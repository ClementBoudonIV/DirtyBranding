var DirtyBranding = angular.module('DirtyBranding',['ngRoute','ngResource','ui.keypress','ui.select', 'ngSanitize']);

DirtyBranding.config(['$routeProvider','$locationProvider', function($routeProvider,$locationProvider){
    $routeProvider
        .when('/',{
            templateUrl: 'partials/home.html',
            controller:'HomeController'
        })
        .when('/results/:idea',{
            templateUrl: 'partials/results.html',
            controller:'ResultsController'
        })
        .otherwise({
            redirectTo: '/'
        });

    $locationProvider.html5Mode(true);


}]);
