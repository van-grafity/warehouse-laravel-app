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
            <div class="row">
                <div class="col-sm-12">
                    <h5 style="font-weight:bold">Serial Number :  {{ $fabric_request->apiFabricRequest->fbr_serial_number }} </h5>
                </div> 
                <div class="col-sm-5">
                    <dl class="row"> 
                        <dt class="col-md-4 col-sm-12">Gl Number</dt>
                        <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_gl_number }}</dd>

                        <dt class="col-md-4 col-sm-12">Color </dt>
                        <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_color }}</dd>

                        <dt class="col-md-4 col-sm-12">Style</dt>
                        <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_style }} </dd>
                    
                        <dt class="col-md-4 col-sm-12">Po Number</dt>
                        <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_fabric_po }} </dd>

                        <dt class="col-md-4 col-sm-12">Table No</dt>
                        <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_table_number }} </dd>

                        <dt class="col-md-4 col-sm-12">Status</dt>
                        <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_status_print }} </dd>
                    </dl>
                </div>
                <div class="col-sm-7">
                    <dl class="row">
                        <dt class="col-md-4 col-sm-12">Qty Required</dt>
                        <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_qty_required }} Yds</dd>

                        <dt class="col-md-4 col-sm-12">Qty Issued</dt>
                        <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->qty_issued }} Yds </dd>
                        
                        <dt class="col-md-4 col-sm-12">Difference</dt>
                        <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->qty_difference }} Yds </dd>

                        <dt class="col-md-4 col-sm-12">Cutting Remark</dt>
                        <dd class="col-md-8 col-sm-12"> : {{ ($fabric_request->apiFabricRequest->fbr_remark) ? $fabric_request->apiFabricRequest->fbr_remark : '-' }} </dd>

                        <dt class="col-md-4 col-sm-12">Fabric Detail</dt>
                        <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_fabric_type }} </dd>

                    </dl>
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
                            <th style="width: 50px">No</th>
                            <th style="width: auto" class="text-center">Color</th>
                            <th style="width: auto" class="text-center">Batch No.</th>
                            <th style="width: 100px" class="text-center">Roll No.</th>
                            <th style="width: 75px">Width</th>
                            <th style="width: 50px">YDs</th>
                            <th style="width: 150px">Rack No.</th>
                            <th style="width: 75px">Location</th>
                            <th style="width: 150px">Action</th>
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
                        <dl class="row">
                            <dt class="col-md-4 col-sm-12">Total Length : <span id="total_selected_roll_length"> 0 </span> (<span id="qty_difference"></span>) Yds </dt>
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
                    <div class="filter_wrapper mr-2" style="width:200px; height:10px">                         
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
                            <th style="width:30px">
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
                            <th style="width:auto" class="text-center">Color</th>
                            <th style="width:auto" class="text-center">Batch No.</th>
                            <th style="width:auto" class="text-center">Roll No</th>
                            <th style="width:75px">Width</th>
                            <th style="width:50px">YDs</th>
                            <th style="width:100px">Rack No</th>
                            <th style="width:75px">Location</th>
                            <th style="width:120px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>     
            </div>
        </div>
        <div class="action-wrapper mr-auto" style="float: right; margin-top: 50px">
            @can('manage')
                <a href="javascript:void(0)" type="button" class="btn btn-primary" id="btn_modal_show" onclick="show_modal_confirmation('modal_issuance_confirmation')"> Save </a>
            @endcan
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<!-- Modal Issuance Confirmation -->
<div class="modal fade" id="modal_issuance_confirmation" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Confirm the Issuance</h5>
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
                                        <dd class="col-md-8 col-sm-12"> : <span id="confirm_qty_issued"> - </span> Yds </dd>
                                        <dt class="col-md-4 col-sm-12">Difference</dt>
                                        <dd class="col-md-8 col-sm-12"> : <span id="confirm_qty_difference"> - </span> Yds </dd>
                                        <dt class="col-md-4 col-sm-12">Cutting Remark</dt>
                                        <dd class="col-md-8 col-sm-12"> : {{ ($fabric_request->apiFabricRequest->fbr_remark) ? $fabric_request->apiFabricRequest->fbr_remark : '-' }}  </dd>
                                        <dt class="col-md-4 col-sm-12">Fabric Detail</dt>
                                        <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_fabric_type }}  </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <dl class="row">
                                        <dt class="col-md-4 col-sm-12">Status</dt>
                                        <dd class="col-md-8 col-sm-12"> : {{ $fabric_request->apiFabricRequest->fbr_status_print }}  </dd>
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
                    <table id="confirm_selected_roll_table" class="table table-bordered table-hover text-center table-vertical-align mb-4">
                        <thead>
                            <tr class="">
                                <th style="width: 50px">No</th>
                                <th style="width: auto" class="text-center">Color</th>
                                <th style="width: auto" class="text-center">Batch No.</th>
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
                                <dt class="col-md-4 col-sm-12">Total Roll : <span id="confirm_total_selected_roll_qty"> 0 </span></dt>
                            </dl>
                            <dl class="row">
                                <dt class="col-md-4 col-sm-12">Total Length : <span id="confirm_total_selected_roll_length"> 0 </span> Yds</dt>
                            </dl>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="remark" class="col-form-label">Warehouse Remark</label> <i>(Optional)</i>
                                <textarea class="form-control" name="remark" id="remark">{{ $fabric_request->remark }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-submit" onclick="submitForm('modal_issuance_confirmation')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Issuance Confirmation -->

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
    const fbr_id = `{{ $fabric_request->id }}`;
    const fbr_qty_required = parseFloat(`{{ $fabric_request->apiFabricRequest->fbr_qty_required }}`);
    const allocated_fabric_rolls = @json($allocated_fabric_roll);

    let confirmed_fabric_roll = [];
    if(allocated_fabric_rolls.length > 0) {
        confirmed_fabric_roll = allocated_fabric_rolls.map(fabric_roll => fabric_roll.id.toString());
    }

    // ## URL List
    const issue_fabric_store_url = "{{ route('fabric-request.issue-fabric-store',':id') }}";
    const dtable_list_url = "{{ route('fabric-request.dtable-roll-list') }}";
    const fetch_select_color_url = "{{ route('fetch-select.color') }}";

    const reload_dtable = () => {
        return new Promise((resolve, reject) => {
            let fabric_roll_table = $('#fabric_roll_table').DataTable();
            fabric_roll_table.ajax.reload(function(json) {
                resolve();
            });
        });
    };

    const show_modal_confirmation = async (modal_element_id) => {
        if(is_table_data_empty()){
            swal_warning({title: "Please select at least one roll"});
            return false;
        }

        // ## get the necessary data and assign to variables
        let total_selected_roll_qty = parseInt($('#total_selected_roll_qty').text());
        let total_selected_roll_length = parseFloat($('#total_selected_roll_length').text());
        let qty_difference = total_selected_roll_length > fbr_qty_required ? `+ ${(total_selected_roll_length - fbr_qty_required).toFixed(2)}` : `- ${(fbr_qty_required - total_selected_roll_length).toFixed(2)}`;
        
        // ## complete fabric request data
        $('#confirm_qty_issued').text(total_selected_roll_length);
        $('#confirm_qty_difference').text(qty_difference);

        // ## add selected fabric rolls to the preview table
        let selected_roll_data_row = $('#selected_roll_table tbody tr').clone();
        selected_roll_data_row.find('td:last, td:nth-child(2)').remove();
        $('#confirm_selected_roll_table tbody').html(selected_roll_data_row);

        // ## add total data
        $('#confirm_total_selected_roll_qty').text(total_selected_roll_qty)
        $('#confirm_total_selected_roll_length').text(total_selected_roll_length)

        $(`#${modal_element_id}`).modal('show');
    };

    const submitForm = async (modal_id) => {
        try {
            let modal = document.getElementById(modal_id);
            let submit_btn = modal.querySelector('.btn-submit');
            
            let form = modal.querySelector('form');
            let formData = getFormData(form);

            fetch_data = {
                url: issue_fabric_store_url.replace(':id', fbr_id),
                method: "POST",
                data: {
                    confirmed_fabric_roll : confirmed_fabric_roll,
                    remark : formData.remark,
                },
                token: token,
            };

            const response = await using_fetch(fetch_data);
            if(response.status == 'success') {
                swal_info({ title: response.message, reload_option: false })
                
            } else {
                swal_failed({ title: response.message })
            }

        } catch (error) {
            console.error("Error:", error);
        }

        $(`#${modal_id}`).modal('hide');
    };
    
    // ## move single tr
    const move_to_fbr = (element) => {
        let row = $(element).closest('tr'); // ## get tr based on button clicked

        // ## get selected_roll_id from input with name selected_roll[]
        let selected_roll_id = row.find('input[name="selected_roll[]"]').val();

        // ## add the selected_roll_id to confirmed_fabric_roll if not already exist
        if (!confirmed_fabric_roll.includes(selected_roll_id)) {
            confirmed_fabric_roll.push(selected_roll_id);
        }

        let remove_button = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="remove_from_fbr(this)">Remove from FBR</a>'; // ## create remove from fbr button
        row.find('td:last').html(remove_button); // ## change last td of this tr to remove_button
        
        let number = is_table_data_empty() ? 1 : $('#selected_roll_table').find('tr').length;
        row.find('td:first').hide(); // ## hide first td of this tr (input checkbox)
        row.find('input:checked').prop('checked', false); // ## find checked input and uncheck it
        row.prepend('<td>' + number + '</td>'); // ## add number to before first td
        
        $('#selected_roll_table tbody').append(row); // ## insert tr to selected_roll_table
        $('#fabric_roll_table').DataTable().row(row).remove().draw(); // ## remove row from datatable

        selected_roll_table_listener();
        update_total_selected_roll();
    };

    // ## remove single tr
    const remove_from_fbr = (element) => {
        let row = $(element).closest('tr'); // ## get tr based on button clicked
        
        // ## get selected_roll_id from input with name selected_roll[]
        let selected_roll_id = row.find('input[name="selected_roll[]"]').val();

        // ## remove the selected_roll_id from confirmed_fabric_roll if exist
        let index_of_fabric_roll_id = confirmed_fabric_roll.indexOf(selected_roll_id);
        if (index_of_fabric_roll_id > -1) {
            confirmed_fabric_roll.splice(index_of_fabric_roll_id, 1);
        }

        let move_button = '<a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="move_to_fbr(this)">Move to FBR</a>'; // ## create move to fbr button
        row.find('td:last').html(move_button); // ## change last td of this tr to move_button

        row.find('td:first').remove(); // ## remove first td of this tr (number)
        row.find('td:first').show(); // ## show first td of this tr (input checkbox)
        
        $('#fabric_roll_table tbody').append(row); // ## insert tr to fabric_roll_table
        $('#fabric_roll_table').DataTable().row.add(row).draw(); // ## add row to datatable

        selected_roll_table_listener();
        update_total_selected_roll();
    };

    // ## move multiple roll to fbr via checkbox
    const move_selected_roll_to_fbr = () => {
        $('#fabric_roll_table tbody input:checked').each(function() {
            move_to_fbr(this);
        });
        checkbox_clicked(); // ## for trigger checkbox function so checkbox all become unchecked
    };

    // ## remove multiple roll from fbr
    const remove_all_selected_roll_from_fbr = () => {
        confirmed_fabric_roll = [];

        fill_table_with_default_data({
            table_selector : '#selected_roll_table',
            num_columns : 9,
            default_data : 'No fabric roll selected'
        });
        checkbox_clicked(); // ## for trigger checkbox function so checkbox all become unchecked
        update_total_selected_roll();
    };

    // ## for always update value on total section
    const update_total_selected_roll = () => {
        let total_roll = is_table_data_empty() ? 0 : $('#selected_roll_table tbody tr').length;
        $('#total_selected_roll_qty').text(total_roll);

        let total_length = 0;
        $('#selected_roll_table tbody td:nth-child(7)').each(function() {
            total_length += parseFloat($(this).text()) || 0;
        });
        let total_length_class = total_length >= fbr_qty_required ? 'text-success' : 'text-danger';
        $('#total_selected_roll_length').text(total_length).attr('class', total_length_class);

        let total_selected_roll_length = parseFloat($('#total_selected_roll_length').text());

        let qty_difference = total_selected_roll_length > fbr_qty_required ? `+ ${(total_selected_roll_length - fbr_qty_required).toFixed(2)}` : `- ${(fbr_qty_required - total_selected_roll_length).toFixed(2)}`;
        $('#qty_difference').text(qty_difference);
       
    };

    // ## check if the table data is empty including default data
    const is_table_data_empty = () => {
        let is_empty = true;
        $('#selected_roll_table tbody tr').each(function() {
            if ($(this).find('td').length > 1) {
                is_empty = false;
                return false; // ## exit loop early if non-default data found
            }
        });
        return is_empty;
    };

    // ## Function to update the display of selected_roll_table based on its data availability
    const selected_roll_table_listener = () => {
        if(is_table_data_empty()) {
            // ## Display default data if the table is empty
            fill_table_with_default_data({
                table_selector : '#selected_roll_table',
                num_columns : 9,
                default_data : 'No fabric roll selected'
            });
        } else {
            // ## Remove default row if table is not empty
            $('#selected_roll_table tbody tr.empty-row-table').remove();
            refresh_table_number({ table_selector: '#selected_roll_table'});
        }
    };

    const refresh_table_number = ({ table_selector }) => {
        $(`${table_selector} tbody tr:not(.empty-row-table)>td:first-child`).each(function(index, element) {
            $(element).text(index + 1);
        });
    };

    // ## function to populate data into a select2 element
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
    };


    const insert_into_selected_roll_table = (allocated_fabric_roll) => {
        let selected_roll_data_rows = allocated_fabric_roll.map((fabric_roll, index) => {
            return `
                <tr role="row" class="odd">
                    <td>${index + 1}</td>
                    <td style="display: none;">
                        <div class="form-group mb-0">
                            <div class="custom-control custom-checkbox">
                                <input id="roll_checkbox_${fabric_roll.id}" name="selected_roll[]" class="custom-control-input checkbox-roll-control" type="checkbox" value="${fabric_roll.id}" data-roll-number="${fabric_roll.serial_number}" onchange="checkbox_clicked()">
                                <label for="roll_checkbox_${fabric_roll.id}" class="custom-control-label"></label>
                            </div>
                        </div>
                    </td>
                    <td>${fabric_roll.color}</td>
                    <td>${fabric_roll.batch}</td>
                    <td>${fabric_roll.roll_number}</td>
                    <td>${fabric_roll.width}</td>
                    <td>${fabric_roll.yds}</td>
                    <td>${fabric_roll.rack_number}</td>
                    <td>${fabric_roll.location}</td>
                    <td><a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="remove_from_fbr(this)">Remove from FBR</a></td>
                </tr>`;
        });

        $('#selected_roll_table tbody').html(selected_roll_data_rows.join(''));
    };

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

    $('#reload_table_btn').on('click', async function(event) {
        try {
            let gl_number = $('#gl_filter').val();
            if(!gl_number) {
                swal_warning({title: "Please select GL Number first!"});
                return false;
            }

            let swal_data = {
                title: "Are you Sure?",
                text: "Applying the filter will reset your selected fabric roll",
                icon: "warning",
                confirmButton: "OK",
                confirmButtonClass: "btn-primary",
                cancelButtonClass: "btn-secondary"
            };
            let confirm_delete = await swal_confirm(swal_data);
            if(!confirm_delete) { return false; };

            $(this).addClass('loading').attr('disabled',true);
            await reload_dtable();
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);

            remove_all_selected_roll_from_fbr();
            insert_into_selected_roll_table(allocated_fabric_rolls);

        } catch (error) {
            console.log(error);
        }
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

    $('#gl_filter, #batch_filter').select2({});

    $(document).ready(async function() {

        // ## Preselect select2 filter if there is data matching the fabric request
        @if($is_gl_number_exist)
            $('#gl_filter').val(`{{ $fabric_request->apiFabricRequest->fbr_gl_number }}`).trigger('change');
        @endif
        
        // todo : kayaknya bagian sini
        // @if(!$is_gl_number_exist)
        //     return false;
        // @endif
        
        @if($color_id)
            let preselect_color = {
                select2_selector: '#color_filter.select2',
                select2_url: fetch_select_color_url,
                option_id: {{$color_id}},
            };
            await select2_preselected_option(preselect_color);
        @endif
        
        reload_dtable(); // ## apply filter and reload the table according to the selected filter
        selected_roll_table_listener();
        insert_into_selected_roll_table(allocated_fabric_rolls);
        update_total_selected_roll();
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
    };

    const is_any_checked = () => {
        let all_roll_checkbox = document.querySelectorAll('#fabric_roll_table .checkbox-roll-control');
        for (let item of all_roll_checkbox) {
            if(item.checked) { return true; }
        }
        return false;
    };

    // ## checkbox listener for always update roll_checkbox_all
    const checkbox_clicked = () => {
        let checked_status_checkbox_all = is_all_checked() ? true : false;
        document.getElementById('roll_checkbox_all').checked = checked_status_checkbox_all;

        let disabled_status_action_wrapper = is_any_checked() ? false : true;
        disabled_action_wrapper(disabled_status_action_wrapper);
    };

     const disabled_action_wrapper = (disabled_status = false) => {
        let action_wrapper = document.getElementsByClassName('action-wrapper').item(0);
        let buttons = action_wrapper.querySelectorAll('button');
        buttons.forEach(function(button) {
            button.disabled = disabled_status;
        });
    };

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
        };
    };
    
    // ## Checkbox Feature
    $('.checkbox-all-control').on('click', function(e) {
        let is_checked = $(this).prop('checked');
        let table = $(this).parents('table');
        table.find('.checkbox-roll-control').prop('checked',is_checked);
    });

    $('#roll_checkbox_all').on('change', function(e) {
        checkbox_clicked();
    });

</script>
@stop