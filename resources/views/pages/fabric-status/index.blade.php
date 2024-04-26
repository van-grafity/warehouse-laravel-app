@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Fabric Stock Status </h3>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="mb-3 text-right">
                    <button id="reload_table_btn" class="btn btn-sm btn-info">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <table id="packinglist_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr class="">
                            <th width="20">No</th>
                            <th width="" class="text-center">Packinglist No</th>
                            <th width="">Batch</th>
                            <th width="">PO Number</th>
                            <th width="">Invoice</th>
                            <th width="50">GL</th>
                            <th width="">Color</th>
                            <th width="">Roll Stock</th>
                            <th width="">Length (YDs)</th>
                            <th width="">Weight (KGs)</th>
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
    const dtable_url = "{{ route('fabric-status.dtable') }}";
</script>

<script type="text/javascript">
    let packinglist_table = $('#packinglist_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
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
            { data: 'batch_number', name: 'batch_number'},
            { data: 'po_number', name: 'po_number'},
            { data: 'invoice', name: 'invoice'},
            { data: 'gl_number', name: 'gl_number'},
            { data: 'color', name: 'color'},
            { data: 'roll_balance', name: 'roll_balance'},
            { data: 'total_length_yds', name: 'total_length_yds'},
            { data: 'total_weight_kgs', name: 'total_weight_kgs'},
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
</script>
@stop