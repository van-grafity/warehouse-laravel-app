@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Packing List </h3>

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
                        <tr class="">
                            <th width="30">No</th>
                            <th width="" class="text-center">Packinglist No</th>
                            <th width="">Invoice</th>
                            <th width="">Buyer</th>
                            <th width="50">GL</th>
                            <th width="">Color</th>
                            <th width="">Batch</th>
                            <th width="">Roll Stock</th>
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

@endsection

@section('js')

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // ## URL List
    const dtable_url = "{{ route('fabric-stock-in.dtable') }}";
    const fetch_select_invoice_url = "{{ route('fetch-select.invoice') }}";
    const fetch_select_color_url = "{{ route('fetch-select.color') }}";
</script>

<script type="text/javascript">
    let packinglist_table = $('#packinglist_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            data: function (d) {
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
            { data: 'roll_balance', name: 'roll_balance'},
            { data: 'action', name: 'action'},
        ],
        columnDefs: [
            { targets: [0,-2], orderable: false, searchable: false },
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

    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');
    }

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