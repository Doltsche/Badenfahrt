function validateForm(url, formId) {
    $.ajax({
        type: 'POST',
        url: url,
        data: JSON.stringify($('#' + formId).serializeArray()),
        dataType: 'json',
        contentType: "application/json",
        success: function (data) {
            if (data.persisted) {
                $('#profilemodal').html('');
                $(".modal-backdrop").remove();
            } else {
                $(".modal-backdrop").remove();
                $('#profilemodal').html(data.modal);
                $('.modal').modal({backdrop: 'static'});
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            $('#message').html('<div class="alert alert-danger" role="alert">\n\
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>\n\
            <span class="sr-only">Bei der Anfrage ist ein Fehler aufgetreten.\n\
             Bitte versuchen Sie es später nocheinmal.</span></div>');
        }
    });
}

function showForm(url) {
    $.ajax({
        url: url,
        dataType: 'json',
        contentType: "application/json",
        success: function (data) {
            $('#profilemodal').html(data.modal);
            $('.modal').modal({backdrop: 'static'});
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('Der Request ging in die Toilette:' + errorThrown);
        }
    });
}