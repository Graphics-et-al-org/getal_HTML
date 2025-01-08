import "tom-select/dist/css/tom-select.css";

import TomSelect from "tom-select";

new TomSelect("#tags",{
	create: true,
    load: function(query, callback) {
        var url = baseurl+'/api/tags?q=' + query;
        fetch(url)
            .then(response => response.json())
            .then(json => {
                //console.log(callback);
                callback(json);
            }).catch(()=>{
                callback();
            });

    },
    plugins: {
		'clear_button':{
			'title':'Remove all selected options',
		},
        remove_button:{
			title:'Remove this item',
		}
	},
});
