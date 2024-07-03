<div id="packinglist_information_card" class="card {{ $collapsed_card_class }} ">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3 my-auto">
            <i class="fas fa-info-circle mr-1"></i>
            Packing List Information :
        </h3>
        <div class="card-tools ml-auto p-3">
            <div class="btn-group">
                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> Related Page </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('packinglist.detail', $packinglist->id) }}">Packing List</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('fabric-stock-in.detail', $packinglist->id) }}">Fabric Stock in</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('fabric-status.detail', $packinglist->id) }}">Fabric Status</a>
                </div>
            </div>
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-lg-12 col-xl-7">
                <div class="row mb-3">
                    <div class="col-sm-12">
                        <h5 style="font-weight:bold">Serial Number : {{ $packinglist->serial_number }}</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <dl class="row">
                            <dt class="col-md-4 col-sm-12">Invoice</dt>
                            <dd class="col-md-8 col-sm-12" id="invoice"> {{ $packinglist->invoice->invoice_number }} </dd>

                            <dt class="col-md-4 col-sm-12">Buyer</dt>
                            <dd class="col-md-8 col-sm-12" id="buyer"> {{ $packinglist->buyer }} </dd>

                            <dt class="col-md-4 col-sm-12">GL Number</dt>
                            <dd class="col-md-8 col-sm-12" id="gl_number"> {{ $packinglist->gl_number }}</dd>

                            <dt class="col-md-4 col-sm-12">Style</dt>
                            <dd class="col-md-8 col-sm-12" id="style"> {{ $packinglist->style }} </dd>
                        </dl>
                    </div>
                    <div class="col-sm-7">
                        <dl class="row">
                            <dt class="col-md-4 col-sm-12">PO Number</dt>
                            <dd class="col-md-8 col-sm-12" id="po_number"> {{ $packinglist->po_number }} </dd>

                            <dt class="col-md-4 col-sm-12">Color</dt>
                            <dd class="col-md-8 col-sm-12" id="color"> {{ $packinglist->color->color }} ({{ $packinglist->color->code }}) </dd>

                            <dt class="col-md-4 col-sm-12">Batch</dt>
                            <dd class="col-md-8 col-sm-12" id="batch_number"> {{ $packinglist->batch_number }} </dd>

                            <dt class="col-md-4 col-sm-12">Fabric Content</dt>
                            <dd class="col-md-8 col-sm-12" id="fabric_content"> {{ $packinglist->fabric_content }} </dd>

                        </dl>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-xl-5">
                <div class="card shadow card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title text-bold">Quick Summary</h3>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-sm text-center align-middle" id="roll_summary_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Packing List Qty</th>
                                    <th>Stock In</th>
                                    <th>Balance to rec'd</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$roll_summary)
                                <tr>
                                    <td colspan="5">No Data</td>
                                </tr>
                                @else
                                    @foreach ($roll_summary as $key => $roll)
                                    <tr>
                                        <td class="text-left"><?= $roll->category; ?></td>
                                        <td><?= $roll->packinglist_qty; ?></td>
                                        <td><?= $roll->stock_in; ?></td>
                                        <td><?= $roll->balance; ?></td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->