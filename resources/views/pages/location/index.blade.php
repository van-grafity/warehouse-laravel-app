@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Location List </h3>

                <div class="ml-auto p-3">
                    @can('manage')
                        <a href="javascript:void(0)" class="btn btn-success " id="btn_modal_create" onclick="show_modal_create('modal_location')">Create</a>
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
                <table id="location_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="250">Location</th>
                            <th width="">Description</th>
                            <th width="">Row</th>
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

<!-- Modal Add and Edit Location -->
<div class="modal fade" id="modal_location" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">
                <input type="hidden" name="edit_location_id" value="" id="edit_location_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add New Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="location" class="col-form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" cols="30" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="location_row">Row</label>
                        <select name="location_row" id="location_row" class="form-control select2 validate-on-change" data-placeholder="Select Row" required>
                            @foreach ($location_rows as $location_row)
                                <option value="{{ $location_row->id }}"> {{ $location_row->row }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-submit" onclick="submitForm('modal_location')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Add and Edit Location -->
@endsection

@section('js')
<script type="text/javascript">

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const column_visible = '{{ $can_manage }}';
    
    // ## URL List
    const show_url = "{{ route('location.show',':id') }}";
    const store_url = "{{ route('location.store') }}";
    const update_url = "{{ route('location.update',':id') }}";
    const delete_url = "{{ route('location.destroy',':id') }}";
    const dtable_url = "{{ route('location.dtable') }}";

    const show_modal_create = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Add New Location",
            btn_submit : "Save",
            form_action_url : store_url,
        }
        clear_form(modal_data);
        $(`#${modal_element_id}`).modal('show');
    }

    const show_modal_edit = async (modal_element_id, location_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Edit Location",
            btn_submit : "Save",
            form_action_url : update_url.replace(':id',location_id),
        }
        clear_form(modal_data);
        
        fetch_data = {
            url: show_url.replace(':id',location_id),
            method: "GET",
            token: token,
        }
        result = await using_fetch(fetch_data);
        location_data = result.data.location

        $('#location').val(location_data.location);
        $('#description').val(location_data.description);
        $('#location_row').val(location_data.location_row_id).trigger('change');
        $('#edit_location_id').val(location_data.id);
        
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

            if(!formData.edit_location_id) {
                // ## kalau tidak ada location id berarti STORE dan Method nya POST
                fetch_data = {
                    url: store_url,
                    method: "POST",
                    data: formData,
                    token: token,
                }
            } else {
                // ## kalau ada location id berarti UPDATE dan Method nya PUT
                fetch_data = {
                    url: update_url.replace(':id',formData.edit_location_id),
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

    const show_modal_delete = async (location_id) => {
        swal_data = {
            title: "Are you Sure?",
            text: "Want to delete the location",
            icon: "warning",
            confirmButton: "Delete",
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary"
        };
        let confirm_delete = await swal_confirm(swal_data);
        if(!confirm_delete) { return false; };

        fetch_data = {
            url: delete_url.replace(':id', location_id),
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
    let location_table = $('#location_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#location_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#location_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
            },
        },
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'location', name: 'location'},
            { data: 'description', name: 'description'},
            { data: 'location_row', name: 'location_row'},
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
        location_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

    
const getValidationRules = () => {
        return {
            location: {
                required: true,
            },
            description: {
                required: true,
            },
            location_row: {
                required: true,
            },
        };
    }
    const getValidationMessages = () => {
        return {
            location: {
                required: "Please enter Location",
            },
            description: {
                required: "Please enter Description",
            },
            location_row: {
                required: "Please select Location Row",
            },
        };
    }
    // ## Form Validator
    let validator = $('#modal_location form').validate({
        rules: getValidationRules(),
        messages: getValidationMessages(),
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

    $('#location_row.select2').select2({
        dropdownParent: $('#modal_location'),
    });

</script>
@stop