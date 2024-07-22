@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3 my-auto"> Rack Location </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row  mb-3">
                    <div class="col-sm-12 d-inline-flex justify-content-end">
                        <div class="action-wrapper mr-auto">
                            @can('manage')
                                <button class="btn btn-primary btn-sm" disabled="disabled" onclick="show_modal_change('modal_change_rack_location')">Change Location</button>
                            @endcan
                        </div>
                         <div class="filter_wrapper mr-2" style="width:200px;">
                            <select name="rack_type_filter" id="rack_type_filter" class="form-control select2 no-search-box">
                                <option value="">All Rack Type</option>
                                <option value="moveable" selected > Moveable Rack </option>
                                <option value="fixed"> Fixed Rack </option>
                            </select>
                        </div>
                         <div class="filter_wrapper mr-2" style="width:200px;">
                            <select name="rack_allocation_filter" id="rack_allocation_filter" class="form-control select2 no-search-box">
                                <option value="" selected>All Data</option>
                                <option value="allocated"> Allocated Rack </option>
                                <option value="unallocated"> Unallocated Rack </option>
                            </select>
                        </div>
                        <div class="filter_wrapper text-right align-self-center">
                            <button id="reload_table_btn" class="btn btn-sm btn-info">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <table id="rack_location_table" class="table table-bordered table-hover text-center">
                    <thead>
                        <tr class="">
                            <th width="30">
                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input 
                                            id="rack_checkbox_all" 
                                            class="custom-control-input checkbox-all-control" 
                                            type="checkbox"
                                        >
                                        <label for="rack_checkbox_all" class="custom-control-label"></label>
                                    </div>
                                </div>
                            </th>
                            <th width="">No</th>
                            <th width="250">Rack</th>
                            <th width="">Location</th>
                            <th width="">GL Number</th>                            
                            <th width="">Color</th>
                            <th width="">Batch</th>
                            <th width="">Total Roll</th>
                            <th width="150">Action</th>
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

<!-- Modal Put Rack to Location -->
<div class="modal fade" id="modal_change_rack_location" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Select Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="selected_rack_id" name="selected_rack_id">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <h6 class="title text-bold">Selected Rack: </h6>
                            <div id="display_rack_number"></div>
                            <p id="total_selected_rack" class="title mt-3 mb-0 text-bold"> </p>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="location">Location</label>
                        <select name="location" id="location" class="form-control select2">
                            <option value=""> Warehouse </option>
                            @foreach ($locations as $location)
                            <option value="{{ $location->id }}"> {{ $location->location }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-submit" onclick="submitForm('modal_change_rack_location')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Put Rack to Location -->
@endsection

@section('js')
<script type="text/javascript">
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const column_visible = '{{ $can_manage }}';

    // ## URL List
    const show_url = "{{ route('rack-location.show',':id') }}";
    const store_url = "{{ route('rack-location.store') }}";
    const dtable_url = "{{ route('rack-location.dtable') }}";
    const fetch_select_rack_url = "{{ route('fetch-select.rack') }}";

    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');
    }

    const show_modal_change = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Select Location",
            btn_submit : "Save",
            form_action_url : store_url,
        }
        clear_form(modal_data);
        

        let selected_rack = get_selected_item();

        if(selected_rack.item_id.length <= 0) {
            swal_warning({title: "Please select at least one rack"});
            return false;
        }

        let selected_rack_number = selected_rack.item_name.sort(function(a, b){return a-b});
        let rack_number_element = '';
        selected_rack_number.forEach(rack_name => {
            rack_number_element += `<span class="badge bg-maroon mr-1">Rack ${rack_name}</span>`;
        });
        $('#display_rack_number').html(rack_number_element)
        $('#total_selected_rack').text(`Total Rack: ${selected_rack.item_id.length}`)
        $('#selected_rack_id').val(selected_rack.item_id);

        $(`#${modal_element_id}`).modal('show');
    }

    const show_modal_edit = async (modal_element_id, element) => {

        let rack_id = $(element).data('rack-id');
        let rack_number = $(element).data('rack-number');

        let modal_data = {
            modal_id : modal_element_id,
            title : "Select Location",
            btn_submit : "Save",
            form_action_url : store_url,
        }
        clear_form(modal_data);
        
        let rack_number_element = `<span class="badge bg-maroon mr-1">Rack ${rack_number}</span>`;
        $('#display_rack_number').html(rack_number_element);
        $('#total_selected_rack').text(``);
        $('#selected_rack_id').val(rack_id);
        
        $(`#${modal_element_id}`).modal('show');
    }

    const submitForm = async (modal_id) => {
        try {
            let modal = document.getElementById(modal_id);
            let submit_btn = modal.querySelector('.btn-submit');
            submit_btn.setAttribute('disabled', 'disabled');
            
            let form = modal.querySelector('form');
            let formData = getFormData(form);

            if (!$(form).valid()) {
                submit_btn.removeAttribute('disabled');
                return false;
            }

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

            submit_btn.removeAttribute('disabled');

        } catch (error) {
            console.error("Error:", error);
        }

        $(`#${modal_id}`).modal('hide');
    }

   const getValidationRules = () => {
        return {
            rack: {
                required: true,
            },
        };
    }
    const getValidationMessages = () => {
        return {
            rack: {
                required: "Please select Rack Number",
            },
        };
    }

    const is_all_checked = () => {
        let all_rack_checkbox = document.getElementsByClassName('checkbox-rack-control');
        if(all_rack_checkbox.length <= 0) { return false; }
        for (let item of all_rack_checkbox) {
            if(!item.checked) { return false; }
        }
        return true;
    }

    const is_any_checked = () => {
        let all_rack_checkbox = document.getElementsByClassName('checkbox-rack-control');
        for (let item of all_rack_checkbox) {
            if(item.checked) { return true; }
        }
        return false;
    }

     // ## checkbox listener for always update rack_checkbox_all
    const checkbox_clicked = () => {
        let checked_status_checkbox_all = is_all_checked() ? true : false;
        document.getElementById('rack_checkbox_all').checked = checked_status_checkbox_all;

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
        let selected_element = $('.checkbox-rack-control:checked').toArray();
        let item_id = [];
        let item_name = [];

        selected_element.forEach(element => {
            item_id.push($(element).val());
            item_name.push($(element).data('rack-number'));
        });

        return {
            item_id,
            item_name
        }
    }

</script>

<script type="text/javascript">
    let rack_location_table = $('#rack_location_table').DataTable({
        processing: true,
        ajax: {
            url: dtable_url,
            data: function (d) {
                d.rack_allocation_filter = $('#rack_allocation_filter').val();
                d.rack_type_filter = $('#rack_type_filter').val();
            },
            beforeSend: function() {
                // ## Tambahkan kelas dimmed-table sebelum proses loading dimulai
                $('#rack_location_table').addClass('dimmed-table').append('<div class="datatable-overlay"></div>');
            },
            complete: function() {
                // ## Hapus kelas dimmed-table setelah proses loading selesai
                $('#rack_location_table').removeClass('dimmed-table').find('.datatable-overlay').remove();
                checkbox_clicked();
            },
        },
        order: [2,'asc'],
        columns: [
            { data: 'checkbox', name: 'checkbox', visible: column_visible},
            { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            { data: 'serial_number', name: 'serial_number'},
            { data: 'location', name: 'locations.location'},
            { data: 'gl_number', name: 'gl_number'},
            { data: 'color', name: 'color'},
            { data: 'batch', name: 'batch'},
            { data: 'total_roll', name: 'total_roll'},
            { data: 'action', name: 'action', visible: column_visible},
        ],
        columnDefs: [
            { targets: [1,0,-1], orderable: false, searchable: false },
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
        rack_location_table.ajax.reload(function(json){
            $('#reload_table_btn').removeClass('loading').attr('disabled',false);
        });
    });

    // ## Form Validator
    let validator = $('#modal_change_rack_location form').validate({
        rules: getValidationRules(),
        messages: getValidationMessages(),
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);

            // ## khusus untuk select2
            if (element.hasClass('select2-hidden-accessible')) {
                error.insertAfter(element.next('span.select2-container'));
            }

            // ## validasi error pada select2
            if (!$(element).val()) {
                $(element).parent().find('.select2-container').addClass('select2-container--error');
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });

    $('#location.select2').select2({
        dropdownParent: $('#modal_change_rack_location'),
    });

    $('#rack_allocation_filter, #rack_type_filter').change(function(event) {
        reload_dtable();
    });  
</script>

<script>
    // ## Checkbox Feature
    $('.checkbox-all-control').on('click', function(e) {
        let is_checked = $(this).prop('checked');
        let table = $(this).parents('table');
        table.find('.checkbox-rack-control').prop('checked',is_checked);
    })

    $('#rack_checkbox_all').on('change', function(e) {
        checkbox_clicked();
    })

</script>
@stop