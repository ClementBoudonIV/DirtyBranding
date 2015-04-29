DirtyBranding.factory('Domain', ['$resource',
function($resource){


    return $resource(
        'http://api.dirtybranding.com/v1/domains/:domain/:member',
        {
            domain:'@domain'
        },
        {
            available: {
                method:'GET',
                params:
                {
                    domain:'@domain',
                    member:'available'
                }
            }
        }
    );
}]);
