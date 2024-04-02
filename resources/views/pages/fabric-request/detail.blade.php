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
                    <button type="button" class="btn btn-default mr-2" onclick="sync_fabric_request('{{$fabric_request->fbr_serial_number }}')"><i class="fas fa-sync-alt"></i> Syncronize Fabric Request</button>
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

    // ## URL List


</script>
@stop
