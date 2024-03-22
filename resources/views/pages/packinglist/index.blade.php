@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Packinglist </h3>

                <div class="ml-auto p-3">
                    @can('manage')
                        <a href="javascript:void(0)" class="btn btn-success " id="btn_modal_create" onclick="show_modal_create('modal_packinglist')">Create</a>
                    @endcan
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-12 d-inline-flex justify-content-end">
                        <div class="filter_wrapper mr-2 align-self-center d-inline-flex" style="width:350px;">
                            <label for="incoming_date_filter" class="mb-0 align-self-center col-form-label col-form-label-sm" style="width:150px;">Incoming Date</label>
                            
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control daterangepicker-select" id="incoming_date_filter" name="incoming_date_filter" autocomplete="off" placeholder="Incoming Date Filter">
                            </div>

                            <input type="hidden" id="incoming_date_start_filter" name="incoming_date_start_filter">
                            <input type="hidden" id="incoming_date_end_filter" name="incoming_date_end_filter">
                        </div>



                        <div class="filter_wrapper text-right align-self-center">
                            <button id="reload_table_btn" class="btn btn-sm btn-info"> 
                                <i class="fas fa-sync-alt"></i> 
                            </button>
                        </div>
                    </div>
                </div>
                <table id="packinglist_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th width="25">No</th>
                            <th width="">Packinglist No</th>
                            <th width="">Invoice</th>
                            <th width="">Buyer</th>
                            <th width="">GL Number</th>
                            <th width="">Color</th>
                            <th width="">Batch</th>
                            <th width="">Roll Qty</th>
                            <th width="100">Action</th>
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

<!-- Modal Add and Edit Product Detail -->
<div class="modal fade" id="modal_packinglist" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">
                <input type="hidden" name="edit_packinglist_id" value="" id="edit_packinglist_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add New Packinglist</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="packinglist_number" class="col-form-label">Packinglist Number</label>
                        <input type="text" class="form-control" id="packinglist_number" name="packinglist_number" required>
                    </div>
                    <div class="form-group">
                        <label for="container_number" class="col-form-label">Container Number</label>
                        <input type="text" class="form-control" id="container_number" name="container_number" required>
                    </div>
                    <div class="form-group">
                        <label for="incoming_date" class="col-form-label">Incoming Date</label>
                        <input type="text" class="form-control" id="incoming_date" name="incoming_date" required autocomplete="off">
                        <input type="hidden" id="incoming_date_input" name="incoming_date_input">
                    </div>
                    <div class="form-group">
                        <label for="supplier">Supplier</label>
                        <select name="supplier" id="supplier" class="form-control select2 validate-on-change" data-placeholder="Select Supplier" required>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"> {{ $supplier->supplier }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-submit" onclick="submitForm('modal_packinglist')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Add and Edit Product Detail -->
@endsection

@section('js')
<script type="text/javascript">

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const column_visible = '{{ $can_manage }}';
    
    // ## URL List
    const show_url = "{{ route('packinglist.show',':id') }}";
    const store_url = "{{ route('packinglist.store') }}";
    const update_url = "{{ route('packinglist.update',':id') }}";
    const delete_url = "{{ route('packinglist.destroy',':id') }}";
    const dtable_url = "{{ route('packinglist.dtable') }}";

    const show_modal_create = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Add New Packinglist",
            btn_submit : "Save",
            form_action_url : store_url,
        }
        clear_form(modal_data);
        $(`#${modal_element_id}`).modal('show');
    }

    const show_modal_edit = async (modal_element_id, packinglist_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Edit Packinglist",
            btn_submit : "Save",
            form_action_url : update_url.replace(':id',packinglist_id),
        }
        clear_form(modal_data);
        
        fetch_data = {
            url: show_url.replace(':id',packinglist_id),
            method: "GET",
            token: token,
        }
        result = await using_fetch(fetch_data);
        packinglist_data = result.data.packinglist

        $('#packinglist_number').val(packinglist_data.packinglist_number);
        $('#container_number').val(packinglist_data.container_number);
        $('#incoming_date').val(moment(packinglist_data.incoming_date,'YYYY-MM-DD').format('DD/MM/YYYY'));
        $('#incoming_date_input').val(packinglist_data.incoming_date);
        $('#supplier').val(packinglist_data.supplier_id).trigger('change');
        $('#edit_packinglist_id').val(packinglist_data.id);
        
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

            if(!formData.edit_packinglist_id) {
                // ## kalau tidak ada packinglist id berarti STORE dan Method nya POST
                fetch_data = {
                    url: store_url,
                    method: "POST",
                    data: formData,
                    token: token,
                }
            } else {
                // ## kalau ada packinglist id berarti UPDATE dan Method nya PUT
                fetch_data = {
                    url: update_url.replace(':id',formData.edit_packinglist_id),
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

    const show_modal_delete = async (packinglist_id) => {
        swal_data = {
            title: "Are you Sure?",
            text: "Want to delete the packinglist",
            icon: "warning",
            confirmButton: "Delete",
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary"
        };
        let confirm_delete = await swal_confirm(swal_data);
        if(!confirm_delete) { return false; };

        fetch_data = {
            url: delete_url.replace(':id',packinglist_id),
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

    const getValidationRules = () => {
        return {
            packinglist_number: {
                required: true,
            },
            container_number: {
                required: true,
            },
            incoming_date: {
                required: true,
            },
            supplier: {
                required: true,
            },
        };
    }
    const getValidationMessages = () => {
        return {
            packinglist_number: {
                required: "Please enter Packinglist Number",
            },
            container_number: {
                required: "Please enter Container Number",
            },
            incoming_date: {
                required: "Please enter Incoming Date",
            },
            supplier: {
                required: "Please enter Supplier",
            },
        };
    }

</script>

<script type="text/javascript">
    let packinglist_table = $('#packinglist_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            data: function (d) {
                d.incoming_date_start_filter = $('#incoming_date_start_filter').val();
                d.incoming_date_end_filter = $('#incoming_date_end_filter').val();
            },
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#packinglist_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#packinglist_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
            },
        },
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'serial_number', name: 'serial_number'},
            { data: 'invoice', name: 'invoice'},
            { data: 'buyer', name: 'buyer'},
            { data: 'gl_number', name: 'gl_number'},
            { data: 'color', name: 'color'},
            { data: 'batch_number', name: 'batch_number'},
            { data: 'roll_qty', name: 'roll_qty'},
            { data: 'action', name: 'action', visible: column_visible },
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
        packinglist_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

    
    // ## Daterange picker
    $('#incoming_date').daterangepicker({
        singleDatePicker: true,
        autoApply: true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    }, function(start, end, label) {
        $('#incoming_date_input').val(start.format('YYYY-MM-DD'))
    });

    $('#incoming_date').on('show.daterangepicker', function(ev, picker) {
        $('#incoming_date_input').val(moment().format('YYYY-MM-DD'))
    });

    $('#incoming_date_filter').daterangepicker({
        // autoUpdateInput: false,
        opens: 'left',
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel: 'Clear'
        },
        startDate: moment().startOf('month'),
        endDate: moment().add(1, 'month').endOf('month'),
        alwaysShowCalendars: true,
        showCustomRangeLabel: false,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
        },
        
    }, function(start, end, label) {
        $('#incoming_date_start_filter').val(start.format('YYYY-MM-DD'));
        $('#incoming_date_end_filter').val(end.format('YYYY-MM-DD'));
    });

    $('#incoming_date_start_filter').val(moment().startOf('month').format('YYYY-MM-DD'))
    $('#incoming_date_end_filter').val(moment().add(1, 'month').endOf('month').format('YYYY-MM-DD'))

    $('#incoming_date_filter').on('apply.daterangepicker', function(ev, picker) {
        // $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        reload_dtable();
    });
    $('#incoming_date_filter').on('cancel.daterangepicker', function(ev, picker) {
        $('#incoming_date_start_filter').val('');
        $('#incoming_date_end_filter').val('');
        $('#incoming_date_filter').val('');
        reload_dtable();
    });


    // ## Form Validator
    let validator = $('#modal_packinglist form').validate({
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

    $('#supplier.select2').select2({
        dropdownParent: $('#modal_packinglist'),
    });

    reload_dtable();
</script>
@stop