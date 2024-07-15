@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<style>
    /*
     * Make Thead of Datatable Vertical Align Center / Middle
     */
    #packinglist_table thead th {
        vertical-align: middle;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Fabric Stock Status </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row  mb-3">
                    <div class="col-sm-12 d-inline-flex justify-content-end">
                        <div class="filter_wrapper mr-2" style="width:200px;">
                             
                        <select name="gl_filter" id="gl_filter" class="form-control select2">
                            <option value="" selected>All GL Number</option>    
                            @foreach ($packinglist as $packinglist)
                            <option value="{{$packinglist->gl_number}}" >{{$packinglist->gl_number}}</option>    
                            @endforeach
                        </select>
                        
                        </div>
                        <div class="filter_wrapper mr-2" style="width:200px;">
                            <select name="color_filter" id="color_filter" class="form-control select2">
                                <option value="" selected >All Color</option>
                            </select>
                        </div>
                        <div class="filter_wrapper text-right align-self-center">
                            <button id="reload_table_btn" class="btn btn-sm btn-info">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <table id="packinglist_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr class="">
                            <th width="20" rowspan="2">No</th>
                            <th width="" rowspan="2" class="text-center">Packinglist No</th>
                            <th width="50"rowspan="2">GL</th>
                            <th width="" rowspan="2">Color</th>
                            <th width="" rowspan="2">Batch</th>
                            <th width="" colspan="2">Stock</th>
                            <th width="150" rowspan="2">Action</th>  
                        </tr> 
                        <tr>      
                            <th width="">Roll Qty</th>
                            <th width="" style="border-right-width:1px">Length (YDs)</th>
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

<!-- Modal Detail Fabric Status  -->
<div class="modal fade" id="modal_fabric_status" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Detail Fabric Rolls</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12 col-xl-12">
                        <div class="row mb-3">
                            <div class="col-sm-12">
                                <h5 class="text-bold" type="text">Serial Number : <span id="serial_number"></span></h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <dl class="row">
                                    <dt class="col-md-4 col-sm-12">Invoice </dt>
                                    <dd class="col-md-8 col-sm-12">: <span id="invoice"></span></dd>

                                    <dt class="col-md-4 col-sm-12">Buyer</dt>
                                    <dd class="col-md-8 col-sm-12">: <span id="buyer"></span> </dd>

                                    <dt class="col-md-4 col-sm-12">GL Number</dt>
                                    <dd class="col-md-8 col-sm-12">: <span id="gl_number"></span></dd>

                                    <dt class="col-md-4 col-sm-12">Style</dt>
                                    <dd class="col-md-8 col-sm-12">: <span id="style"></span> </dd>
                                </dl>
                            </div>
                            <div class="col-sm-7">
                                <dl class="row">
                                    <dt class="col-md-4 col-sm-12">PO Number</dt>
                                    <dd class="col-md-8 col-sm-12">: <span id="po_number"></span></dd>

                                    <dt class="col-md-4 col-sm-12">Color</dt>
                                    <dd class="col-md-8 col-sm-12">: <span id="color"></span></dd>

                                    <dt class="col-md-4 col-sm-12">Batch</dt>
                                    <dd class="col-md-8 col-sm-12">: <span id="batch_number"></span></dd>

                                    <dt class="col-md-4 col-sm-12">Fabric Content</dt>
                                    <dd class="col-md-8 col-sm-12">: <span id="fabric_content"></span></dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <!-- Table Roll List -->
                    <div class="col-lg-12 col-xl-12">
                        <h5>Detail Fabric Roll</h5>
                        <table id="fabric_roll_table" class="table table-bordered table-sm">
                            <thead class="text-center thead-dark">
                                <tr>
                                    <th style="width: 80px;">Roll Number</th>
                                    <th style="width: 280px;">Serial Number</th>
                                    <th style="width: 60px;">KGs</th>
                                    <th style="width: 60px;">LBs</th>
                                    <th style="width: 60px;">YDs</th>
                                    <th style="width: 60px;">Width</th>
                                    <th style="width: 100px;">Rack Number</th>
                                    <th style="width: 80px;">Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data rows (fabric roll ) will be dynamically populated here -->
                            </tbody>       
                            <tfoot>
                            </tfoot>
                        </table>                 
                    </div>
                    <!-- End Table Roll List -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Detail Fabric Status -->

@endsection

@section('js')

<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ## URL List
    const show_url = "{{ route('fabric-status.show',':id') }}";
    const dtable_url = "{{ route('fabric-status.dtable') }}";
    const fetch_select_color_url = "{{ route('fetch-select.color') }}";

    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');
    }
    
    const show_modal_detail = async (modal_element_id, packinglist_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Detail Fabric Status",
        }
        clear_form(modal_data);
        
        fetch_data = {
            url: show_url.replace(':id',packinglist_id),
            method: "GET",
            token: token,
        }
        result = await using_fetch(fetch_data);
        
        packinglist_data = result.data.packinglist
        fabric_rolls_data = result.data.fabric_rolls

        $('#serial_number').text(packinglist_data.serial_number);
        $('#invoice').text(packinglist_data.invoice.invoice_number);
        $('#buyer').text(packinglist_data.buyer);
        $('#gl_number').text(packinglist_data.gl_number);
        $('#po_number').text(packinglist_data.po_number);
        $('#color').text(packinglist_data.color.color);
        $('#batch_number').text(packinglist_data.batch_number);
        $('#style').text(packinglist_data.style);
        $('#fabric_content').text(packinglist_data.fabric_content);

        
        let fabric_rolls_element = '';
        let total_rolls_element = '';
        
        let total_roll_weight = 0;
        let total_roll_pound = 0;
        let total_roll_length = 0;
        
        if(fabric_rolls_data.length > 0) {
            fabric_rolls_data.forEach(fabric_roll => {
                fabric_rolls_element += `
                    <tr style="text-align: center">
                        <td>${fabric_roll.roll_number}</td>
                        <td>${fabric_roll.serial_number}</td> 
                        <td>${fabric_roll.kgs}</td> 
                        <td>${fabric_roll.lbs}</td>
                        <td>${fabric_roll.yds}</td>
                        <td>${fabric_roll.width}</td>
                        <td>${fabric_roll.rack_number}</td> 
                        <td>${fabric_roll.rack_location}</td>
                    </tr>
                `;

                // ## Penjumlahan data roll
                total_roll_weight = total_roll_weight + fabric_roll.kgs;
                total_roll_pound = total_roll_pound + fabric_roll.lbs;
                total_roll_length = total_roll_length + fabric_roll.yds;
            });
            total_rolls_element += `
                <tr style="text-align: center">
                    <td style="font-weight:bold" >Total</td>
                    <td class="text-bold">${fabric_rolls_data.length} Roll</td>
                    <td class="text-bold">${(total_roll_weight).toFixed(2)} Kgs</td> 
                    <td class="text-bold">${(total_roll_pound).toFixed(2)} Lbs</td> 
                    <td class="text-bold">${(total_roll_length).toFixed(2)} Yds</td>
                    <td colspan="3"></td>
                </tr>
            `;
        } else {
            fabric_rolls_element = '<tr style="text-align: center"><td colspan="8">There is no data fabric roll</td></tr>';
        }
        
        $('#fabric_roll_table').find('tbody').html(fabric_rolls_element);
        $('#fabric_roll_table').find('tfoot').html(total_rolls_element);
        
        $(`#${modal_element_id}`).modal('show');
    }
</script>

<script type="text/javascript">
    let packinglist_table = $('#packinglist_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
            data: function (d) {
                d.gl_filter = $('#gl_filter').val();
                d.color_filter = $('#color_filter').val();
            },
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#packinglist_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#packinglist_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
                $('[data-toggle="tooltip"]').tooltip();
            },
        },
        order: [],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'serial_number', name: 'packinglists.serial_number', className: 'text-left'},
            { data: 'gl_number', name: 'gl_number'},
            { data: 'color_name', name: 'colors.color'},
            { data: 'batch_number', name: 'batch_number'},
            { data: 'roll_balance', name: 'roll_balance', orderable: false, searchable: false},
            { data: 'total_length_yds', name: 'total_length_yds', orderable: false, searchable: false},
            { data: 'action', name: 'action', searchable: false},
        ],
        columnDefs: [
            { targets: [0,-1], orderable: false, searchable: false },
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
        packinglist_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

    let validator = $('#modal_fabric_status form').validate({
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
    

    $('#color_filter.select2').select2({
        ajax: {
            url: fetch_select_color_url,
            dataType: 'json',
            delay: 500,
            data: function (params) {
                var query = {
                    search: params.term || '',
                }
                return query;
            },
            processResults: function (fetch_result, params) {
                if (!params.term) {
                    fetch_result.data.items.unshift({
                        id: '',
                        text: 'All Color'
                    });
                }
                return {
                    results: fetch_result.data.items,
                };
            },
        }
    });

    $('#color_filter').change(function(event) {
        reload_dtable();
    });

    $('#gl_filter').select2({}).change(function(event) {
        reload_dtable();
    }); 
</script>


@stop