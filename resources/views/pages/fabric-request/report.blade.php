@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #ff8000;
        border: 1px solid #ff8000;
        color: #fff;
        padding: 0 10px;
        height: 1.75rem;
        margin-top: .30rem;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255,255,255,.7);
        float: right;
        margin-left: 5px;
        margin-right: -2px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff;
    }

    .select2-container--default .select2-selection--multiple {
        border-radius: 0;
        border-color: #006fe6;
        min-height: 38px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #006fe6;
        box-shadow: none;
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="gl_filter">Gl Number</label>
                    <select name="gl_filter" id="gl_filter" class="form-control select2">
                        <option value="" selected> Select GL Number </option>    
                        @foreach ($gl_numbers as $gl_numbers)
                            <option value="{{$gl_numbers->fbr_gl_number}}" >{{$gl_numbers->fbr_gl_number}}</option>    
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="color_filter">Color</label>
                    <select name="color_filter" id="color_filter" class="form-control select2">
                        <option value="" selected> Select Color </option>    
                        @foreach ($colors as $colors)
                            <option value="{{$colors->fbr_color}}" >{{$colors->fbr_color}}</option>    
                        @endforeach
                    </select>
                </div>
                    <div class="form-group">
                    <label for="date_filter" class="mb-0 align-self-center col-form-label">Date Filter</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar"></i></span>
                        </div>
                        <input type="text" class="form-control daterangepicker-select" id="date_filter" name="date_filter" autocomplete="off" placeholder="Fabric Request Date Filter">
                    </div>
                    <input type="hidden" id="date_start_filter" name="date_start_filter">
                    <input type="hidden" id="date_end_filter" name="date_end_filter">
                </div>
            </div>
            <div class="ml-auto p-3">
                <a href="javascript:void(0)" class="btn btn-info mb-2 mr-2" id="btn_preview_report">Preview</a>
                <a href="javascript:void(0)" class="btn btn-primary mb-2 mr-2" id="btn_print_report">Print PDF</a>
            </div>
        </div>
    </div>
    <div class="col-12" id="preview_card">
        <div class="card">
            <div class="card-body">
                <table id="fabric_request_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr class="">
                            <th width="5%">No</th>
                            <th width="30%" class="text-center">Serial Number</th>
                            <th width="10%">GL</th>
                            <th width="">Color</th>
                            <th width="">Table</th>
                            <th width="">Qty Required</th>
                            <th width="">Qty Issued</th>
                            <th width="">Requested at</th>
                            <th width="">Issued at</th>
                            <th width="">Status</th>
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
    const dtable_preview_url = "{{ route('fabric-request.dtable-preview') }}";
    const url = "{{ route('fabric-request.print') }}";
    
</script>

<script type="text/javascript">
    // ## Page Variable
    let start_date_filter = moment().format('YYYY-MM-DD');
    let end_date_filter = moment().format('YYYY-MM-DD');

    const default_daterangepicker = () => {
        start_date_filter = moment().format('YYYY-MM-DD');
        end_date_filter = moment().format('YYYY-MM-DD');
        $('#date_filter').data('daterangepicker').setStartDate(moment());
        $('#date_filter').data('daterangepicker').setEndDate(moment());
    }

        $('#date_filter').daterangepicker({
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
        
    }, function(start, end, label) {
        $('#date_start_filter').val(start.format('YYYY-MM-DD'));
        $('#date_end_filter').val(end.format('YYYY-MM-DD'));
    });

    $('#date_start_filter').val(moment().startOf('month').format('YYYY-MM-DD'))
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

            var gl_filter = $('#gl_filter').val();
            var color_filter = $('#color_filter').val();
            var date_start_filter = $('#date_start_filter').val();
            var date_end_filter = $('#date_end_filter').val();

            window.open(url + '?gl_number=' + gl_filter + '&color_name=' + color_filter + '&start_date=' + date_start_filter + '&end_date=' + date_end_filter, '_blank');
        });
    });
</script>

<script type="text/javascript">
    $('#preview_card').hide();
    
    let fabric_request_table = $('#fabric_request_table').DataTable({
        processing: true,
        serverSide: true,
        deferLoading: 0,
        ajax: {
            url: dtable_preview_url,
            data: function (d) {
                d.gl_filter = $('#gl_filter').val();
                d.color_filter = $('#color_filter').val();
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
            { data: 'serial_number', name: 'serial_number', className: 'text-left'},
            { data: 'fbr_gl_number', name: 'fbr_gl_number'},
            { data: 'fbr_color', name: 'fbr_color'},
            { data: 'fbr_table_number', name: 'fbr_table_number'},
            { data: 'fbr_qty_required', name: 'fbr_qty_required'},
            { data: 'qty_issued', name: 'qty_issued'},
            { data: 'fbr_requested_at', name: 'fbr_requested_at'},
            { data: 'issued_at', name: 'issued_at'},
            { data: 'status', name: 'status'},
        ],
        columnDefs: [
            { targets: [0,1,2,3,4,5,6,7,8,9], orderable: false, searchable: false },
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
        fabric_request_table.ajax.reload(function(json){
            $('#btn_preview_report').removeClass('loading').attr('disabled',false);
        });
    });

</script>
@endpush