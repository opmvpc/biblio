require('./bootstrap');
require('./alerts');
require('./helpers.js')
require('./datatable.js');

const dropzone = require('dropzone');

dropzone.options.imageUpload = {
    maxFilesize : 1,
    acceptedFiles: ".pdf",
    maxFilesize: 100,
    init: function() {
        this.on("error", function(file, response) {
            console.log(response);
            toastr.error(response, "Oups !");
        });
        this.on("success", function(file, response) {
            console.log(response);
            toastr.success(response.success, "Succès !");
        });
    }
};
