var page = window.location.origin + '/';
var URL = window.location.href;

$(document).ready(function (e) {

});

function updated(admin_id) {
    $('.modal-loading').modal('show');
    $.get(URL + '?ajaxtype=update', {}, function (data) {
        setTimeout(function () {
            $('.modal-loading').modal('hide');
        }, 1000);

        console.log('data:' + JSON.stringify(data))
        if (data.status == 'false' || data.status === false) {
            Toastify({
                text: 'UnableToUpdated!',
                duration: 100000,
                close: true,
                gravity: "top",
                positionLeft: false,
                className: "alert-warning"
            }).showToast();
        }
    });
}