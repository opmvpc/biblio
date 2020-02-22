import toastr from 'toastr/toastr';

toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

window.toastr = toastr;

// Affichage de l'alerte si variable de session "ok" ou "errors"
console.log(window.ok)
if (window.ok) {
    toastr.success(window.ok, "Succès !");
}
if (window.error) {
    toastr.error(window.error, "Oups !");
}
