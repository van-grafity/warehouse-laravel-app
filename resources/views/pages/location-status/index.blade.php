@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Location Status </h3>
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
                <table id="location_status_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th width="">No</th>
                            <th width="">Location</th>
                            <th width="">Rack</th>
                            <th width="">Color</th>
                            <th width="">Gl Number</th>
                            <th width="">Total Rack</th>
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
    const dtable_url = "{{ route('location-status.dtable') }}";

    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');
    }

</script>

<script type="text/javascript">
    let location_status_table = $('#location_status_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#location_status_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#location_status_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
            },
        },
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'location', name: 'location'},
            { data: 'rack', name: 'rack'},
            { data: 'color', name: 'color'},
            { data: 'gl_number', name: 'gl_number'},
            { data: 'total_rack', name: 'total_rack'},
        ],
        columnDefs: [
            { targets: [0], orderable: false, searchable: false },
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
        location_status_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

</script>
@stop