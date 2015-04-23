DirtyBranding.factory('Brand', ['$resource',
function($resource){


    return $resource(
        '../../api/v1/brands/:brand/:member',
        {
            brand:'@brand'
        },
        {
            domains: {
                method:'GET',
                params:
                {
                    brand:'@brand',
                    member:'domains',
                    'extensions[]': '@extensions'
                },
                isArray:true
            },
            available: {
                method:'GET',
                params:
                {
                    brand:'@brand',
                    member:'available',
                    'ipoffices[]':'@ipoffices'
                }
            }
        }
    );
}]);
