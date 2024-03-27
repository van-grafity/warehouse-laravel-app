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
                <div class="mb-3 text-right">
                    <button id="reload_table_btn" class="btn btn-sm btn-info">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <table id="permission_category_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr class="">
                            <th width="30">No</th>
                            <th width="" class="text-center">Packinglist No</th>
                            <th width="">Invoice</th>
                            <th width="">Buyer</th>
                            <th width="">GL</th>
                            <th width="">Color</th>
                            <th width="">Batch</th>
                            <th width="">Offloaded Roll</th>
                            <th width="200">Action</th>
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
    const dtable_url = "{{ route('fabric-offloading.dtable') }}";
</script>

<script type="text/javascript">
    let permission_category_table = $('#permission_category_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#permission_category_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#permission_category_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
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
        permission_category_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

    let validator = $('#modal_permission_category form').validate({
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

</script>
@stop