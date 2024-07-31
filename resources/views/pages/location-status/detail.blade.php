@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card ">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto">
                    <i class="fas fa-info-circle mr-1"></i>
                    Location Information :
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <h5 style="font-weight:bold">Location : {{$location->location}} </h5>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-12 d-inline-flex justify-content-end">
                                <div class="filter_wrapper text-right align-self-center">
                                    <button id="reload_table_btn" class="btn btn-sm btn-info">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <table id="detail_location_table" class="table table-bordered table-hover text-center table-vertical-align mb-4">
                            <thead>
                                <tr class="">
                                    <th>No</th>
                                    <th>Rack</th>
                                    <th>Total Roll</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

<!-- Back Button -->
<div class="row text-right mb-5">
    <div class="col-12">
        @php $back_url = (url()->previous() == url()->current()) ? url('location-status') : url()->previous() @endphp
        <a href="<?= $back_url ?>" class="btn btn-secondary"><i class="fas fa-arrow-alt-circle-left mr-1"></i>Back</a>
    </div>
</div>

@endsection

@section('js')

<script type="text/javascript">
    
    // ## URL List
    const dtable_url = "{{ route('location-status.dtable-roll-list') }}";

    const location_id = '{{ $location->id }}';

    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');
    }
</script>

<script type="text/javascript">
    let detail_location_table = $('#detail_location_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            data: function (d) {
                d.location_id = location_id;
            },
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#detail_location_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#detail_location_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
                $('[data-toggle="tooltip"]').tooltip();
            },
        },
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'serial_number', name: 'racks.serial_number'},
            { data: 'total_roll', name: 'total_roll'},
            { data: 'action', name: 'action'},
        ],
        columnDefs: [
            { targets: [0, -1], orderable: false, searchable: false },
        ],

        paging: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        autoWidth: false,
        orderCellsTop: true,
    });

    $('#reload_table_btn').on('click', function(event) {
        $(this).addClass('loading').attr('disabled',true);
        fabric_roll_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

</script>
@stop