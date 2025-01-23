import "tom-select/dist/css/tom-select.css";

import TomSelect from "tom-select";

new TomSelect("#roles",{
	create: true,
    allowEmptyOption: true,
    plugins: {
		'clear_button':{
			'title':'Remove all selected options',
		},
        remove_button:{
			title:'Remove this item',
		}
	},
});

new TomSelect("#teams",{
	create: true,
    allowEmptyOption: true,
    plugins: {
		'clear_button':{
			'title':'Remove all selected options',
		},
        remove_button:{
			title:'Remove this item',
		}
	},
});

