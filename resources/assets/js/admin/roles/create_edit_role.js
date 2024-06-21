
$('#createRoleForm').on('submit', function (event) {
    event.preventDefault();
    let name = $('#role_name').val();
    let emptyName = name.trim().replace(/ \r\n\t/g, '') === '';

    if (emptyName) {
        displayToastr(Lang.get('messages.new_keys.error'), 'error',  Lang.get('messages.new_keys.name_field_is_not_contain_white_space'));
        return
    }

    let loadingButton = jQuery(this).find('#btnCreateRole');
    loadingButton.button('loading');

    $('#createRoleForm')[0].submit();

    return true;
});

$('#editRoleForm').on('submit', function (event) {
    event.preventDefault();
    let editName = $('#edit_role_name').val();
    let emptyEditName = editName.trim().replace(/ \r\n\t/g, '') === '';

    if (emptyEditName) {
        displayToastr(Lang.get('messages.new_keys.error'), 'error', Lang.get('messages.new_keys.name_field_is_not_contain_white_space'));
        return
    }

    let loadingButton = jQuery(this).find('#btnEditSave');
    loadingButton.button('loading');

    $('#editRoleForm')[0].submit();

    return true;
});
