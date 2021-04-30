var page = (window.location.href).split('?');
var URL = page[0];
var publicURL = '';

$(document).ready(function (e) {
    publicURL = $('#publicUrl').val();
});

function details(user_id) {
    $('.modal-loading').modal('show');
    $('#member_update')[0].reset();
    $.get(URL + '?ajaxtype=details&user_id=' + user_id, {}, function (data) {
        setTimeout(function () {
            $('.modal-loading').modal('hide');
        }, 1000);

        //console.log('data:' + JSON.stringify(data))
        if (data.status == 'true' || data.status === true) {
            var details = data.details;
            console.log('details:', details)

            $("#id").val(details.id);
            $("#fname").val(details.fname);
            $("#lname").val(details.lname);
            $("#email").val(details.email);
            $("#phone").val(details.phone);
            $("#address").val(details.address);
            $("#city").val(details.city);
            $("#state").val(details.state);
            $('#image-profile').attr({"src": details.files});
            $("#status").val(details.status);
            $("#roles").val(details.roles);
            $('#exampleModalCenter').modal('show');
        }
    });
}

function updated(updated = false) {
    $('.modal-loading').modal('show');
    var datas = $('#member_update').serializeArray();

    $.ajax({
        url: URL + '/update',
        dataType: 'json',
        type: 'post',
        data: JSON.stringify(datas),
        contentType: "application/json; charset=utf-8",
        processData: false,
        headers: {'X-CSRF-Token': $('input[name="_token"]').val()},
        success: function (data) {
            //console.log('datas[success]:', JSON.stringify(data))
            setTimeout(function () {
                $('.modal-loading').modal('hide');
            }, 1000);
            if (data.status == 'true') {
                //$('#exampleModalCenter').modal('hide');
                Toastify({
                    text: "Record " + updated + " Updated",
                    duration: 90000,
                    close: true,
                    gravity: "top",
                    positionLeft: false,
                    className: "alert-warning"
                }).showToast();
//                setInterval(function () {
//                    location.reload();
//                }, 3000);
            } else {
                Toastify({
                    text: data.msg,
                    duration: 90000,
                    close: true,
                    gravity: "top",
                    positionLeft: false,
                    className: "alert-warning"
                }).showToast();
            }
        },
        error: function (jqXhr, textStatus, errorThrown) {
            setTimeout(function () {
                $('.modal-loading').modal('hide');
            }, 1000);
        }
    });
}

function removed($id) {
    $('.modal-loading').modal('show');
    var objects = {
        id: $id
    }

    $.ajax({
        url: URL + '/remove',
        dataType: 'json',
        type: 'post',
        data: JSON.stringify(objects),
        contentType: "application/json; charset=utf-8",
        processData: false,
        headers: {'X-CSRF-Token': $('input[name="_token"]').val()},
        success: function (data) {
            //console.log('datas[success]:', JSON.stringify(data))
            setTimeout(function () {
                $('.modal-loading').modal('hide');
            }, 1000);
            if (data.status == 'true') {
                //$('#exampleModalCenter').modal('hide');
                Toastify({
                    text: "Record Removed",
                    duration: 90000,
                    close: true,
                    gravity: "top",
                    positionLeft: false,
                    className: "alert-warning"
                }).showToast();
//                setInterval(function () {
//                    location.reload();
//                }, 3000);
            } else {
                Toastify({
                    text: data.msg,
                    duration: 90000,
                    close: true,
                    gravity: "top",
                    positionLeft: false,
                    className: "alert-warning"
                }).showToast();
            }
        },
        error: function (jqXhr, textStatus, errorThrown) {
            setTimeout(function () {
                $('.modal-loading').modal('hide');
            }, 1000);
        }
    });
}

function AddNew(types = null) {
    switch (types) {
        case 'member':
            $('#create_new_member')[0].reset();
            $('#exampleModalCenter3').modal('show');
            break;
        default:
            $('#create_new')[0].reset();
            $('#exampleModalCenter2').modal('show');
            break;
}
}

function createNew(types) {
    $('.modal-loading').modal('show');
    var addNewStatus = true;
    var datas = $('#create_new_member').serializeArray();

    let urlPath = URL + '/add';
    if (addNewStatus === true) {
        $.ajax({
            url: urlPath,
            dataType: 'json',
            type: 'post',
            data: JSON.stringify(datas),
            contentType: "application/json; charset=utf-8",
            processData: false,
            headers: {'X-CSRF-Token': $('input[name="_token"]').val()},
            success: function (data) {
                setTimeout(function () {
                    $('.modal-loading').modal('hide');
                }, 1000);
                if (data.status == 'true') {
                    $('#exampleModalCenter').modal('hide');
                    Toastify({
                        text: data.msg,
                        duration: 90000,
                        close: true,
                        gravity: "top",
                        positionLeft: false,
                        className: "alert-warning"
                    }).showToast();
                    setInterval(function () {
                        location.reload();
                    }, 3000);
                } else {
                    Toastify({
                        text: data.msg,
                        duration: 90000,
                        close: true,
                        gravity: "top",
                        positionLeft: false,
                        className: "alert-warning"
                    }).showToast();
                }
            },
            error: function (jqXhr, textStatus, errorThrown) {
                setTimeout(function () {
                    $('.modal-loading').modal('hide');
                }, 1000);
            }
        });
    } else {
        setTimeout(function () {
            $('.modal-loading').modal('hide');
        }, 1000);
    }
}