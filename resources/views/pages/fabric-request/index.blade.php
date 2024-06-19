@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Fabric Request List </h3>
                
                <div class="ml-auto p-3">
                    <button id="sync_fabric_request_btn" type="button" class="btn btn-default mr-2" onclick="show_modal('modal_sync_fabric_request')">
                        Sync Fabric Request
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="mb-3 text-right">
                    <button id="reload_table_btn" class="btn btn-sm btn-info">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <table id="farbic_request_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr class="">
                            <th width="30">No</th>
                            <th width="" class="text-center">Serial Number</th>
                            <th width="50">GL</th>
                            <th width="">Color</th>
                            <th width="">Table</th>
                            <th width="">Qty Required</th>
                            <th width="">Requested at</th>
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


<!-- Modal Syncronize Fabric Request -->
<div class="modal fade" id="modal_sync_fabric_request" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Syncronize Fabric Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="gl_number" class="col-form-label">GL Number</label>
                        <input type="text" class="form-control" id="gl_number" name="gl_number" placeholder="63789-00" required>
                    </div>
                    <div class="form-group">
                        <label for="date_filter" class="mb-0 align-self-center col-form-label" style="width:150px;">Date Filter</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control daterangepicker-select" id="date_filter" name="date_filter" autocomplete="off" placeholder="Incoming Date Filter">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button id="sync_fabric_request_btn" type="button" class="btn btn-primary mr-2" onclick="sync_fbr_with_cutting_app(this)">
                        <i class="fas fa-sync-alt mr-1"></i> Sync Fabric Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Syncronize Fabric Request -->

@endsection

@section('js')

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const cutting_app_api_baseurl = "{{ config('api.cutting_app.base_url') }}";
    const cutting_app_api_token = "{{ config('api.cutting_app.token') }}";
    
    // ## URL List
    const dtable_url = "{{ route('fabric-request.dtable') }}";
    const sync_url = "{{ route('fabric-request.sync') }}";

    // ## Page Variable
    let start_date_filter = moment().format('YYYY-MM-DD');
    let end_date_filter = moment().format('YYYY-MM-DD');


    const show_modal = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Syncronize Fabric Request",
            btn_submit : "Save",
            form_action_url : '',
        }
        clear_form(modal_data);
        default_daterangepicker();
        $(`#${modal_element_id}`).modal('show')
    }

    const default_daterangepicker = () => {
        start_date_filter = moment().format('YYYY-MM-DD');
        end_date_filter = moment().format('YYYY-MM-DD');
        $('#date_filter').data('daterangepicker').setStartDate(moment());
        $('#date_filter').data('daterangepicker').setEndDate(moment());
    }

    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');
    }
</script>

<script type="text/javascript">
    let farbic_request_table = $('#farbic_request_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#farbic_request_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#farbic_request_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
                $('[data-toggle="tooltip"]').tooltip();
            },
        },
        order: [
            [6, 'desc'],
            [2, 'asc'],
            [3, 'asc'],
            [4, 'asc'],
        ],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'serial_number', name: 'serial_number', className: 'text-left'},
            { data: 'fbr_gl_number', name: 'fbr_gl_number'},
            { data: 'fbr_color', name: 'fbr_color'},
            { data: 'fbr_table_number', name: 'fbr_table_number'},
            { data: 'fbr_qty_required', name: 'fbr_qty_required'},
            { data: 'fbr_requested_at', name: 'fbr_requested_at', visible: false },
            { data: 'action', name: 'action'},
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
        farbic_request_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

    $('#date_filter').daterangepicker({
        maxDate: moment(),
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
            'Last 2 Weeks': [moment().subtract(13, 'days'), moment()],
        },
        maxSpan: {
            "days": 13
        },
        
    }, function(start, end, label) {
        start_date_filter = start.format('YYYY-MM-DD');
        end_date_filter = end.format('YYYY-MM-DD');
    });

    $('#date_filter').on('apply.daterangepicker', function(ev, picker) {
        start_date_filter = picker.startDate.format('YYYY-MM-DD');
        end_date_filter = picker.endDate.format('YYYY-MM-DD');
    });
    $('#date_filter').on('cancel.daterangepicker', function(ev, picker) {
        start_date_filter = '';
        end_date_filter = '';
        $(this).val('');
    });
</script>

<script type="text/javascript">

    const sync_fbr_with_cutting_app = async (e) => {
        if(!$('#date_filter').val()) {
            toastr.error("Please enter Date")
            return false;
        }
        $(e).addClass('loading').attr('disabled',true);

        let response = await using_axios({
            url: `${cutting_app_api_baseurl}/fabric-request-sync/get-fabric-request`,
            method: 'GET',
            data: {
                gl_number : $('#gl_number').val(),
                start_date : start_date_filter,
                end_date : end_date_filter,
            },
            token : cutting_app_api_token,
        })

        if ([401,404].includes(response.status)) {
            $(e).removeClass('loading').attr('disabled',false);
            swal_failed({
                title : response.message,
            });
            return false;
        }

        let fetch_data = {
            url: sync_url,
            method: "POST",
            data: response.data,
            token: token,
        }

        try {
            let response_sync = await using_fetch(fetch_data);
            $(e).removeClass('loading').attr('disabled',false);

            if(response_sync.status != 'success') {
                swal_failed({
                    title : "Something went wrong",
                });
                return false;
            }
            
            swal_info({'title': response_sync.message, timer: 3000});
            reload_dtable();
            $(e).closest('.modal').modal('hide');

        } catch (error) {
            $(e).removeClass('loading').attr('disabled',false);
            swal_failed({
                title : "Something went wrong",
                text : "Try to decrease range of date filter",
            });
            return false;
        }
    }
</script>
@stop