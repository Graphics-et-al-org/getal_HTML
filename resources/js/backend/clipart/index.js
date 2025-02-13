import "tom-select/dist/css/tom-select.css";

import TomSelect from "tom-select";

console.log(baseurl);

new TomSelect("#tags",{
	create: false,
    options: tags,
    preload: true,
    valueField: "value",
    items: tags.map((item) => {
        return item.value;
    }),
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
