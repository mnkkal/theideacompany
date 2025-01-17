/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/*!*************************************************!*\
  !*** ./resources/assets/js/admin/users/user.js ***!
  \*************************************************/


$(document).ready(function () {
  $('#filter_user').select2({
    minimumResultsForSearch: -1
  });
  $('#privacy_filter').select2({
    minimumResultsForSearch: -1
  });
  var tbl = $('#users_table').DataTable({
    processing: true,
    serverSide: true,
    'bStateSave': true,
    'order': [[1, 'asc']],
    ajax: {
      url: route('users.index'),
      data: function data(_data) {
        _data.filter_user = $('#filter_user').find('option:selected').val();
        _data.privacy_filter = $('#privacy_filter').find('option:selected').val();
      }
    },
    columnDefs: [{
      'targets': [0]
    }, {
      'targets': [3, 4, 5, 6],
      'orderable': false,
      'className': 'text-center',
      'width': '10%'
    }],
    columns: [{
      data: function data(row) {
        return "<div class=\"d-flex align-items-center\"> <div class=\"symbol symbol-circle symbol-50px overflow-hidden mr-3\">  <div> <img src=\"".concat(row.photo_url, "\" alt=\"User Image\" class=\"user-avatar-img\"> </div> </div> <div class=\"d-flex flex-column\"> <a href=\"javascript:void(0)\" class=\"mb-1 user-name-data\">").concat(htmlSpecialCharsDecode(row.name), "</a> <span class=\"user-email-data\">").concat(row.email, "</span> </div> </div> ");
      },
      name: 'name'
    }, {
      data: function data(_data2) {
        var role_name = getRoleName(_data2.roles);
        return htmlSpecialCharsDecode(role_name);
      },
      name: 'name'
    }, {
      data: function data(_data3) {
        return _data3.privacy ? '<span class="public-badge py-1 px-2">' + Lang.get('messages.group.public') + '</span>' : '<span class="private-badge py-1 px-2">' + Lang.get('messages.group.private') + '</span>';
      },
      name: 'privacy',
      'searchable': false
    }, {
      data: function data(row) {
        var checked = row.email_verified_at == null ? '' : 'checked disabled';
        return ' <label class="switch switch-label switch-outline-primary-alt align-middle">' + '<input name="email_verified" data-id="' + row.id + '" class="switch-input email-verified" type="checkbox" ' + checked + '>' + '<span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>' + '</label>';
      },
      name: 'id'
    }, {
      data: function data(row) {
        var checked = row.is_active == 0 ? '' : 'checked';
        return ' <label class="switch switch-label switch-outline-primary-alt align-middle">' + '<input name="is_active" data-id="' + row.id + '" class="switch-input is-active" type="checkbox" value="1" ' + checked + '>' + '<span class="switch-slider" data-checked="&#x2713;" data-unchecked="&#x2715;"></span>' + '</label>';
      },
      name: 'id'
    }, {
      data: function data(row) {
        return '<a title="" href="' + route("user-impersonate-login", row.id) + '" class="btn btn-primary btn-sm">' + Lang.get('messages.impersonate') + '</a>';
      },
      name: 'id'
    }, {
      data: function data(row) {
        var helpers = {
          isArchive: isArchive
        };
        var template = $.templates('#tmplAddChatUsersList');
        return template.render(row, helpers);
      },
      name: 'id'
    }],
    drawCallback: function drawCallback() {
      this.api().state.clear();
    },
    'fnInitComplete': function fnInitComplete() {
      $('#filter_user').change(function () {
        tbl.ajax.reload();
      });
      $('#privacy_filter').change(function () {
        tbl.ajax.reload();
      });
    }
  });
  window.isArchive = function (deletedAt) {
    return deletedAt != null ? 1 : 0;
  };
  window.getRoleName = function (roles) {
    var roleName = '';
    $.each(roles, function (index, val) {
      roleName = val.name;
      return false;
    });
    return roleName;
  };
  $('#createUserForm').on('submit', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#createBtnSave');
    loadingButton.button('loading');
    $.ajax({
      url: route('users.store'),
      type: 'post',
      data: new FormData($(this)[0]),
      processData: false,
      contentType: false,
      success: function success(result) {
        if (result.success) {
          displayToastr(Lang.get('messages.new_keys.success'), 'success', result.message);
          $('#create_user_modal').modal('hide');
          $('#users_table').DataTable().ajax.reload(null, false);
        }
      },
      error: function error(result) {
        displayToastr(Lang.get('messages.new_keys.error'), 'error', result.responseJSON.message);
      },
      complete: function complete() {
        loadingButton.button('reset');
      }
    });
  });
  $('#editUserForm').on('submit', function (event) {
    event.preventDefault();
    var loadingButton = jQuery(this).find('#editBtnSave');
    loadingButton.button('loading');
    var id = $('#edit_user_id').val();
    $.ajax({
      url: route('user.update', id),
      type: 'post',
      data: new FormData($(this)[0]),
      processData: false,
      contentType: false,
      success: function success(result) {
        if (result.success) {
          displayToastr(Lang.get('messages.new_keys.success'), 'success', result.message);
          $('#edit_user_modal').modal('hide');
          $('#users_table').DataTable().ajax.reload(null, false);
        }
      },
      error: function error(result) {
        displayToastr(Lang.get('messages.new_keys.error'), 'error', result.responseJSON.message);
      },
      complete: function complete() {
        loadingButton.button('reset');
      }
    });
  });
  $(document).on('click', '.edit-btn', function () {
    var userId = $(this).data('id');
    renderData(route('users.edit', userId));
  });
  window.renderData = function (url) {
    $.ajax({
      url: url,
      type: 'GET',
      success: function success(result) {
        if (result.success) {
          var user = result.data.user;
          var permissions = result.data.permissions;

          // Populate user details in the modal
          $('#edit_user_id').val(user.id);
          $('#edit_name').val(htmlSpecialCharsDecode(user.name));
          $('#edit_email').val(user.email);
          $('#edit_phone').val(user.phone);
          $('#edit_is_active').val(user.is_active);
          $('#edit_role_id').val(user.roles[0].id);
          $('#edit_upload-photo-img').attr('src', user.photo_url);
          $('#edit_about').val(htmlSpecialCharsDecode(user.about));
          $('#edit_user_modal').modal('show');

          // Set gender radio buttons
          if (user.gender == 1) {
            $('#edit_male').prop('checked', true);
          }
          if (user.gender == 2) {
            $('#edit_female').prop('checked', true);
          }

          // Set privacy radio buttons
          if (user.privacy == 1) {
            $('#editPrivacyPublic').prop('checked', true);
          } else {
            $('#editPrivacyPrivate').prop('checked', true);
          }

          // Clear existing permissions
          $('#permissionsContainer').empty();

          // Populate permissions with checkboxes
          permissions.forEach(function (permission) {
            var isChecked = user.permissions.some(function (userPermission) {
              return userPermission.name === permission.name;
            }) ? 'checked' : '';
            var permissionHtml = "\n                            <div class=\"form-group col-sm-6 login-group__sub-title\">\n                                <input type=\"checkbox\" name=\"permissions[]\" value=\"".concat(permission.name, "\" ").concat(isChecked, " id=\"permission_").concat(permission.id, "\">\n                                <label for=\"permission_").concat(permission.id, "\">").concat(permission.display_name, "</label>\n                            </div>\n                        ");
            $('#permissionsContainer').append(permissionHtml);
          });
        }
      },
      error: function error(_error) {
        // Display an error message if the request fails
        displayToastr(Lang.get('messages.new_keys.error'), 'error', _error.responseJSON.message);
      }
    });
  };

  // Helper function to decode HTML special characters
  function htmlSpecialCharsDecode(str) {
    var textArea = document.createElement('textarea');
    textArea.innerHTML = str;
    return textArea.value;
  }
  var swalDelete = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-danger mr-2 btn-lg',
      cancelButton: 'btn btn-secondary btn-lg'
    },
    buttonsStyling: false
  });

  // open delete confirmation model
  $(document).on('click', '.delete-btn', function (event) {
    var userId = $(this).data('id');
    deleteItem(route('users.destroy', userId), '#users_table', Lang.get('messages.placeholder.user'));
  });
  function deleteItem(url, tableId, header) {
    var callFunction = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
    swalDelete.fire({
      title: Lang.get('messages.placeholder.are_you_sure'),
      html: Lang.get('messages.placeholder.want_to_delete_this') + '"' + header + '"' + ' ?',
      icon: 'warning',
      showCancelButton: true,
      cancelButtonText: Lang.get('messages.new_keys.cancle'),
      confirmButtonText: Lang.get('messages.chats.delete'),
      input: 'text',
      inputPlaceholder: Lang.get('messages.placeholder.write_delete_user'),
      inputValidator: function inputValidator(value) {
        if (value !== "delete") {
          return Lang.get('messages.placeholder.need_to_write_delete');
        }
      }
    }).then(function (result) {
      if (result.value) {
        deleteItemAjax(url, tableId, header, callFunction = null);
      }
    });
  }
  $(document).on('click', '.archive-btn', function () {
    var userId = $(this).data('id');
    archiveItem(route('archive-user', userId), '#users_table', Lang.get('messages.placeholder.user'));
  });
  function archiveItem(url, tableId, header) {
    var callFunction = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
    swalDelete.fire({
      title: Lang.get('messages.placeholder.are_you_sure'),
      input: 'text',
      inputPlaceholder: Lang.get('messages.placeholder.confirm_archive'),
      html: Lang.get('messages.placeholder.want_to_archive') + ' "' + header + '" ' + Lang.get('messages.placeholder.after_archive'),
      icon: 'warning',
      showCancelButton: true,
      cancelButtonText: Lang.get('messages.new_keys.cancle'),
      confirmButtonText: Lang.get('messages.new_keys.archive'),
      inputValidator: function inputValidator(value) {
        if (value !== "archive") {
          return Lang.get('messages.placeholder.you_need_to');
        }
      }
    }).then(function (result) {
      if (result.value) {
        archiveItemAjax(url, tableId, header, callFunction = null);
      }
    });
  }
  window.archiveItemAjax = function (url, tableId, header) {
    var callFunction = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
    $.ajax({
      url: url,
      type: 'DELETE',
      dataType: 'json',
      success: function success(obj) {
        if (obj.success) {
          $(tableId).DataTable().ajax.reload(null, false);
        }
        displayToastr(Lang.get('messages.new_keys.success'), 'success', obj.message);
      },
      error: function error(data) {
        displayToastr(Lang.get('messages.new_keys.error'), 'error', data.responseJSON.message);
      }
    });
  };
  $(document).on('click', '.restore-btn', function (event) {
    var userId = $(this).data('id');
    restoreItem(route('user.restore-user'), '#users_table', Lang.get('messages.placeholder.user'), userId);
  });
  function restoreItem(url, tableId, header, userId) {
    swal.fire({
      title: Lang.get('messages.placeholder.are_you_sure'),
      html: Lang.get('messages.placeholder.want_to_restore') + '"' + header + '"?',
      icon: 'warning',
      showCancelButton: true,
      cancelButtonText: Lang.get('messages.new_keys.cancle'),
      confirmButtonText: Lang.get('messages.new_keys.restore')
    }).then(function (result) {
      if (result.value) {
        restoreItemAjax(url, tableId, header, userId);
      }
    });
  }
  window.restoreItemAjax = function (url, tableId, header, userId) {
    $.ajax({
      url: url,
      type: 'POST',
      data: {
        'id': userId
      },
      dataType: 'json',
      success: function success(obj) {
        if (obj.success) {
          $(tableId).DataTable().ajax.reload(null, false);
        }
        displayToastr(Lang.get('messages.new_keys.success'), 'success', header + Lang.get('messages.new_keys.has_been_restored'));
      },
      error: function error(data) {
        displayToastr(Lang.get('messages.new_keys.error'), 'error', data.responseJSON.message);
      }
    });
  };
  $('#create_user_modal').on('hidden.bs.modal', function () {
    resetModalForm('#createUserForm', '#validationErrorsBox');
    $('#upload-photo-img').attr('src', defaultImageAvatar);
  });
  $('#edit_user_modal').on('hidden.bs.modal', function () {
    resetModalForm('#editUserForm', '#editValidationErrorsBox');
  });
  function resetModalForm(formId, validationBox) {
    $(formId)[0].reset();
    $(validationBox).hide();
  }
  function printErrorMessage(selector, errorMessage) {
    $(selector).show().html('');
    $(selector).append('<div>' + errorMessage + '</div>');
  }

  // listen user activation deactivation change event
  $(document).on('change', '.is-active', function (event) {
    var userId = $(event.currentTarget).data('id');
    activeDeActiveUser(userId);
  });

  // activate de-activate user
  window.activeDeActiveUser = function (id) {
    $.ajax({
      url: route('active-de-active-user', id),
      method: 'post',
      cache: false,
      success: function success(result) {
        if (result.success) {
          displayToastr(Lang.get('messages.new_keys.success'), 'success', result.message);
          $('#users_table').DataTable().ajax.reload(null, false);
        }
      }
    });
  };

  // Email verified
  $(document).on('change', '.email-verified', function (event) {
    var userId = $(event.currentTarget).data('id');
    $.ajax({
      url: route('user.email-verified', userId),
      method: 'post',
      cache: false,
      success: function success(result) {
        if (result.success) {
          displayToastr(Lang.get('messages.new_keys.success'), 'success', result.message);
          $('#users_table').DataTable().ajax.reload(null, false);
        }
      }
    });
  });
  window.validatePasswordConfirmation = function () {
    var passwordConfirmation = $('#confirm_password').val();
    if (passwordConfirmation === '') {
      displayToastr(Lang.get('messages.new_keys.error'), 'error', Lang.get('messages.new_keys.password_confirmation_required'));
      return false;
    }
    return true;
  };
  window.validateMatchPasswords = function () {
    var passwordConfirmation = $('#confirm_password').val();
    var password = $('#password').val();
    if (passwordConfirmation !== password) {
      displayToastr(Lang.get('messages.new_keys.error'), 'error', Lang.get('messages.new_keys.password_and_password_confirmation_did_not_match'));
      return false;
    }
    return true;
  };
  window.validatePassword = function () {
    var password = $('#password').val();
    if (password === '') {
      displayToastr(Lang.get('messages.new_keys.error'), 'error', __('messages.new_keys.password_field_required'));
      return false;
    }
    return true;
  };
});
/******/ })()
;