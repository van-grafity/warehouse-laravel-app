@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Row List </h3>

                <div class="ml-auto p-3">
                    @can('manage')
                        <a href="javascript:void(0)" class="btn btn-success " id="btn_modal_create" onclick="show_modal_create('modal_location_row')">Create</a>
                    @endcan
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="mb-3 text-right">
                    <button id="reload_table_btn" class="btn btn-sm btn-info">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <table id="location_row_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="250">Row</th>
                            <th width="">Description</th>
                            <th width="150">Action</th>
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

<!-- Modal Add and Edit Row -->
<div class="modal fade" id="modal_location_row" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">
                <input type="hidden" name="edit_location_row_id" value="" id="edit_location_row_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add New Row</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="row" class="col-form-label">Row</label>
                        <input type="text" class="form-control" id="row" name="row" required>
                    </div>
                 <div class="form-group">
                        <label for="description" class="col-form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" cols="30" rows="2" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-submit" onclick="submitForm('modal_location_row')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Add and Edit Row -->
@endsection

@section('js')
<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const column_visible = '{{ $can_manage }}';
    
    // ## URL List
    const show_url = "{{ route('location-row.show',':id') }}";
    const store_url = "{{ route('location-row.store') }}";
    const update_url = "{{ route('location-row.update',':id') }}";
    const delete_url = "{{ route('location-row.destroy',':id') }}";
    const dtable_url = "{{ route('location-row.dtable') }}";

    const show_modal_create = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Add New Row",
            btn_submit : "Save",
            form_action_url : store_url,
        }
        clear_form(modal_data);
        $(`#${modal_element_id}`).modal('show');
    }

    const show_modal_edit = async (modal_element_id, location_row_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Edit Row",
            btn_submit : "Save",
            form_action_url : update_url.replace(':id',location_row_id),
        }
        clear_form(modal_data);
        
        fetch_data = {
            url: show_url.replace(':id',location_row_id),
            method: "GET",
            token: token,
        }
        result = await using_fetch(fetch_data);
        row_data = result.data.row

        $('#row').val(row_data.row);
        $('#description').val(row_data.description);
        $('#edit_location_row_id').val(row_data.id);
        
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

            if(!formData.edit_location_row_id) {
                // ## kalau tidak ada row id berarti STORE dan Method nya POST
                fetch_data = {
                    url: store_url,
                    method: "POST",
                    data: formData,
                    token: token,
                }
            } else {
                // ## kalau ada row id berarti UPDATE dan Method nya PUT
                fetch_data = {
                    url: update_url.replace(':id',formData.edit_location_row_id),
                    method: "PUT",
                    data: formData,
                    token: token,
                }
            }

            const response = await using_fetch(fetch_data);
            if(response.status == 'success') {
                swal_info({ title: response.message })
                
                reload_dtable();
            } else {
                swal_failed({ title: response.message })
            }

            submit_btn.removeAttribute('disabled');

        } catch (error) {
            console.error("Error:", error);
        }

        $(`#${modal_id}`).modal('hide');
    }

    const show_modal_delete = async (location_row_id) => {
        swal_data = {
            title: "Are you Sure?",
            text: "Want to delete the row",
            icon: "warning",
            confirmButton: "Delete",
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary"
        };
        let confirm_delete = await swal_confirm(swal_data);
        if(!confirm_delete) { return false; };

        fetch_data = {
            url: delete_url.replace(':id', location_row_id),
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
    let location_row_table = $('#location_row_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#location_row_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#location_row_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
            },
        },
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'row', name: 'row'},
            { data: 'description', name: 'description'},
            { data: 'action', name: 'action', visible: column_visible},
        ],
        columnDefs: [
            { targets: [0,-1], orderable: false, searchable: false },
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
        $(this).addClass('loading').attr('disabled',true);
        location_row_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

    let validator = $('#modal_location_row form').validate({
        errorElement: "span",
        errorPlacement: function (error, element) {
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
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });


</script>
@stop