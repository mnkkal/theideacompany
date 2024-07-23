$(document).ready(function () {
    let roleTable = $('#roles-table').DataTable({
        processing: true,
        serverSide: true,
        'order': [[0, 'asc']],
        ajax: {
            url: route('roles.index'),
        },
        columnDefs: [
            {
                'targets': [2],
                'orderable': false,
                'className': 'text-center',
                'width': '7%',
            },
            {
                'targets': [1],
                'orderable': false,
            },
        ],
        columns: [
            {
                data: function (data) {
                    let role_name = data.name;
                    return htmlSpecialCharsDecode(role_name);
                },
                name: 'name',
            },
            {
                data:function (row){
                    let html = '<div class="d-flex flex-wrap">';
                    if (row.permissions.length > 0) {
                        $.each(row.permissions, function (i, v){
                            html += '<span class="bg-regent-opacity-3 font-size-3 p-1 mr-4 mb-1 permission-lh">'+ v.display_name +'</span>';
                        });
                    }else {
                        html += '<span class="bg-regent-opacity-3 font-size-3 p-1 mr-4 mb-1 permission-lh">N/A</span>';
                    }
                    html += '</div>';

                    return html;
                },
                name: 'permissions.display_name',
            },
            {
                data: function (row) {
                    if (row.is_default) {
                        return '';
                    }
                   
                    return `<div class="d-flex justify-content-center align-items-center"> <a title="Edit" class="index__btn btn btn-ghost-success btn-sm edit-btn mr-1" href="${route('roles.edit',row.id)}">
                            <i class="cui-pencil action-icon"></i></a>
                            <button title="Delete" class="index__btn btn btn-ghost-danger btn-sm delete-btn" data-id="${row.id}"><i class="cui-trash action-icon"></i></button>
                            <div>
                            `;
                }, name: 'id',
            },
        ],
    });


    const swalDelete = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-danger mr-2 btn-lg',
            cancelButton: 'btn btn-secondary btn-lg',
        },
        buttonsStyling: false,
    });

// open delete confirmation model
    $(document).on('click', '.delete-btn', function (event) {
        let roleId = $(this).data('id');
        swalDelete.fire({
            title: Lang.get('messages.placeholder.are_you_sure'),
            html: Lang.get('messages.placeholder.delete_this_role'),
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: Lang.get('messages.new_keys.cancle'),
            confirmButtonText: Lang.get('messages.chats.delete'),
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: route('roles.destroy',roleId),
                    type: 'DELETE',
                    dataType: 'json',
                    success: function (obj) {
                        displayToastr(
                            Lang.get('messages.new_keys.success'), 'success', Lang.get('messages.new_keys.role_deleted'),
                        );
                        if (obj.success) {
                            if ($('#roles-table').DataTable().data().count() == 1) {
                                $('#roles-table').DataTable().page('previous').draw('page');
                            } else {
                                $('#roles-table').DataTable().ajax.reload(null, false);
                            }
                        }
                    },
                    error: function (data) {
                        displayToastr(Lang.get('messages.new_keys.error'), 'error',
                            data.responseJSON.message);
                    },
                });
            }
        });
    });
});
