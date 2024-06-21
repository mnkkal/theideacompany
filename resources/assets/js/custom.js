window.displayToastr = function (heading, icon, message) {
    $.toast({
        heading: heading,
        text: message,
        showHideTransition: 'slide',
        icon: icon,
        position: 'top-right',
    });
};

window.htmlSpecialCharsDecode = function (string) {
    return jQuery('<div />').html(string).text();
};

window.setLocalStorageItem = function (variable, data) {
    localStorage.setItem(variable, data);
};

window.getLocalStorageItem = function (variable) {
    return localStorage.getItem(variable);
};

window.removeLocalStorageItem = function (variable) {
    localStorage.removeItem(variable);
};

/** Change Password */
$('#changePasswordForm').on('submit', function (event) {
    event.preventDefault();
    let loadingButton = jQuery(this).find('#btnEditSave');

    loadingButton.button('loading');
    $.ajax({
        url: route('change-password'),
        type: 'POST',
        data: new FormData($(this)[0]),
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                displayToastr(Lang.get('messages.new_keys.success'), 'success', result.message);
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }
        },
        error: function (result) {
            displayToastr(Lang.get('messages.new_keys.error'), 'error', result.responseJSON.message);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

window.avoidSpace = function (event) {
    var k = event ? event.which : window.event.keyCode;
    if (k === 32) {
        event.stopPropagation();
        return false;
    }
};

window.resetModalForm = function (formId, validationBox) {
    $(formId)[0].reset();
    $(validationBox).hide();
};

window.deleteItemAjax = function (url, tableId, header, callFunction = null) {
    $.ajax({
        url: url,
        type: 'DELETE',
        dataType: 'json',
        success: function (obj) {
            if (obj.success) {
                if ($(tableId).DataTable().data().count() == 1) {
                    $(tableId).DataTable().page('previous').draw('page')
                } else {
                    $(tableId).DataTable().ajax.reload(null, false)
                }
            }
            displayToastr(Lang.get('messages.new_keys.success'), 'success',
                header + ' ' + Lang.get('messages.new_keys.has_been_deleted'))
        },
        error: function (obj) {
            displayToastr(Lang.get('messages.new_keys.error'), 'error', obj.responseJSON.message)
        },
    });
};

window.notificationSound = function () {
    let sound = document.getElementById("notificationSound");
    sound.currentTime = 0;
    sound.play();
}
$(document).ready(function () {
    $('[data-toggle="password"]').each(function () {
        var input = $(this);
        var eye_btn = $(this).parent().find('.input-icon');
        eye_btn.css('cursor', 'pointer').addClass('input-password-hide');
        eye_btn.on('click', function () {
            if (eye_btn.hasClass('input-password-hide')) {
                eye_btn.removeClass('input-password-hide').addClass('input-password-show');
                eye_btn.find('.fa').removeClass('fa-eye-slash').addClass('fa-eye')
                input.attr('type', 'text');
            } else {
                eye_btn.removeClass('input-password-show').addClass('input-password-hide');
                eye_btn.find('.fa').removeClass('fa-eye').addClass('fa-eye-slash')
                input.attr('type', 'password');
            }
        });
    });
});
