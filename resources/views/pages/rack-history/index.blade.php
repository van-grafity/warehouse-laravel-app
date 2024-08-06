@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Rack History </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row  mb-3">
                    <div class="col-sm-12 d-inline-flex justify-content-end">
                        <div class="filter_wrapper text-right align-self-center">
                            <button id="reload_table_btn" class="btn btn-sm btn-info">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <table id="rack_history_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th width="">No</th>
                            <th width="">Rack</th>
                            <th width="">Location</th>
                            <th width="">Entry At</th>
                            <th width="">Action</th>
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
    const dtable_url = "{{ route('rack-history.dtable') }}";

    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');
    }

</script>

<script type="text/javascript">
    let rack_history_table = $('#rack_history_table').DataTable({
        processing: true,
        ajax: {
            url: dtable_url,
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#rack_history_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#rack_history_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
            },
        },
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
            { data: 'serial_number', name: 'serial_number'},
            { data: 'location', name: 'location'},
            { data: 'entry_at', name: 'entry_at'},
            { data: 'action', name: 'action', searchable: false},
        ],
        columnDefs: [
            { targets: [0,-1], orderable: false},
        ],
        paging: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        autoWidth: false,
        orderCellsTop: true,
    })

    $('#reload_table_btn').on('click', function(event) {
        $(this).addClass('loading').attr('disabled',true);
        rack_history_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

</script>
@stop