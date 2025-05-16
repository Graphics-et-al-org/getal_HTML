import "tom-select/dist/css/tom-select.css";
import "pretty-checkbox/dist/pretty-checkbox.min.css";


import TomSelect from "tom-select";

console.log(tags);

new TomSelect("#tags",{
	create: true,
    preload: true,
    options: tags,
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

// regenerate AI parameters
window.saveAndRegenerate = ()=>{
   // console.log('hi')
    const form = document.getElementById("updateForm");
    addHiddenField(form, "updategpt", 'true');
    form.submit();
}

const addHiddenField = (form, name, value) => {
    let input = document.createElement("input");
    input.type = "hidden";
    input.name = name;
    input.value = value;
    form.appendChild(input);
};
