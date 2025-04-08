import "tom-select/dist/css/tom-select.css";

import TomSelect from "tom-select";

new TomSelect("#teams", {
    create: false,
    options: teams,
    preload:true,
    valueField: "value",
    items: teams.map((item) => {
        return item.value;
    }),
    load: function (query, callback) {
        var url = baseurl + "/admin/teams/search?q=" + query;
        fetch(url)
            .then((response) => response.json())
            .then((json) => {
                console.log('loading teams');
                callback(json);
            })
            .catch(() => {
                callback();
            });
    },
    plugins: {
        clear_button: {
            title: "Remove all selected options",
        },
        remove_button: {
            title: "Remove this item",
        },
    },
});


new TomSelect("#users", {
    create: false,
    preload:true,
    options: users,
    valueField: "value",
    items: users.map((item) => {
        return item.value;
    }),
    load: function (query, callback) {
        var url = baseurl + "/admin/users/search?q=" + query;
        fetch(url)
            .then((response) => response.json())
            .then((json) => {
                console.log('loading users');
                //console.log(callback);
                callback(json);
            })
            .catch(() => {
                callback();
            });
    },
    plugins: {
        clear_button: {
            title: "Remove all selected options",
        },
        remove_button: {
            title: "Remove this item",
        },
    },
});

// save to database, putting here so we can do fancy stuff later
window.save = () => {
    const form = document.getElementById("storeForm");
    form.submit();
};

