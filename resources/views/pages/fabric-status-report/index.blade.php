@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="date_filter" class="mb-0 align-self-center col-form-label">Date Filter</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar"></i></span>
                        </div>
                        <input type="text" class="form-control daterangepicker-select" id="date_filter" name="date_filter" autocomplete="off" placeholder="Date Filter">
                    </div>
                    <input type="hidden" id="date_start_filter" name="date_start_filter">
                    <input type="hidden" id="date_end_filter" name="date_end_filter">
                </div>
            </div>
            <div class="ml-auto p-3">
                <a href="javascript:void(0)" class="btn btn-info mb-2 mr-2" id="btn_preview_report">Preview</a>
                <a href="javascript:void(0)" class="btn btn-primary mb-2 mr-2" id="btn_print_report">Print PDF</a>
                <a href="javascript:void(0)" class="btn btn-primary mb-2 mr-2" id="btn_download_excel">Download Excel</a>
            </div>
        </div>
    </div>
    <div class="col-12" id="preview_card">
        <div class="card">
            <div class="card-body">
                <table id="fabric_status_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr class="">
                            <th width="">No</th>
                            <th width="">Arival Date</th>
                            <th width="">Supplier</th>
                            <th width="">Invoice</th>
                            <th width="">Buyer</th>
                            <th width="">Container</th>
                            <th width="">Batch</th>
                            <th width="">GL Number</th>
                            <th width="">PO</th>
                            <th width="">Style No</th>
                            <th width="">Fabric Content</th>
                            <th width="">Color</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');   

    // ## URL List
    const dtable_preview_url = "{{ route('fabric-status-report.dtable-preview') }}";
    const url = "{{ route('fabric-status-report.print') }}";
    const download_excel_url = "{{ route('fabric-status-report.export-excel') }}";
    
</script>

<script type="text/javascript">
    // ## Page Variable

    $('#date_filter').daterangepicker({
        opens: 'left',
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel: 'Clear'
        },
        startDate: moment().subtract(1, 'month').startOf('month'),
        endDate: moment().endOf('month'),
        alwaysShowCalendars: true,
        showCustomRangeLabel: false,
        ranges: {
            'Today': [moment(), moment()],
            'Current Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().endOf('month')],
            'Last 3 Month': [moment().subtract(3, 'month').startOf('month'), moment().endOf('month')],
        },
        
    }, function(start, end, label) {
        $('#date_start_filter').val(start.format('YYYY-MM-DD'));
        $('#date_end_filter').val(end.format('YYYY-MM-DD'));
    });

    $('#date_start_filter').val(moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD'))
    $('#date_end_filter').val(moment().add(1, 'month').endOf('month').format('YYYY-MM-DD'))
    $('#date_filter').on('apply.daterangepicker', function(ev, picker) {

    });
    $('#date_filter').on('cancel.daterangepicker', function(ev, picker) {
        $('#date_start_filter').val('');
        $('#date_end_filter').val('');
        $('#date_filter').val('');
        reload_dtable();
    });

    $(document).ready(function(){
        $('#gl_filter, #color_filter').select2();
        
        $('#btn_print_report').click(function(){

            var date_start_filter = $('#date_start_filter').val();
            var date_end_filter = $('#date_end_filter').val();

            window.open(url + '?gl_number=' + gl_filter + '&color_name=' + color_filter + '&start_date=' + date_start_filter + '&end_date=' + date_end_filter, '_blank');
        });

        $('#btn_download_excel').click(function(){
            let data_filter = {
                date_start_filter : $('#date_start_filter').val(),
                date_end_filter : $('#date_end_filter').val(),
            }
            let queryString = new URLSearchParams(data_filter).toString();
            download_excel_filter_url = `${download_excel_url}?${queryString}`;
            window.location.href = download_excel_filter_url;
        });
    });
</script>

<script type="text/javascript">
    $('#preview_card').hide();
    
    let fabric_status_table = $('#fabric_status_table').DataTable({
        processing: true,
        serverSide: true,
        deferLoading: 0,
        ajax: {
            url: dtable_preview_url,
            data: function (d) {
                d.invoice_filter = $('#invoice_filter').val();
                d.date_start_filter = $('#date_start_filter').val();
                d.date_end_filter = $('#date_end_filter').val();
            },
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
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'incoming_date', name: 'incoming_date'},
            { data: 'supplier', name: 'supplier'},
            { data: 'invoice_number', name: 'invoice_number'},
            { data: 'buyer', name: 'buyer'},
            { data: 'container_number', name: 'container_number'},
            { data: 'batch_number', name: 'batch_number'},
            { data: 'gl_number', name: 'gl_number'},
            { data: 'po_number', name: 'po_number'},
            { data: 'style', name: 'style'},
            { data: 'fabric_content', name: 'fabric_content'},
            { data: 'color', name: 'color'},
        ],
        columnDefs: [
            { targets: [0,1,2,3,4,5,6,7,8,9,10,11], orderable: false, searchable: false },
        ],
        orderable: false,
        paging: false,
        responsive: true,
        lengthChange: true,
        searching: false,
        autoWidth: false,
        searchDelay: 500,
    });

    $('#btn_preview_report').on('click', function(event) {
        $('#preview_card').show();
        $(this).addClass('loading').attr('disabled',true);
        fabric_status_table.ajax.reload(function(json){
            $('#btn_preview_report').removeClass('loading').attr('disabled',false);
        });
    });

</script>
@endpush