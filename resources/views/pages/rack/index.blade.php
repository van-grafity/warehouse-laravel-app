@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Rack List </h3>

                <div class="ml-auto p-3">
                    @can('manage')
                        <a href="javascript:void(0)" class="btn btn-success " id="btn_modal_create" onclick="show_modal_create('modal_rack')">Create</a>
                    @endcan
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row  mb-3">
                    <div class="col-sm-12 d-inline-flex justify-content-end">
                        <div class="action-wrapper mr-auto">
                            @can('print')
                                <button class="btn btn-primary" disabled="disabled" onclick="print_barcode_btn()">
                                    <i class="fas fa-print"></i> Print Barcode
                                </button>
                            @endcan
                        </div>
                        <div class="filter_wrapper mr-2" style="width:200px;">
                            <select name="rack_type_filter" id="rack_type_filter" class="form-control select2 no-search-box">
                                <option value="" selected>All Data</option>
                                <option value="moveable"> Moveable </option>
                                <option value="fixed"> Fixed </option>
                            </select>
                        </div>
                        <div class="filter_wrapper text-right align-self-center">
                            <button id="reload_table_btn" class="btn btn-sm btn-info">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <table id="rack_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr class="">
                            <th width="30">
                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input 
                                            id="print_checkbox_all" 
                                            class="custom-control-input checkbox-all-control" 
                                            type="checkbox"
                                        >
                                        <label for="print_checkbox_all" class="custom-control-label"></label>
                                    </div>
                                </div>
                            </th>
                            <th width="">No</th>
                            <th width="250">Serial Number</th>
                            <th width="">Basic Number</th>
                            <th width="">Type</th>
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

<!-- Modal Add and Edit Rack -->
<div class="modal fade" id="modal_rack" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">
                <input type="hidden" name="edit_rack_id" value="" id="edit_rack_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add New Rack</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="basic_number" class="col-form-label">Basic Number</label>
                        <input type="number" class="form-control" id="basic_number" name="basic_number" required>
                    </div>
                    <div class="form-group">
                        <label for="rack_type">Row</label>
                        <select name="rack_type" id="rack_type" class="form-control select2 no-search-box" data-placeholder="Select Rack Type" required>
                            <option value="moveable"> Moveable </option>
                            <option value="fixed"> Fixed </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" cols="30" rows="2" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-submit" onclick="submitForm('modal_rack')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Add and Edit Rack -->
@endsection

@section('js')
<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const column_visible = '{{ $can_manage }}';
    const column_checkbox = '{{ $can_print }}';

    // ## URL List
    const show_url = "{{ route('rack.show',':id') }}";
    const store_url = "{{ route('rack.store') }}";
    const update_url = "{{ route('rack.update',':id') }}";
    const delete_url = "{{ route('rack.destroy',':id') }}";
    const dtable_url = "{{ route('rack.dtable') }}";
    const print_barcode_url = "{{ route('rack.print-barcode') }}";

    const show_modal_create = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Add New Rack",
            btn_submit : "Save",
            form_action_url : store_url,
        }
        clear_form(modal_data);
        $(`#${modal_element_id}`).modal('show');
    }

    const show_modal_edit = async (modal_element_id, rack_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Edit Rack",
            btn_submit : "Save",
            form_action_url : update_url.replace(':id',rack_id),
        }
        clear_form(modal_data);
        
        fetch_data = {
            url: show_url.replace(':id',rack_id),
            method: "GET",
            token: token,
        }
        result = await using_fetch(fetch_data);
        rack_data = result.data.rack

        $('#basic_number').val(rack_data.basic_number);
        $('#rack_type').val(rack_data.rack_type).trigger('change');
        $('#description').val(rack_data.description);
        $('#edit_rack_id').val(rack_data.id);
        
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

            if(!formData.edit_rack_id) {
                // ## kalau tidak ada rack id berarti STORE dan Method nya POST
                fetch_data = {
                    url: store_url,
                    method: "POST",
                    data: formData,
                    token: token,
                }
            } else {
                // ## kalau ada rack id berarti UPDATE dan Method nya PUT
                fetch_data = {
                    url: update_url.replace(':id',formData.edit_rack_id),
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

    const show_modal_delete = async (rack_id) => {
        swal_data = {
            title: "Are you Sure?",
            text: "Want to delete the rack",
            icon: "warning",
            confirmButton: "Delete",
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary"
        };
        let confirm_delete = await swal_confirm(swal_data);
        if(!confirm_delete) { return false; };

        fetch_data = {
            url: delete_url.replace(':id', rack_id),
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
    let rack_table = $('#rack_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            data: function (d) {
                d.rack_type_filter = $('#rack_type_filter').val();
            },
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#rack_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#rack_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
            },
        },
        order: [],
        columns: [
            { data: 'checkbox', name: 'checkbox', visible: column_checkbox},
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'serial_number', name: 'serial_number'},
            { data: 'basic_number', name: 'basic_number'},
            { data: 'rack_type', name: 'rack_type'},
            { data: 'description', name: 'description'},
            { data: 'action', name: 'action', visible: column_visible},
        ],
        columnDefs: [
            { targets: [1,0,-1], orderable: false, searchable: false },
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
        rack_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

    let validator = $('#modal_rack form').validate({
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

    $('#rack_type_filter').change(function(event) {
        reload_dtable();
    });    
</script>

<script type="text/javascript">

    const is_all_checked = () => {
        let all_print_checkbox = document.getElementsByClassName('checkbox-print-control');
        if(all_print_checkbox.length <= 0) { return false; }
        for (let item of all_print_checkbox) {
            if(!item.checked) { return false; }
        }
        return true;
    }

    const is_any_checked = () => {
        let all_print_checkbox = document.getElementsByClassName('checkbox-print-control');
        for (let item of all_print_checkbox) {
            if(item.checked) { return true; }
        }
        return false;
    }

    // ## checkbox listener for always update print_checkbox_all
    const checkbox_clicked = () => {
        let checked_status_checkbox_all = is_all_checked() ? true : false;
        document.getElementById('print_checkbox_all').checked = checked_status_checkbox_all;

        let disabled_status_action_wrapper = is_any_checked() ? false : true;
        disabled_action_wrapper(disabled_status_action_wrapper);
    }

    const disabled_action_wrapper = (disabled_status = false) => {
        let action_wrapper = document.getElementsByClassName('action-wrapper').item(0);
        let buttons = action_wrapper.querySelectorAll('button');
        buttons.forEach(function(button) {
            button.disabled = disabled_status;
        });
    }

    const get_selected_item = () => {
        let selected_element = $('.checkbox-print-control:checked').toArray();
        let selected_item_value = [];

        selected_element.forEach(element => {
            selected_item_value.push($(element).val());
        });
        return selected_item_value;
    }

    const print_barcode_btn = async () => {
        let selected_print = get_selected_item();

        if(selected_print.length > 0) {
            // window.open("{{ route('rack.print-barcode') }}?id=" + selected_print, '_blank');
            window.open(print_barcode_url + "?id=" + selected_print, '_blank');
        } else {
            Swal.fire({
                title: 'Error',
                text: 'Please select at least one rack',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    };
</script>

<script>
    // ## Checkbox Feature
    $('.checkbox-all-control').on('click', function(e) {
        let is_checked = $(this).prop('checked');
        let table = $(this).parents('table');
        table.find('.checkbox-print-control').prop('checked',is_checked);
    })

    $('#print_checkbox_all').on('change', function(e) {
        checkbox_clicked();
    })

</script>
@stop