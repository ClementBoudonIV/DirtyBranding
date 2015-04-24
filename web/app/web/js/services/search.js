DirtyBranding.factory('SearchFactory', [
function(){
   var factory = {
        search: {
            ideas:['safe','secure'],
            prefixes:['my','captain'],
            suffixes:['pay','payment'],
            separators:['_'],
            extensions:['com','fr']
        },
        save: function(searchForm){

            //Le searchForm contient les vvaleurs sous forme de liste séparées par des virgules
            //On le transforme alors en search (dont les éléments sont des tableaux)
            factory.search.ideas = factory.searchstringToArray(searchForm.ideas);
            factory.search.prefixes = factory.searchstringToArray(searchForm.prefixes);
            factory.search.suffixes = factory.searchstringToArray(searchForm.suffixes);
            factory.search.separators = factory.searchstringToArray(searchForm.separators);
            factory.search.extensions = factory.searchstringToArray(searchForm.extensions);
        },
        get: function(){
            return factory.search;
        },
        searchstringToArray: function(searchstring){
            var return_array = [];
            if(typeof searchstring  !== 'undefined' && searchstring.length > 0) {
                if(searchstring.constructor === Array){
                    return_array = searchstring;
                }else{
                    if(searchstring.indexOf(',') !== false){
                        return_array = searchstring.split(',');
                    }else{
                        return_array = [searchstring];
                    }
                }
            }
            for (i = 0; i < return_array.length; i++) {
                return_array[i] = return_array[i].trim();
            }
            return return_array;
        }
   };

    return factory;

}]);
