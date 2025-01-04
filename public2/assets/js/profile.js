/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/*!****************************************!*\
  !*** ./resources/assets/js/profile.js ***!
  \****************************************/


$('#editProfileForm').on('submit', function (event) {
  if (!validateName() || !validateEmail() || !validatePhone()) {
    return false;
  }
  event.preventDefault();
  var loadingButton = jQuery(this).find('#btnEditSave');
  loadingButton.button('loading');
  $.ajax({
    url: route('update.profile'),
    type: 'post',
    data: new FormData($(this)[0]),
    processData: false,
    contentType: false,
    success: function success(result) {
      if (result.success) {
        displayToastr(Lang.get('messages.new_keys.success'), 'success', result.message);
        setTimeout(function () {
          location.reload();
        }, 2000);
      }
    },
    error: function error(result) {
      displayToastr(Lang.get('messages.new_keys.error'), 'error', result.responseJSON.message);
      setTimeout(function () {
        location.reload();
      }, 2000);
    },
    complete: function complete() {
      loadingButton.button('reset');
    }
  });
});
$(":checkbox:not('.not-checkbox')").iCheck({
  checkboxClass: 'icheckbox_square-green',
  radioClass: 'iradio_square',
  increaseArea: '20%' // optional
});
$('#upload-photo').on('change', function () {
  readURL(this);
});
var on = $('#btnCancelEdit').on('click', function () {
  $('#editProfileForm').trigger('reset');
});

// profile js
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#upload-photo-img').attr('src', e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
  }
}
window.printErrorMessage = function (selector, errorResult) {
  $(selector).show().html('');
  $(selector).text(errorResult.responseJSON.message);
};

//validations
var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
window.validateName = function () {
  var name = $('#user-name').val();
  if (name === '') {
    displayToastr(Lang.get('messages.new_keys.error'), 'error', Lang.get('messages.new_keys.name_required'));
    return false;
  }
  return true;
};
window.validateEmail = function () {
  var email = $('#email').val();
  if (email === '') {
    displayToastr(Lang.get('messages.new_keys.error'), 'error', Lang.get('messages.new_keys.email_required'));
    return false;
  } else if (!validateEmailFormat(email)) {
    displayToastr(Lang.get('messages.new_keys.error'), 'error', Lang.get('messages.new_keys.enter_valid_email'));
    return false;
  }
  return true;
};
window.validateEmailFormat = function (email) {
  return emailReg.test(email);
};
window.validatePhone = function () {
  var phone = $('#phone').val();
  if (phone !== '' && phone.length !== 10) {
    displayToastr(Lang.get('messages.new_keys.error'), 'error', Lang.get('messages.new_keys.enter_valid_phoneno'));
    return false;
  }
  return true;
};
$('#phone').on('keypress keyup blur', function (e) {
  $(this).val($(this).val().replace(/[^\d].+/, ''));
  if (e.which !== 8 && e.which !== 0 && (e.which < 48 || e.which > 57)) {
    e.preventDefault();
    return false;
  }
});
var swalDelete = Swal.mixin({
  customClass: {
    confirmButton: 'btn btn-danger mr-2 btn-lg',
    cancelButton: 'btn btn-secondary btn-lg'
  },
  buttonsStyling: false
});
$('.remove-profile-img').on('click', function (e) {
  e.preventDefault();
  swalDelete.fire({
    title: Lang.get('messages.are_you_sure'),
    html: Lang.get('messages.new_keys.remove_profile_image'),
    icon: 'warning',
    showCancelButton: true,
    cancelButtonText: Lang.get('messages.new_keys.cancle'),
    confirmButtonText: Lang.get('messages.new_keys.yes_remove_it')
  }).then(function (result) {
    if (result.value) {
      $.ajax({
        type: 'DELETE',
        url: route('remove-profile-image'),
        success: function success(result) {
          displayToastr(Lang.get('messages.new_keys.success'), 'success', result.message);
          setTimeout(function () {
            location.reload();
          }, 2000);
        },
        error: function error(_error) {
          displayToastr(Lang.get('messages.new_keys.error'), 'error', _error.message);
        }
      });
    }
  });
});
$(document).on('click', '.changeLanguage', function () {
  var languageName = $(this).data('prefix-value');
  $.ajax({
    type: 'POST',
    url: route('update-language'),
    data: {
      languageName: languageName
    },
    success: function success() {
      location.reload();
    }
  });
});
$('#changePasswordModal').on('hidden.bs.modal', function () {
  resetModalForm('#changePasswordForm', '#validationErrorsBox');
});
/******/ })()
;