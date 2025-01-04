/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/*!*************************************************************!*\
  !*** ./resources/assets/js/admin/roles/create_edit_role.js ***!
  \*************************************************************/


$('#createRoleForm').on('submit', function (event) {
  event.preventDefault();
  var name = $('#role_name').val();
  var emptyName = name.trim().replace(/ \r\n\t/g, '') === '';
  if (emptyName) {
    displayToastr(Lang.get('messages.new_keys.error'), 'error', Lang.get('messages.new_keys.name_field_is_not_contain_white_space'));
    return;
  }
  var loadingButton = jQuery(this).find('#btnCreateRole');
  loadingButton.button('loading');
  $('#createRoleForm')[0].submit();
  return true;
});
$('#editRoleForm').on('submit', function (event) {
  event.preventDefault();
  var editName = $('#edit_role_name').val();
  var emptyEditName = editName.trim().replace(/ \r\n\t/g, '') === '';
  if (emptyEditName) {
    displayToastr(Lang.get('messages.new_keys.error'), 'error', Lang.get('messages.new_keys.name_field_is_not_contain_white_space'));
    return;
  }
  var loadingButton = jQuery(this).find('#btnEditSave');
  loadingButton.button('loading');
  $('#editRoleForm')[0].submit();
  return true;
});
/******/ })()
;