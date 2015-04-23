DirtyBranding.factory('Domain', ['$resource',
function($resource){


    return $resource(
        '../../api/v1/domains/:domain/:member',
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
