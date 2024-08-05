@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Authentication Log List </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="mb-3 text-right">
                    <button id="reload_table_btn" class="btn btn-sm btn-info">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <table id="authentication_log_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="">Name</th>
                            <th width="">Email</th>
                            <th width="">IP Address</th>
                            <th width="150">Login at</th>
                            <th width="100">Login Status</th>
                            <th width="150">Logout at</th>
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
    const dtable_url = "{{ route('authentication-log.dtable') }}";

</script>

<script type="text/javascript">
    let authentication_log_table = $('#authentication_log_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#authentication_log_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#authentication_log_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
            },
        },
        order: [4, 'desc'],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'user_name', name: 'users.name'},
            { data: 'user_email', name: 'users.email'},
            { data: 'ip_address', name: 'ip_address'},
            { data: 'login_at', name: 'login_at'},
            { data: 'login_status', name: 'login_status'},
            { data: 'logout_at', name: 'logout_at'},
        ],
        columnDefs: [
            { targets: [0,5], orderable: false, searchable: false },
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
        authentication_log_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

</script>
@stop