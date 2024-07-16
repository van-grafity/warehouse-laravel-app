@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Packing List </h3>

                <div class="ml-auto p-3">
                    @can('manage')
                        <button type="button" class="btn btn-default mr-2" onclick="show_import_modal()"><i class="fas fa-upload"></i>Upload Packing List</button>
                        <a href="javascript:void(0)" class="btn btn-success " id="btn_modal_create" onclick="show_modal_create('modal_packinglist')">Create</a>
                    @endcan
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-12 d-inline-flex justify-content-end">
                        <div class="filter_wrapper mr-2" style="width:200px; height:10px">                         
                           <select name="gl_filter" id="gl_filter" class="form-control select2">
                            <option value="" selected>All GL Number</option>    
                            @foreach ($packinglist as $packinglist)
                            <option value="{{$packinglist->gl_number}}" >{{$packinglist->gl_number}}</option>    
                            @endforeach
                        </select>
                       </div>
                       <div class="filter_wrapper mr-2" style="width:200px;">
                           <select name="color_filter" id="color_filter" class="form-control select2">
                               <option value="" selected >All Color</option>
                           </select>
                       </div>
                       <div class="filter_wrapper mr-2" style="width:200px;">                         
                           <select name="invoice_filter" id="invoice_filter" class="form-control select2">
                               <option value="" selected>All Invoice</option>
                                   <option value=""></option>    
                               <option value=""></option>  
                           </select>
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
                            <th width="" class="text-center">Packing List No</th>
                            <th width="">Invoice</th>
                            <th width="">Buyer</th>
                            <th width="50">GL</th>
                            <th width="">Color</th>
                            <th width="">Batch</th>
                            <th width="">Roll Qty</th>
                            <th width="125">Action</th>
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

<!-- Modal Add and Edit Packinglist -->
<div class="modal fade" id="modal_packinglist" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">
                <input type="hidden" name="edit_packinglist_id" value="" id="edit_packinglist_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add New Packing List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="invoice" class="col-form-label">Invoice</label>
                                <select name="invoice" id="invoice" class="form-control select2" required>
                                    <option value=""> Select Invoice </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="buyer" class="col-form-label">Buyer</label>
                                <input type="text" class="form-control" id="buyer" name="buyer" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="gl_number" class="col-form-label">GL Number</label>
                                <input type="text" class="form-control" id="gl_number" name="gl_number" required>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="po_number" class="col-form-label">PO Number</label>
                                <input type="text" class="form-control" id="po_number" name="po_number" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="color" class="col-form-label">Color</label>
                                <select name="color" id="color" class="form-control select2" required>
                                    <option value=""> Select Color </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="batch_number" class="col-form-label">Batch</label>
                                <input type="text" class="form-control" id="batch_number" name="batch_number" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="style" class="col-form-label">Style</label>
                        <textarea class="form-control" name="style" id="style" cols="30" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="fabric_content" class="col-form-label">Fabric Content</label>
                        <textarea class="form-control" name="fabric_content" id="fabric_content" cols="30" rows="3"></textarea>
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
<!-- End Modal Add and Edit Packinglist -->


<!-- Modal Import Purchase Order -->
<div class="modal fade" id="modal_import_excel" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('packinglist.import') }}" method="post" id="import_form" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add <?= $title ? $title : '' ?> via Import Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="file_excel">Upload <?= $title ? $title : '' ?> on Excel Format</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="file_excel" name="file_excel">
                                        <label class="custom-file-label" for="file_excel">Choose Excel file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success ld-ext-right" id="btn_submit_form">
                        Upload <?= $title ? $title : '' ?>
                        <div class="ld ld-ring ld-spin"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Import Purchase Order -->
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
    const fetch_select_invoice_url = "{{ route('fetch-select.invoice') }}";
    const fetch_select_color_url = "{{ route('fetch-select.color') }}";

    const show_modal_create = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Add New Packing List",
            btn_submit : "Save",
            form_action_url : store_url,
        }
        clear_form(modal_data);
        $(`#${modal_element_id}`).modal('show');
    }

    const show_modal_edit = async (modal_element_id, packinglist_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Edit Packing List",
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

        $('#buyer').val(packinglist_data.buyer);
        $('#gl_number').val(packinglist_data.gl_number);
        $('#po_number').val(packinglist_data.po_number);
        $('#batch_number').val(packinglist_data.batch_number);
        $('#style').val(packinglist_data.style);
        $('#fabric_content').val(packinglist_data.fabric_content);
        $('#edit_packinglist_id').val(packinglist_data.id);

        // ## Kasus spesial untuk memberikan data pada select2 yang datanya diambil menggunakan ajax ( serverside )
        let preselect_color = {
            select2_selector: '#color.select2',
            select2_url: fetch_select_color_url,
            option_id: packinglist_data.color_id,
        };
        preselecting_option(preselect_color);

        let preselect_invoice = {
            select2_selector: '#invoice.select2',
            select2_url: fetch_select_invoice_url,
            option_id: packinglist_data.invoice_id,
        };
        await preselecting_option(preselect_invoice);
        /*
         * NOTE :
         * karena proses preselecting ini menggunakan async maka tunggu sampai data selesai terpilih baru modal di munculkan. 
         * karena kalau tidak ada await nya maka modal akan langsung muncil meski preselecting belum selesai di load.
        */

        $(`#${modal_element_id}`).modal('show');
    }

    const preselecting_option = async ({ select2_selector, select2_url, option_id }) => {
        let select2_element = $(select2_selector);

        params_data = { id : option_id };
        fetch_data = {
            url: select2_url,
            method: "GET",
            data: params_data,
        }
        fetch_result = await using_fetch(fetch_data);
        select_data = fetch_result.data.items;
        
        let option = new Option(select_data.text, select_data.id, true, true);
        select2_element.append(option).trigger('change');
    }

    const submitForm = async (modal_id) => {
        let modal = document.getElementById(modal_id);
        let submit_btn = modal.querySelector('.btn-submit');
        submit_btn.setAttribute('disabled', 'disabled');
        
        let form = modal.querySelector('form');
        let formData = getFormData(form);

        try {

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

            $(`#${modal_id}`).modal('hide');
            
        } catch (error) {
            console.error("Error:", error);
        }

        submit_btn.removeAttribute('disabled');
    }

    const show_modal_delete = async (packinglist_id) => {
        swal_data = {
            title: "Are you Sure?",
            text: "Want to delete the packing list",
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

<script>
    // ## JS for Modal Import
    const show_import_modal = () => {
        $('#modal_import_excel').modal('show');
    }

    $(document).ready(function() {
        bsCustomFileInput.init();
    })

    let import_validator = $("#import_form").validate({
        rules: {
            file_excel: {
                required: true,
                extension: "xlsx|xls|csv"
            },
        },
        messages: {
            file_excel: {
                required: "Please enter the File",
                extension: "Please provide Excel or CSV files",
            }
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });

    // ## Avoid double submit
    $('#import_form').on('submit', function (event) {
        $(this).find('button:submit').addClass('running').prop("disabled", true);
        if(!$(this).valid()){
            $(this).find('button:submit').removeClass('running').prop("disabled", false);
        }
    })

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
                d.gl_filter = $('#gl_filter').val();
                d.color_filter = $('#color_filter').val();
                d.invoice_filter = $('#invoice_filter').val();
            },
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#packinglist_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#packinglist_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
                $('[data-toggle="tooltip"]').tooltip();
            },
        },
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'serial_number', name: 'serial_number', className: 'text-left'},
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

    $('#invoice.select2').select2({
        dropdownParent: $('#modal_packinglist'),
        ajax: {
            url: fetch_select_invoice_url,
            dataType: 'json',
            delay: 500,
            data: function (params) {
                var query = {
                    search: params.term,
                }
                return query;
            },
            processResults: function (fetch_result) {
                return {
                    results: fetch_result.data.items,
                };
            },
        }
    });

    $('#color.select2').select2({
        dropdownParent: $('#modal_packinglist'),
        ajax: {
            url: fetch_select_color_url,
            dataType: 'json',
            delay: 500,
            data: function (params) {
                var query = {
                    search: params.term,
                }
                return query;
            },
            processResults: function (fetch_result) {
                return {
                    results: fetch_result.data.items,
                };
            },
        }
    });

    reload_dtable();

    
    $('#color_filter.select2').select2({
        ajax: {
            url: fetch_select_color_url,
            dataType: 'json',
            delay: 500,
            data: function (params) {
                var query = {
                    search: params.term || '',
                }
                return query;
            },
            processResults: function (fetch_result, params) {
                if (!params.term) {
                    fetch_result.data.items.unshift({
                        id: '',
                        text: 'All Color'
                    });
                }
                return {
                    results: fetch_result.data.items,
                };
            },
        }
    });

    $('#color_filter').change(function(event) {
        reload_dtable();
    });

    $('#invoice_filter.select2').select2({
        ajax: {
            url: fetch_select_invoice_url,
            dataType: 'json',
            delay: 500,
            data: function (params) {
                var query = {
                    search: params.term || '',
                }
                return query;
            },
            processResults: function (fetch_result, params) {
                if (!params.term) {
                    fetch_result.data.items.unshift({
                        id: '',
                        text: 'All Invoice'
                    });
                }
                return {
                    results: fetch_result.data.items,
                };
            },
        }
    });

    $('#invoice_filter').change(function(event) {
        reload_dtable();
    });

    $('#gl_filter').select2({}).change(function(event) {
        reload_dtable();
    }); 
</script>
@stop