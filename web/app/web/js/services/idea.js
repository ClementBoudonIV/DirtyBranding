DirtyBranding.factory('Idea', ['$resource',
function($resource){


    return $resource(
        '../../api/v1/ideas/:idea/:member',
        {
            idea:'@idea'
        },
        {
            brands: {
                method:'GET',
                params:
                {
                    idea:'@idea',
                    member:'brands',
                    'suffixes[]': '@suffixes',
                    'prefixes[]': '@prefixes'
                },
                isArray:true
            }
        }
    );
}]);
