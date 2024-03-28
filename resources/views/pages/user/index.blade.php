@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3 my-auto"> User List </h3>

                    <div class="ml-auto p-3">
                        <a href="javascript:void(0)" class="btn btn-success " id="btn_modal_create" onclick="show_modal_create('modal_user')">Create</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row  mb-3">
                        <div class="col-sm-12 d-inline-flex justify-content-end">
                            <div class="filter_wrapper mr-2" style="width:200px;">
                                <select name="data_status" id="data_status" class="form-control select2 no-search-box">
                                    <option value="">All Data</option>
                                    <option value="1" selected> Active Only </option>
                                    <option value="2"> Deleted Only </option>
                                </select>
                            </div>
                            <div class="filter_wrapper text-right align-self-center">
                                <button id="reload_table_btn" class="btn btn-sm btn-info">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <table id="user_table" class="table table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th width="25">No</th>
                                <th width="" class="text-center">Name</th>
                                <th width="" class="text-center">Email</th>
                                <th width="">Department</th>
                                <th width="">Role</th>
                                <th width="">Created Date</th>
                                <th width="250">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </div>
        <!-- /.col -->
    </div>

</div>

<!-- Modal Add and Edit User -->
<div class="modal fade" id="modal_user" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">
                <input type="hidden" name="edit_user_id" value="" id="edit_user_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="col-form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-control select2" id="department" name="department" style="width: 100%;" data-placeholder="Choose Department">
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->department }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control select2 validate-on-change" id="role" name="role" style="width: 100%;" data-placeholder="Choose Role" required>
                            @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-submit" onclick="submitForm('modal_user')">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Add and Edit User -->

@endsection

@section('js')
<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ## URL List
    const show_url = "{{ route('user.show',':id') }}";
    const store_url = "{{ route('user.store') }}";
    const update_url = "{{ route('user.update',':id') }}";
    const delete_url = "{{ route('user.destroy',':id') }}";
    const reset_password_url = "{{ route('user.reset-password',':id') }}";
    const restore_url = "{{ route('user.restore',':id') }}";
    const dtable_url = "{{ route('user.dtable') }}";

    const show_modal_create = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Add New User",
            btn_submit : "Add User",
            form_action_url : store_url,
        }
        clear_form(modal_data);
        $(`#${modal_element_id}`).modal('show');
    }

    const show_modal_edit = async (modal_element_id, user_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Edit User",
            btn_submit : "Save",
            form_action_url : update_url.replace(':id', user_id),
        }
        clear_form(modal_data);
        
        fetch_data = {
            url: show_url.replace(':id', user_id),
            method: "GET",
            token: token,
        }
        result = await using_fetch(fetch_data);
        user_data = result.data.user

        $('#name').val(user_data.name);
        $('#email').val(user_data.email);
        $('#department').val(user_data.department_id).trigger('change');
        $('#role').val(user_data.role).trigger('change');
        $('#edit_user_id').val(user_data.id);
        
        $(`#${modal_element_id}`).modal('show');
    }

    const submitForm = async (modal_id) => {
        try {
            let modal = document.getElementById(modal_id);
            let submit_btn = modal.querySelector('.btn-submit');
            submit_btn.setAttribute('disabled', 'disabled');

            let form = modal.querySelector('form');
            let formData = getFormData(form);

            if (!$(form).valid()) {
                submit_btn.removeAttribute('disabled');
                return false;
            }

            if (!formData.edit_user_id) {
                // ## kalau tidak ada user id berarti STORE dan Method nya POST
                fetch_data = {
                    url: store_url,
                    method: "POST",
                    data: formData,
                    token: token,
                }
            } else {
                // ## kalau ada user id berarti UPDATE dan Method nya PUT
                fetch_data = {
                    url: update_url.replace(':id', formData.edit_user_id),
                    method: "PUT",
                    data: formData,
                    token: token,
                }
            }

            const response = await using_fetch(fetch_data);
            if (response.status == 'success') {
                swal_info({ title: response.message })
                
                reload_dtable();
            } else {
                swal_failed({ title: response.message, text: ' ' })
            }

            submit_btn.removeAttribute('disabled');

        } catch (error) {
            console.error("Error:", error);
        }

        $(`#${modal_id}`).modal('hide');
    }

    const show_modal_reset_password = async (user_id) => {
        swal_data = {
            title: "Reset Password?",
            text: "This account password will be changed to default",
            icon: "warning",
            confirmButton: "Reset",
            confirmButtonClass: "btn-primary",
            cancelButtonClass: "btn-secondary"
        };
        let confirm_delete = await swal_confirm(swal_data);
        if (!confirm_delete) {
            return false;
        };

        fetch_data = {
            url: reset_password_url.replace(':id', user_id),
            method: "GET",
            token: token,
        }
        result = await using_fetch(fetch_data);

        if (result.status == "success") {
            swal_info({ title: result.message, });

            reload_dtable();
        } else {
            swal_failed({ title: result.message });
        }
    }

    const show_modal_delete = async (user_id) => {
        swal_data = {
            title: "Are you Sure?",
            text: "Want to delete the user",
            icon: "warning",
            confirmButton: "Delete",
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary"
        };
        let confirm_delete = await swal_confirm(swal_data);
        if (!confirm_delete) {
            return false;
        };

        fetch_data = {
            url: delete_url.replace(':id', user_id),
            method: "DELETE",
            token: token,
        }
        result = await using_fetch(fetch_data);

        if (result.status == "success") {
            swal_info({ title: result.message });

            reload_dtable();
        } else {
            swal_failed({ title: result.message });
        }
    }

    const show_modal_restore = async (user_id) => {
        swal_data = {
            title: "Want to Restore this User?",
            text: "This user will remove from user deleted list",
            icon: "warning",
            confirmButton: "Restore",
            confirmButtonClass: "btn-info",
            cancelButtonClass: "btn-secondary"
        };
        let confirm_delete = await swal_confirm(swal_data);
        if(!confirm_delete) { return false; };

        fetch_data = {
            url: restore_url.replace(':id', user_id),
            method: "GET",
            token: token,
        }
        result = await using_fetch(fetch_data);

        if(result.status == "success"){
            swal_info({
                title : result.message,
            });

            reload_dtable();
            
        } else {
            swal_failed({ title: result.message });
        }
    }

    const show_modal_delete_permanent = async (user_id) => {
        swal_data = {
            title: "Permanently Delete?",
            text: "User will be permanently deleted",
            icon: "warning",
            confirmButton: "Delete",
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary"
        };
        let confirm_delete = await swal_confirm(swal_data);
        if(!confirm_delete) { return false; };

        fetch_data = {
            url: delete_url.replace(':id', user_id),
            method: "DELETE",
            token: token,
        }
        result = await using_fetch(fetch_data);

        if(result.status == "success"){
            swal_info({
                title : result.message,
            });

            reload_dtable();
            
        } else {
            swal_failed({ title: result.message });
        }
    }

    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');
    }
</script>

<script type="text/javascript">
    let user_table = $('#user_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            data: function (d) {
                d.data_status = $('#data_status').val();
            },
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#user_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#user_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
            },
        },
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'name', name: 'name', className:'text-left'},
            { data: 'email', name: 'email', className:'text-left'},
            { data: 'department', name: 'department' },
            { data: 'role', name: 'role' },
            { data: 'created_date', name: 'created_date' },
            { data: 'action', name: 'action' },
        ],
        columnDefs: [
            { targets: [0, -1], orderable: false, searchable: false }, 
        ],
        paging: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        autoWidth: false,
        orderCellsTop: true,
        searchDelay: 500,
    })

    $('#reload_table_btn').on('click', function(event) {
        $(this).addClass('loading').attr('disabled', true);
        user_table.ajax.reload(function(json) {
            $('#reload_table_btn').removeClass('loading').attr('disabled', false);
        });
    });

    let validator = $('#modal_user form').validate({
        errorElement: "span",
        errorPlacement: function(error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);

            // ## khusus untuk select2
            if (element.hasClass('select2-hidden-accessible')) {
                error.insertAfter(element.next('span.select2-container'));
            }

            // ## validasi error pada select2
            if (!$(element).val()) {
                $(element).parent().find('.select2-container').addClass('select2-container--error');
            }
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });

    $('#department.select2 , #role.select2').select2({
        dropdownParent: $('#modal_user'),
    });

    $('#data_status').change(function(event) {
        reload_dtable();
    });
</script>
@stop