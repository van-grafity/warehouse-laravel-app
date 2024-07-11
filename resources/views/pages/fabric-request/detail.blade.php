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
                    Fabric Request Information :
                </h3>
                <div class="card-tools ml-auto p-3">
                    <div class="btn-group">
                        @can('print')
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> Print </button>
                        @endcan
                        <div class="dropdown-menu dropdown-menu-right">
                            @can('issuance-note')    
                            <a class="dropdown-item" href="{{ route('fabric-request.issuance-note', $fabric_request->id) }}" target="_blank">Issuance note</a>
                            @endcan
                            
                            @can('issuance-note-full')
                            <a class="dropdown-item" href="{{ route('fabric-request.issuance-note-full', $fabric_request->id) }}" target="_blank">Issuance note (full)</a>
                            @endcan
                        </div>
                    </div>
                    <button type="button" class="btn btn-default mr-2" 
                        data-fbr-serial-number = "{{$fabric_request->fbr_serial_number }}"
                        onclick="sync_fabric_request(this)">
                        <i class="fas fa-sync-alt"></i> 
                        Syncronize Fabric Request
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <h5 style="font-weight:bold">Serial Number : {{ $fabric_request->apiFabricRequest->fbr_serial_number }} </h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                 <dl class="row">
                                    <dt class="col-md-4 col-sm-12">GL Number</dt>
                                    <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_gl_number }}  </dd>
                                    <dt class="col-md-4 col-sm-12">Color</dt>
                                    <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_color }}  </dd>
                                    <dt class="col-md-4 col-sm-12">Style</dt>
                                    <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_style }}  </dd>
                                    <dt class="col-md-4 col-sm-12">PO Number</dt>
                                    <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_fabric_po }}  </dd>
                                    <dt class="col-md-4 col-sm-12">Table No</dt>
                                    <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_table_number }}  </dd>
                                    <dt class="col-md-4 col-sm-12">Qty Required</dt>
                                    <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_qty_required }}  Yds</dd>
                                </dl>
                            </div>
                            <div class="col-sm-7">
                                <dl class="row">
                                    <dt class="col-md-4 col-sm-12">Qty Issued</dt>
                                    <dd class="col-md-8 col-sm-12"> <span> : {{ $fabric_request->qty_issued }} </span> Yds </dd>
                                    <dt class="col-md-4 col-sm-12">Difference</dt>
                                    <dd class="col-md-8 col-sm-12"> <span> : {{ $fabric_request->qty_difference }} </span> Yds </dd>
                                    <dt class="col-md-4 col-sm-12">Cutting Remark</dt>
                                    <dd class="col-md-8 col-sm-12"> : {{ ($fabric_request->apiFabricRequest->fbr_remark) ? $fabric_request->apiFabricRequest->fbr_remark : '-' }}  </dd>
                                    <dt class="col-md-4 col-sm-12">Fabric Detail</dt>
                                    <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_fabric_type }}  </dd>
                                    <dt class="col-md-4 col-sm-12">Warehouse Remark</dt>
                                    <dd class="col-md-8 col-sm-12"> : <span> {{ ($fabric_request->remark) ? $fabric_request->remark : '-' }}</span></dt>
                                </dl>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <dl class="row">
                                    <dt class="col-md-4 col-sm-12">Status</dt>
                                    <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_status_print }} </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-md-12">
                            <dl class="row">
                                <dt class="col-md-12 col-sm-12">Selected Roll :</dt>
                            </dl>
                        </div>
                        </div>
                        <table id="issuance_roll_table" class="table table-bordered table-hover text-center table-vertical-align mb-4">
                            <thead>
                                <tr class="">
                                    <th style="width: 50px">No</th>
                                    <th style="width: 150px" class="text-center">Color</th>
                                    <th style="width: 150px" class="text-center">Batch No.</th>
                                    <th style="width: 100px" class="text-center">Roll No.</th>
                                    <th style="width: 75px">Width</th>
                                    <th style="width: 50px">YDs</th>
                                    <th style="width: 150px">Rack No.</th>
                                    <th style="width: 75px">Location</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <dl class="row">
                                    <dt class="col-md-4 col-sm-12">Total Roll : <span id="total_roll_qty"> 0 </span></dt>
                                </dl>
                                <dl class="row">
                                    <dt class="col-md-4 col-sm-12">Total Length : <span> {{ $fabric_request->qty_issued }} </span> Yds</dt>
                                </dl>
                            </div>
                        </div>
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
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const cutting_app_api_baseurl = "{{ config('api.cutting_app.base_url') }}";
    const cutting_app_api_token = "{{ config('api.cutting_app.token') }}";
    const fabric_roll_issuances = @json($fabric_roll_issuance);

    // ## URL List
    const sync_url = "{{ route('fabric-request.sync') }}";

    const sync_fabric_request = async (element) => {

        $(element).addClass('loading').attr('disabled',true);
        
        let fbr_serial_number = $(element).data('fbr-serial-number');
        let fbr_sync_url = `${cutting_app_api_baseurl}/fabric-request-sync/get-fabric-request`;

        let response = await using_axios({
            url: fbr_sync_url,
            method: 'GET',
            data: {
                fbr_serial_number : fbr_serial_number,
            },
            token : cutting_app_api_token,
        });

        if ([401,404].includes(response.status)) {
            $(element).removeClass('loading').attr('disabled',false);
            swal_failed({
                title : response.message,
            });
            return false;
        }

        let fetch_data = {
            url: sync_url,
            method: "POST",
            data: response.data,
            token: token,
        }

        try {
            let response_sync = await using_fetch(fetch_data);
            $(element).removeClass('loading').attr('disabled',false);

            if(response_sync.status != 'success') {
                swal_failed({
                    title : "Something went wrong",
                });
                return false;
            }
            
            swal_info({'title': `${response_sync.message} (${fbr_serial_number})`, timer: 3000, reload_option: true });

        } catch (error) {
            $(element).removeClass('loading').attr('disabled',false);
            swal_failed({
                title : "Something went wrong",
                text : "Try to decrease range of date filter",
            });
            return false;
        }
    }

    const is_table_data_empty = () => {
        let is_empty = true;
        $('#issuance_roll_table tbody tr').each(function() {
            if ($(this).find('td').length > 1) {
                is_empty = false;
                return false; // ## exit loop early if non-default data found
            }
        });
        return is_empty;
    };

    const selected_roll_table_listener = () => {
        if(is_table_data_empty()) {
            // ## Display default data if the table is empty
            fill_table_with_default_data({
                table_selector : '#issuance_roll_table',
                num_columns : 8,
                default_data : 'No fabric roll selected'
            });
        } else {
            // ## Remove default row if table is not empty
            $('#issuance_roll_table tbody tr.empty-row-table').remove();
            refresh_table_number({ table_selector: '#issuance_roll_table'});
        }
    };

    const issuance_roll_table = (fabric_roll_issuance) => {

        let issuance_data_rolls = '';

        if(fabric_roll_issuance.length > 0) {
            fabric_roll_issuance.map((fabric_roll, index) => {
                issuance_data_rolls += `
                    <tr role="row" class="odd">
                    <td>${index + 1}</td>
                    <td>${fabric_roll.color}</td>
                    <td>${fabric_roll.batch}</td>
                    <td>${fabric_roll.roll_number}</td>
                    <td>${fabric_roll.width}</td>
                    <td>${fabric_roll.yds}</td>
                    <td>${fabric_roll.rack_number}</td>
                    <td>${fabric_roll.location}</td>
                    </tr>
                    `;
            });
        } else {
            issuance_data_rolls = '<tr style="text-align: center"><td colspan="8">There is no data fabric roll selected</td></tr>';
        }
    
        let total_roll_qty = $(fabric_roll_issuance).length;

        $('#total_roll_qty').text(total_roll_qty)
        $('#issuance_roll_table tbody').html(issuance_data_rolls);
    };

    issuance_roll_table(fabric_roll_issuances);

</script>
@stop
