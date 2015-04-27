DirtyBranding.factory('SearchFactory', [
function(){
   var factory = {
        search: {
            ideas:['safe','secure'],
            prefixes:['My','Captain'],
            suffixes:['Pay', 'Payment'],
            separators:[],
            extensions:['com','fr'],
            ipoffices:['inpi'],
            available_ipoffices:['inpi'],
            ideas_inline:'',
            prefixes_inline:'',
            suffixes_inline:'',
            separators_inline:'',
            extensions_inline:'',
            ipoffices_inline:''
        },
        save: function(searchForm){

            //Le searchForm contient les vvaleurs sous forme de liste séparées par des virgules
            //On le transforme alors en search (dont les éléments sont des tableaux)
            factory.search.ideas = factory.searchstringToArray(searchForm.ideas_inline);
            factory.search.prefixes = factory.searchstringToArray(searchForm.prefixes_inline);
            factory.search.suffixes = factory.searchstringToArray(searchForm.suffixes_inline);
            factory.search.separators = factory.searchstringToArray(searchForm.separators_inline);
            factory.search.extensions = factory.searchstringToArray(searchForm.extensions_inline);
            factory.search.ipoffices = factory.searchstringToArray(searchForm.ipoffices_inline);
        },
        get: function(){

            factory.search.ideas_inline = factory.searcharrayToString(factory.search.ideas);
            factory.search.prefixes_inline = factory.searcharrayToString(factory.search.prefixes);
            factory.search.suffixes_inline = factory.searcharrayToString(factory.search.suffixes);
            factory.search.separators_inline = factory.searcharrayToString(factory.search.separators);
            factory.search.extensions_inline = factory.searcharrayToString(factory.search.extensions);
            factory.search.ipoffices_inline = factory.searcharrayToString(factory.search.ipoffices);

            return factory.search;
        },
        changeForm: function(searchForm){
            //A chaque modification d'un champs, on met à jour l'objet complet,
            //pour conserver la synchronisation inline / tab
            //Doit être tenu à jour en fonction des champs présentés (et ça c'est moche)
            //Champs public (inline ou array) vers champs non public (array ou inline)
            factory.search.ideas = factory.searchstringToArray(searchForm.ideas_inline);
            factory.search.prefixes_inline = factory.searchstringToArray(searchForm.prefixes);
            factory.search.suffixes_inline = factory.searchstringToArray(searchForm.suffixes);
            factory.search.separators = factory.searchstringToArray(searchForm.separators_inline);
            factory.search.extensions_inline = factory.searchstringToArray(searchForm.extensions);
            factory.search.ipoffices_inline = factory.searchstringToArray(searchForm.ipoffices);

        },
        searchstringToArray: function(search_string){
            var return_array = [];
            if(typeof search_string  !== 'undefined' && search_string.length > 0) {
                if(search_string.constructor === Array){
                    return_array = search_string;
                }else{
                    if(search_string.indexOf(',') !== false){
                        return_array = search_string.split(',');
                    }else{
                        return_array = [search_string];
                    }
                }
            }
            for (i = 0; i < return_array.length; i++) {
                return_array[i] = return_array[i].trim();
            }
            return return_array;
        },
        searcharrayToString: function(search_array){
            var return_string = '';
            if(typeof search_array  !== 'undefined' && search_array.length > 0) {
                if(search_array.constructor === String){
                    return_string = search_array.trim();
                }else{
                    for (i = 0; i < search_array.length; i++) {
                        search_array[i] = search_array[i].trim();
                    }
                    return_string = search_array.join(', ');
                }
            }

            return_string = return_string.trim();

            return return_string;
        }
   };

    return factory;

}]);
