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
            <div class="col-lg-12 col-xl-12"> 
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
            <div class="col-lg-12 col-xl-12"> 
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
                            <th width="" class="text-center">Serial Number</th>
                            <th width="100">Width</th>
                            <th width="100">YDs</th>
                            <th width="">Rack No</th>
                            <th width="">Location</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12">
                        <dl class="row">
                            <dt class="col-md-4 col-sm-12">Total Roll : <span id="total_roll"></span> - </dt>
                        </dl>
                    </div>
                </div>
                    <div class="row">
                    <div class="col-md-12">
                        <dl class="row">
                            <dt class="col-md-4 col-sm-12">Total Length : <span id="total_length"></span> - </dt>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-xl-12"> 
                <div class="col-sm-12 d-inline-flex justify-content-end" style= "margin:30px 0 15px 0;">
                    <div class="action-wrapper mr-auto">
                        @can('manage')
                            <button class="btn btn-success btn-submit" disabled="disabled" onclick="add_roll()">Add to FBR</button>
                        @endcan
                    </div>
                    <div class="filter_wrapper mr-2" style="width:150px; height:10px">                         
                        <select name="gl_filter" id="gl_filter" class="form-control select2 no-search-box">
                            <option value="" selected>All GL</option>    
                            @foreach ($packinglist as $packinglist)
                            <option value="{{$packinglist->gl_number}}">{{$packinglist->gl_number}}</option>    
                            @endforeach
                        </select>
                    </div>
                    <div class="filter_wrapper mr-2" style="width:150px;">
                        <select name="color_filter" id="color_filter" class="form-control select2">
                            <option value="" selected >All Color</option>
                        </select>
                    </div>
                    <div class="filter_wrapper mr-2" style="width:150px;">                         
                        <select name="batch_filter" id="batch_filter" class="form-control select2 no-search-box">
                            <option value="" selected>All Batch</option>  
                            <option value=""></option>  
                        </select>
                    </div>
                    <div class="action-wrapper" id="reload_table_btn">
                        <a href="javascript:void(0)" class="btn btn-info">Apply</a>
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
                            <th width="" class="text-center">Serial Number</th>
                            <th width="" class="text-center">Roll No</th>
                            <th width="">Width</th>
                            <th width="">YDs</th>
                            <th width="">Rack No</th>
                            <th width="">Location</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>     
            </div>
        </div>
        <div class="action-wrapper mr-auto" style="float: right; margin-top: 50px">
            @can('manage')
                <a href="javascript:void(0)" type="button" class="btn btn-primary"  id="btn_modal_show" onclick="show_modal_issue('modal_fabric_issue', {{$fabric_request->id}})">Save</a>
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
<div class="modal fade" id="modal_fabric_issue" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
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
                                <dd class="col-md-8 col-sm-12"><span id="total_roll"></span></dd>
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
                    <button type="button" class="btn btn-primary" onclick="submitForm('modal_fabric_issue')">Save</button>
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
    
    // ## URL List
    const show_url = "{{ route('fabric-request.show',':id') }}";
    const update_url = "{{ route('fabric-request.update',':id') }}";
    const show_roll_url = "{{ route('fabric-request.show-roll',':id') }}";
    const store_url = "{{ route('fabric-request.store') }}";
    const dtable_list_url = "{{ route('fabric-request.dtable-roll-list') }}";
    const fetch_select_color_url = "{{ route('fetch-select.color') }}";

    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');
    }

    const insert_roll_to_selected_table = (carton_data) => {
        let selected_roll_table_first_row = $('#selected_roll_table tbody').find('td').length;
        if(selected_roll_table_first_row <= 1) {
            $('#selected_roll_table tbody').html('');
        };
        $('#selected_roll_table tbody').append(carton_data);

        update_total_scanned_carton();
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

    const show_modal_issue = async (modal_element_id,id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Allocated Fabric to Fabric Request",
            form_action_url : store_url,
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
                url: store_url,
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

    function add_roll(){
        element_html = create_roll_element({button_type: "button-delete"});
        $('#selected_roll_table > tbody').append(element_html);
    }

    const create_roll_element = () => {
        let selected_roll = get_selected_item();

        let fabric_rolls_element = '';

        let button_element = `
            <a href="javascript:void(0);" class="btn btn-danger btn-sm p-2 btn-style-delete"><i class="fas fa-trash-alt"></i></a>
            `;
        
        if(selected_roll.item_id.length > 0) {
            selected_roll.item_id.forEach(fabric_roll => {
            fabric_rolls_element += `
        <tr>
            <td>${selected_roll.item_id}</td>
            <td></td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td width="50" class="text-center">
                ${button_element}
            </td>
        </tr>
        `;
        }); 

        } else {
            fabric_rolls_element = '<tr style="text-align: center"><td colspan="8">There is no data fabric roll</td></tr>';
        }
        return fabric_rolls_element;
    }

    $('#selected_roll_table > tbody').on("click",".btn-style-delete", function(e){ 
        e.preventDefault();
        $(this).parent().parent().remove();
    });

</script>

<script type="text/javascript">
    let fabric_roll_table = $('#fabric_roll_table').DataTable({
        processing: true,
        serverSide: true,
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
            { data: 'serial_number', name: 'fabric_rolls.serial_number'},
            { data: 'roll_number', name: 'CAST(fabric_rolls.roll_number AS SIGNED)'},
            { data: 'width', name: 'fabric_rolls.width'},
            { data: 'yds', name: 'fabric_rolls.yds'},
            { data: 'rack_number', name: 'racks.rack_number'},
            { data: 'rack_location', name: 'locations.location'},
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


    $('#gl_filter, #color_filter, #batch_filter').change(function(event) {
        reload_dtable();
    }); 
</script>

<script>
    const is_all_checked = () => {
    let all_roll_checkbox = document.getElementsByClassName('checkbox-roll-control');
    if(all_roll_checkbox.length <= 0) { return false; }
    for (let item of all_roll_checkbox) {
        if(!item.checked) { return false; }
    }
    return true;
    }

    const is_any_checked = () => {
        let all_roll_checkbox = document.getElementsByClassName('checkbox-roll-control');
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