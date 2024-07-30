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
                    Rack Location Information :
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <h5 style="font-weight:bold">Serial Number : {{$rack->serial_number}} </h5>
                                <h5 style="font-weight:bold">Location : {{$rack->location->location}} </h5>
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
                        <table id="fabric_roll_table" class="table table-bordered table-hover text-center table-vertical-align mb-4">
                            <thead>
                                <tr class="">
                                    <th style="width: 50px">No</th>
                                    <th style="width: 150px" class="text-center">Color</th>
                                    <th style="width: 150px" class="text-center">Gl Number</th>
                                    <th style="width: 150px" class="text-center">Batch No.</th>
                                    <th style="width: 100px" class="text-center">Roll No.</th>
                                    <th style="width: 75px">Width</th>
                                    <th style="width: 50px">YDs</th>
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
        @php $back_url = (url()->previous() == url()->current()) ? url('fabric-request') : url()->previous() @endphp
        <a href="<?= $back_url ?>" class="btn btn-secondary"><i class="fas fa-arrow-alt-circle-left mr-1"></i>Back</a>
    </div>
</div>

@endsection

@section('js')

<script type="text/javascript">
    
    // ## URL List
    const dtable_url = "{{ route('rack-location.dtable-roll-list') }}";

    const rack_id = '{{ $rack->id }}';

    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');
    }
</script>

<script type="text/javascript">
    let fabric_roll_table = $('#fabric_roll_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            data: function (d) {
                d.rack_id = rack_id;
            },
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#fabric_roll_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#fabric_roll_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
                $('[data-toggle="tooltip"]').tooltip();
            },
        },
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'color', name: 'colors.color'},
            { data: 'gl_number', name: 'packinglists.gl_number'},
            { data: 'batch_number', name: 'packinglists.batch_number'},
            { data: 'roll_number', name: 'roll_number'},
            { data: 'width', name: 'fabric_rolls.width'},
            { data: 'yds', name: 'fabric_rolls.yds'},
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
    });

    $('#reload_table_btn').on('click', function(event) {
        $(this).addClass('loading').attr('disabled',true);
        fabric_roll_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

</script>
@stop