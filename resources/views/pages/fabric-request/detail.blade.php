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
                                <h5 style="font-weight:bold">Serial Number : {{ $fabric_request->fbr_serial_number }} </h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <dl class="row">
                                    <dt class="col-md-4 col-sm-12">GL Number</dt>
                                    <dd class="col-md-8 col-sm-12" id="gl_number"> {{ $fabric_request->gl_number }}  </dd>
                                    <dt class="col-md-4 col-sm-12">Color</dt>
                                    <dd class="col-md-8 col-sm-12" id="color"> {{ $fabric_request->color }}  </dd>
                                    <dt class="col-md-4 col-sm-12">Style</dt>
                                    <dd class="col-md-8 col-sm-12" id="style"> {{ $fabric_request->style }}  </dd>
                                    <dt class="col-md-4 col-sm-12">PO Number</dt>
                                    <dd class="col-md-8 col-sm-12" id="gl_number"> {{ $fabric_request->fabric_po }}  </dd>
                                    <dt class="col-md-4 col-sm-12">Table No</dt>
                                    <dd class="col-md-8 col-sm-12" id="gl_number"> {{ $fabric_request->table_number }}  </dd>
                                    <dt class="col-md-4 col-sm-12">Qty Required</dt>
                                    <dd class="col-md-8 col-sm-12" id="color"> {{ $fabric_request->qty_required }}  yds</dd>
                                </dl>
                            </div>
                            <div class="col-sm-7">
                                <dl class="row">
                                    
                                    <dt class="col-md-4 col-sm-12">Qty Issued</dt>
                                    <dd class="col-md-8 col-sm-12" id="color"> - </dd>
                                    <dt class="col-md-4 col-sm-12">Difference</dt>
                                    <dd class="col-md-8 col-sm-12" id="color"> - </dd>
                                    <dt class="col-md-4 col-sm-12">Remark</dt>
                                    <dd class="col-md-8 col-sm-12" id="color"> {{ ($fabric_request->fbr_remark) ? $fabric_request->fbr_remark : '-' }}  </dd>
                                    <dt class="col-md-4 col-sm-12">Fabric Detail</dt>
                                    <dd class="col-md-8 col-sm-12" id="color"> {{ $fabric_request->fabric_type }}  </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <dl class="row">
                                    <dt class="col-md-4 col-sm-12">Status</dt>
                                    <dd class="col-md-8 col-sm-12" id="gl_number"> {{ $fabric_request->fbr_status_print }}  </dd>
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

</script>
@stop
