@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')

<div class="row">
    <div class="col-12">
        
        <!-- Load Card Packinglist Information Using Component -->
        <div id="packinglist_information_container"></div>

        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> 
                    <i class="fas fa-list-ol mr-1"></i>
                    Fabric Roll List 
                </h3>
                 <div class="ml-auto p-3">
                    @can('print')
                        <a href="{{ route('fabric-status.export') }}" class="btn btn-primary ">Report</a>
                    @endcan
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-12 d-flex">
                        <div class="filter-wrapper text-right ml-auto align-self-center">
                            <button id="reload_table_btn" class="btn btn-sm btn-info"> 
                                <i class="fas fa-sync-alt"></i> 
                            </button>
                        </div>
                    </div>
                </div>
                <table id="fabric_roll_table" class="table table-bordered table-hover text-center table-vertical-align">
                    <thead>
                        <tr class="">
                            <th width="100" class="text-center">Roll Number</th>
                            <th width="" class="text-center">Serial Number</th>
                            <th width="">KGs</th>
                            <th width="">LBs</th>
                            <th width="">YDs</th>
                            <th width="">Rack Number</th>
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
<!-- /.row -->

<!-- Back Button -->
<div class="row text-right mb-5">
    <div class="col-12">
        @php $back_url = (url()->previous() == url()->current()) ? url('fabric-status') : url()->previous() @endphp
        <a href="<?= $back_url ?>" class="btn btn-secondary"><i class="fas fa-arrow-alt-circle-left mr-1"></i>Back</a>
    </div>
</div>

@endsection


@section('js')

<script type="text/javascript">

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const packinglist_id = '{{ $packinglist->id }}';
    
    // ## URL List
    const dtable_list_url = "{{ route('fabric-status.dtable-roll-list') }}";
    const packinglist_information_url = "{{ route('packinglist.information-card', ':id') }}";
    const export_instore_report_url = "{{ route('fabric-status.export') }}";

    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');

        let is_card_collapsed = $('#packinglist_information_card').hasClass("collapsed-card");
        
        load_component({
            url : packinglist_information_url.replace(':id',packinglist_id),
            container_element_id : 'packinglist_information_container',
            data : {
                collapsed_card_class : is_card_collapsed ? 'collapsed-card' : '',
            }
        })
    }

</script>

<script type="text/javascript">
    let fabric_roll_table = $('#fabric_roll_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_list_url,
            data: function (d) {
                d.packinglist_id = packinglist_id;
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
            { data: 'roll_number', name: 'CAST(fabric_rolls.roll_number AS SIGNED)'},
            { data: 'serial_number', name: 'fabric_rolls.serial_number'},
            { data: 'kgs', name: 'fabric_rolls.kgs'},
            { data: 'lbs', name: 'fabric_rolls.lbs'},
            { data: 'yds', name: 'fabric_rolls.yds'},
            { data: 'rack_number', name: 'racks.rack_number'},
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
        searchDelay: 500,
    });

    $('#reload_table_btn').on('click', function(event) {
        $(this).addClass('loading').attr('disabled',true);
        fabric_roll_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

    setTimeout(reload_dtable, 500);

</script>

@stop
