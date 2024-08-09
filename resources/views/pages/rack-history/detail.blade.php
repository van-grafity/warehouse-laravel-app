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
                    Rack Location History :
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <h5 style="font-weight:bold">Serial Number : {{$rack->serial_number}} </h5>
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
                        <table id="rack_history_table" class="table table-bordered table-hover text-center table-vertical-align mb-4">
                            <thead>
                                <tr class="">
                                    <th style="width: 50px">No</th>
                                    <th style="width: 150px" class="text-center">Location</th>
                                    <th style="width: 150px" class="text-center">Entry At</th>
                                    <th style="width: 150px" class="text-center">Exit At</th>
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
        @php $back_url = (url()->previous() == url()->current()) ? url('rack-location') : url()->previous() @endphp
        <a href="<?= $back_url ?>" class="btn btn-secondary"><i class="fas fa-arrow-alt-circle-left mr-1"></i>Back</a>
    </div>
</div>

@endsection

@section('js')

<script type="text/javascript">
    
    // ## URL List
    const dtable_url = "{{ route('rack-history.dtable-roll-list') }}";

    const rack_id = '{{ $rack->id }}';

    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');
    }
</script>

<script type="text/javascript">
    let rack_history_table = $('#rack_history_table').DataTable({
        processing: true,
        ajax: {
            url: dtable_url,
            data: function (d) {
                d.rack_id = rack_id;
            },
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#rack_history_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#rack_history_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
                $('[data-toggle="tooltip"]').tooltip();
            },
        },
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'location', name: 'location'},
            { data: 'entry_at', name: 'entry_at'},
            { data: 'exit_at', name: 'exit_at'},
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
        rack_history_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

</script>
@stop