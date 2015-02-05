function validateForm() {
    $.ajax({
        type: 'POST',
        url: '/user/edit',
        data: JSON.stringify($('#formid').serializeArray()),
        dataType: 'json',
        contentType: "application/json",
        success: function (data) {
            decorate(data);
        },
        error: function (xhr, textStatus, errorThrown) {
            alert(xhr.responseText + 'request failed' + errorThrown + '. Status: ' + textStatus);
        },
    });
}

function syntaxHighlight(json) {
    if (typeof json != 'string') {
        json = JSON.stringify(json, undefined, 2);
    }
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}

function decorate(data) {
    if (data.success) {
        $('#formid').prepend('<div class="alert alert-success" role="alert">\n\
            Änderungen wurden erfolgreich übernommen.\n\
        </div>');
    } else {
        var msg = '';
        jQuery.each(data.messages, function (i, val) {
            // We could use the following line to add custom css to the elements:
            // $("input[name=" + i + "]\"");
            console.log(i + ': ' + val + '<br>');
            msg += i + ': ' + val + '<br>';
        });

        $('#formid').prepend('<div class="alert alert-danger" role="alert">\n\
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>\n\
            <span class="sr-only">Error:</span>' + msg +
                'Die Angaben sind Fehlerhaft.\n\
        </div>');
    }
}

function showForm(url) {
    $.ajax({
        url: url,
        dataType: 'json',
        contentType: "application/json",
        success: function (data) {
            $('#badenfahrtmodal').append(data.form);
            $('#editUserModal').modal({backdrop: 'static'});
        },
        error: function (xhr, textStatus, errorThrown) {
            alert('request failed' + errorThrown + 'Status: ' + textStatus);
        },
    });
}