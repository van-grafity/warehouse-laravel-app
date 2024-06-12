@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')

<div class="card">
    <div class="card-header d-flex p-0">
        <h3 class="card-title p-3 my-auto"> 
            Fabric Allocation
        </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-sm-12"> 
                <div class="row">
                    <div class="col-md-12">
                        <dl class="row">
                            <dd class="col-md-12 col-sm-12"><b>Serial Number : {{ $fabric_request->fbr_serial_number }}</b></dd>
                            
                            <dt class="col-md-2 col-sm-12">Gl Number</dt>
                            <dd class="col-md-10 col-sm-12" id="gl_number"> {{ $fabric_request->gl_number }}</dd>

                            <dt class="col-md-2 col-sm-12">Color </dt>
                            <dd class="col-md-10 col-sm-12" id="color">{{ $fabric_request->color }}</dd>

                            <dt class="col-md-2 col-sm-12">Qty Required</dt>
                            <dd class="col-md-10 col-sm-12" id="qty_required">{{ $fabric_request->qty_required }} yds</dd>
                        </dl>
                    </div>  
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="ml-auto" style="margin-bottom:50px;">
                            @can('manage')
                                <a href="javascript:void(0)" type="button" class="btn btn-info" id="btn_modal_show" onclick="show_modal_detail('modal_detail_fabric_request', {{$fabric_request->id}})">More detail</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12"> 
                <div class="row">
                    <div class="col-md-12">
                        <dl class="row">
                            <dt class="col-md-12 col-sm-12">Selected Roll :</dt>
                        </dl>
                    </div>
                </div>
                <table id="selected_roll_table" class="table table-bordered table-hover text-center table-vertical-align">
                    <thead>
                        <tr class="">
                            <th width="50">No</th>
                            <th width="" class="text-center">Color</th>
                            <th width="" class="text-center">Batch No.</th>
                            <th width="" class="text-center">Roll No.</th>
                            <th width="75">Width</th>
                            <th width="50">YDs</th>
                            <th width="100">Rack No.</th>
                            <th width="75">Location</th>
                            <th width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12">
                        <dl class="row">
                            <dt class="col-md-4 col-sm-12">Total Roll : <span id="total_selected_roll_qty"> 0 </span></dt>
                        </dl>
                    </div>
                </div>
                    <div class="row">
                    <div class="col-md-12">
                        <dl class="row">
                            <dt class="col-md-4 col-sm-12">Total Length : <span id="total_selected_roll_length"> 0 </span> Yds </dt>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12"> 
                <div class="col-sm-12 d-inline-flex justify-content-end" style= "margin:30px 0 15px 0;">
                    <div class="action-wrapper mr-auto">
                        @can('manage')
                            <button class="btn btn-success btn-submit" disabled="disabled" onclick="move_selected_roll_to_fbr()">Add to FBR</button>
                        @endcan
                    </div>
                    <div class="filter_wrapper mr-2" style="width:120px; height:10px">                         
                        <select name="gl_filter" id="gl_filter" class="form-control select2">
                            <option value="" selected>All GL</option>    
                            @foreach ($gl_numbers as $gl_number)
                                <option value="{{$gl_number->gl_number}}">{{$gl_number->gl_number}}</option>    
                            @endforeach
                        </select>
                    </div>
                    <div class="filter_wrapper mr-2" style="width:250px;">
                        <select name="color_filter" id="color_filter" class="form-control select2">
                            <option value="" selected >All Color</option>
                        </select>
                    </div>
                    <div class="filter_wrapper mr-2" style="width:150px;">                         
                        <select name="batch_filter" id="batch_filter" class="form-control select2">
                            <option value="" selected>All Batch</option>
                            @foreach ($batch_numbers as $batch_number)
                                <option value="{{$batch_number->batch_number}}">{{$batch_number->batch_number}}</option>    
                            @endforeach
                            <option value=""></option>  
                        </select>
                    </div>
                    <div class="action-wrapper">
                        <button id="reload_table_btn" class="btn btn-info"> Apply </button>
                    </div>
                </div>
                <table id="fabric_roll_table" class="table table-bordered table-hover text-center table-vertical-align">
                    <thead>
                        <tr class="">
                            <th width="30">
                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input 
                                            id="roll_checkbox_all" 
                                            class="custom-control-input checkbox-all-control" 
                                            type="checkbox"
                                        >
                                        <label for="roll_checkbox_all" class="custom-control-label"></label>
                                    </div>
                                </div>
                            </th>
                            <th width="" class="text-center">Color</th>
                            <th width="" class="text-center">Batch No.</th>
                            <th width="" class="text-center">Roll No</th>
                            <th width="75">Width</th>
                            <th width="50">YDs</th>
                            <th width="100">Rack No</th>
                            <th width="75">Location</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>     
            </div>
        </div>
        <div class="action-wrapper mr-auto" style="float: right; margin-top: 50px">
            @can('manage')
                <a href="javascript:void(0)" type="button" class="btn btn-primary"  id="btn_modal_show" onclick="show_modal_issuance('modal_fabric_issuance', {{$fabric_request->id}})">Save</a>
            @endcan
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<!-- Modal Detail -->
<div class="modal fade" id="modal_detail_fabric_request" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Fabric Request Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                                        <dd class="col-md-8 col-sm-12" id="fabric_po"> {{ $fabric_request->fabric_po }}  </dd>
                                        <dt class="col-md-4 col-sm-12">Table No</dt>
                                        <dd class="col-md-8 col-sm-12" id="table_number"> {{ $fabric_request->table_number }}  </dd>
                                        <dt class="col-md-4 col-sm-12">Qty Required</dt>
                                        <dd class="col-md-8 col-sm-12" id="qty_required"> {{ $fabric_request->qty_required }}  yds</dd>
                                    </dl>
                                </div>
                                <div class="col-sm-7">
                                    <dl class="row">
                                        
                                        <dt class="col-md-4 col-sm-12">Qty Issued</dt>
                                        <dd class="col-md-8 col-sm-12" id=""> - </dd>
                                        <dt class="col-md-4 col-sm-12">Difference</dt>
                                        <dd class="col-md-8 col-sm-12" id=""> - </dd>
                                        <dt class="col-md-4 col-sm-12">Remark</dt>
                                        <dd class="col-md-8 col-sm-12" id="fbr_remark"> {{ ($fabric_request->fbr_remark) ? $fabric_request->fbr_remark : '-' }}  </dd>
                                        <dt class="col-md-4 col-sm-12">Fabric Detail</dt>
                                        <dd class="col-md-8 col-sm-12" id="fabric_type"> {{ $fabric_request->fabric_type }}  </dd>
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
                        <!-- /.card-body -->
                        </div>
                    <!-- /.card -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Detail -->

<!-- Modal Save -->
<div class="modal fade" id="modal_fabric_issuance" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Fabric Request Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                        <!-- /.card-body -->
                        </div>
                    <!-- /.card -->
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <dl class="row">
                                <dt class="col-md-12 col-sm-12">Selected Roll :</dt>
                            </dl>
                        </div>
                    </div>
                    <table id="selected_rolls_allocated" class="table table-bordered table-hover text-center table-vertical-align">
                        <thead>
                            <tr class="">
                                <th width="50">No</th>
                                <th width="" class="text-center">Serial Number</th>
                                <th width="100">Width</th>
                                <th width="100">YDs</th>
                                <th width="">Rack No</th>
                                <th width="">Location</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="row">
                                <dt class="col-md-4 col-sm-12">Total Roll : </dt>
                                <dd class="col-md-8 col-sm-12"><span class="total-roll"></span></dd>
                            </dl>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <dl class="row">
                                <dt class="col-md-4 col-sm-12">Total Length : </dt>
                                <dd class="col-md-8 col-sm-12"><span id="total_length"></span></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitForm('modal_fabric_issuance')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Save -->

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
    const fbr_qty_required = parseFloat(`{{ $fabric_request->qty_required }}`);
    
    // ## URL List
    const show_url = "{{ route('fabric-request.show',':id') }}";
    const issue_fabric_store_url = "{{ route('fabric-request.issue-fabric-store',':id') }}";
    const dtable_list_url = "{{ route('fabric-request.dtable-roll-list') }}";
    const fetch_select_color_url = "{{ route('fetch-select.color') }}";

    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');
    }

    const show_modal_detail = async (modal_element_id,id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Fabric Request Information ",
        }
        clear_form(modal_data);
        
        fetch_data = {
            url: show_url.replace(':id', id),
            method: "GET",
            token: token,
        }
        result = await using_fetch(fetch_data);
     
        $(`#${modal_element_id}`).modal('show');
    }

    const show_modal_issuance = async (modal_element_id, fabric_request_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Allocated Fabric to Fabric Request",
            form_action_url : issue_fabric_store_url.replace(':id',fabric_request_id),
        }
        clear_form(modal_data);
        
        fetch_data = {
            url: show_url.replace(':id', id),
            method: "GET",
            token: token,
        }
        result = await using_fetch(fetch_data);
     
        $(`#${modal_element_id}`).modal('show');
    }

    const submitForm = async (modal_id) => {
        try {
            let modal = document.getElementById(modal_id);
       
            let form = modal.querySelector('form');
            let formData = getFormData(form);

            let fetch_data = {
                url: issue_fabric_store_url,
                method: "POST",
                data: formData,
                token: token,
            }

            const response = await using_fetch(fetch_data);
            if(response.status == 'success') {
                swal_info({ title: response.message })
                
                reload_dtable();
            } else {
                swal_failed({ title: response.message })
            }

        } catch (error) {
            console.error("Error:", error);
        }

        $(`#${modal_id}`).modal('hide');
    }
    
    // ## move single tr
    const move_to_fbr = (element) => {
        let row = $(element).closest('tr'); // ## get tr based on button clicked
        let remove_button = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="remove_from_fbr(this)">Remove from FBR</a>'; // ## create remove from fbr button
        row.find('td:last').html(remove_button); // ## change last td of this tr to remove_button
        
        let number = $('#selected_roll_table').find('tr').length;
        row.find('td:first').hide(); // ## hide first td of this tr (input checkbox)
        row.find('input:checked').prop('checked', false); // ## find checked input and uncheck it
        row.prepend('<td>' + number + '</td>'); // ## add number to before first td
        
        $('#selected_roll_table tbody').append(row); // ## insert tr to selected_roll_table
        $('#fabric_roll_table').DataTable().row(row).remove().draw(); // ## remove row from datatable

        update_total_selected_roll();
    }

    // ## remove single tr
    const remove_from_fbr = (element) => {
        let row = $(element).closest('tr'); // ## get tr based on button clicked
        let move_button = '<a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="move_to_fbr(this)">Move to FBR</a>'; // ## create move to fbr button
        row.find('td:last').html(move_button); // ## change last td of this tr to move_button

        row.find('td:first').remove(); // ## remove first td of this tr (number)
        row.find('td:first').show(); // ## show first td of this tr (input checkbox)
        
        $('#fabric_roll_table tbody').append(row); // ## insert tr to fabric_roll_table
        $('#fabric_roll_table').DataTable().row.add(row).draw(); // ## add row to datatable

        update_total_selected_roll();
    }

    // ## move multiple tr via checkbox
    const move_selected_roll_to_fbr = () => {
        $('#fabric_roll_table tbody input:checked').each(function() {
            move_to_fbr(this);
            checkbox_clicked(); // ## for trigger checkbox function so checkbox all become unchecked
        });
    }


    const update_total_selected_roll = () => {
        let total_roll = $('#selected_roll_table tbody tr').length;
        $('#total_selected_roll_qty').text(total_roll);

        let total_length = 0;
        $('#selected_roll_table tbody td:nth-child(7)').each(function() {
            total_length += parseFloat($(this).text()) || 0;
        });
        let total_length_class = total_length >= fbr_qty_required ? 'text-success' : 'text-danger';
        $('#total_selected_roll_length').text(total_length).attr('class', total_length_class);
    }

    const select2_preselected_option = async ({ select2_selector, select2_url, option_id }) => {
        const select2_element = $(select2_selector);

        const fetch_result = await using_fetch({
            url: select2_url,
            method: "GET",
            data: { id: option_id },
        });
        const select_data = fetch_result.data.items;
        
        const option = new Option(select_data.text, select_data.id, true, true);
        select2_element.append(option).trigger('change');
    }

    // todo : avoid duplicate roll on table
    // todo : if apply filter deleted all selected roll and this alert 
    // todo : auto calculation total roll and length ✅
    // todo : first load page , only gl that related are show (auto select gl number) ✅
    // todo : show selected roll to modal, for confirmation
    // todo : save selected roll to database and update fabric_roll status

</script>

<script type="text/javascript">

    let fabric_roll_table = $('#fabric_roll_table').DataTable({
        processing: true,
        ajax: {
            url: dtable_list_url,
            data: function (d) {
                d.gl_filter = $('#gl_filter').val();
                d.color_filter = $('#color_filter').val();
                d.batch_filter = $('#batch_filter').val();
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
            { data: 'checkbox', name: 'checkbox',orderable: false, searchable: false },
            { data: 'color', name: 'colors.color'},
            { data: 'batch_number', name: 'packinglists.batch_number'},
            { data: 'roll_number', name: 'roll_number'},
            { data: 'width', name: 'fabric_rolls.width'},
            { data: 'yds', name: 'fabric_rolls.yds'},
            { data: 'rack_number', name: 'racks.serial_number'},
            { data: 'rack_location', name: 'locations.location'},
            { data: 'action', name: 'action'},
        ],
        columnDefs: [
            { targets: [0,-1], orderable: false, searchable: false },
        ],
        paging: false,
        responsive: true,
        lengthChange: false,
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

    let validator = $('#modal_detail_fabric_request form').validate({
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

    $('#gl_filter, #batch_filter').select2({})

    $(document).ready(async function() {

        // ## if any data match the fabric request preselect select2 filter
        @if($is_gl_number_exist)
            $('#gl_filter').val(`{{ $fabric_request->gl_number }}`).trigger('change');
        @endif
        
        @if($color_id)
            let preselect_color = {
                select2_selector: '#color_filter.select2',
                select2_url: fetch_select_color_url,
                option_id: {{$color_id}},
            };
            await select2_preselected_option(preselect_color);
        @endif

        reload_dtable(); // ## apply filter and reload the table according to the selected filter
    });

</script>

<script>
    const is_all_checked = () => {
        let all_roll_checkbox = document.querySelectorAll('#fabric_roll_table .checkbox-roll-control');
        if(all_roll_checkbox.length <= 0) { return false; }
        for (let item of all_roll_checkbox) {
            if(!item.checked) { return false; }
        }
        return true;
    }

    const is_any_checked = () => {
        let all_roll_checkbox = document.querySelectorAll('#fabric_roll_table .checkbox-roll-control');
        for (let item of all_roll_checkbox) {
            if(item.checked) { return true; }
        }
        return false;
    }

     // ## checkbox listener for always update roll_checkbox_all
    const checkbox_clicked = () => {
        let checked_status_checkbox_all = is_all_checked() ? true : false;
        document.getElementById('roll_checkbox_all').checked = checked_status_checkbox_all;

        let disabled_status_action_wrapper = is_any_checked() ? false : true;
        disabled_action_wrapper(disabled_status_action_wrapper);
    }

     const disabled_action_wrapper = (disabled_status = false) => {
        let action_wrapper = document.getElementsByClassName('action-wrapper').item(0);
        let buttons = action_wrapper.querySelectorAll('button');
        buttons.forEach(function(button) {
            button.disabled = disabled_status;
        });
    }

    const get_selected_item = () => {
        let selected_element = $('.checkbox-roll-control:checked').toArray();
        let item_id = [];
        let item_name = [];

        selected_element.forEach(element => {
            item_id.push($(element).val());
            item_name.push($(element).data('roll-number'));
        });

        return {
            item_id,
            item_name,
        }
    }
    // ## Checkbox Feature
    $('.checkbox-all-control').on('click', function(e) {
        let is_checked = $(this).prop('checked');
        let table = $(this).parents('table');
        table.find('.checkbox-roll-control').prop('checked',is_checked);
    })

    $('#roll_checkbox_all').on('change', function(e) {
        checkbox_clicked();
    })

</script>
@stop