@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h3 class="">@yield('page_title')</h3>
    </div>
</div>
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
                    @can('manage')
                        <a href="javascript:void(0)" class="btn btn-success " id="btn_modal_create" onclick="show_modal_create('modal_fabric_roll')" >Create</a>
                    @endcan
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-12 d-flex">
                        <div class="action-wrapper mr-auto">
                            @can('manage')
                                <button class="btn btn-sm btn-danger" disabled="disabled" onclick="mass_delete_roll()" ><i class="fas fa-trash-alt"></i></button>
                            @endcan
                            @can('print')
                                <button class="btn btn-sm btn-primary" disabled="disabled" onclick="print_qrcode_btn()">
                                    <i class="fas fa-print"></i> Print QR Code
                                </button>
                            @endcan    
                        </div>
                                                
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
                            <th width="" class="text-center">Roll Number</th>
                            <th width="" class="text-center">Serial Number</th>
                            <th width="">KGs</th>
                            <th width="">LBs</th>
                            <th width="">YDs</th>
                            <th width="">Width</th>
                            <th width="120">Action</th>
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

<!-- Back Button -->
<div class="row text-right mb-5">
    <div class="col-12">
        @php $back_url = (url()->previous() == url()->current()) ? url('packinglist') : url()->previous() @endphp
        <a href="<?= $back_url ?>" class="btn btn-secondary"><i class="fas fa-arrow-alt-circle-left mr-1"></i>Back</a>
    </div>
</div>

<!-- Modal Add and Edit Fabric Roll -->
<div class="modal fade" id="modal_fabric_roll" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">
                <input type="hidden" name="edit_fabric_roll_id" value="" id="edit_fabric_roll_id">
                <input type="hidden" name="packinglist_id" value="" id="packinglist_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Add New Fabric Roll</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="roll_number" class="col-form-label">Roll Number</label>
                        <input type="text" class="form-control" id="roll_number" name="roll_number" required>
                    </div>
                    <div class="form-group">
                        <label for="kgs" class="col-form-label">KGs</label>
                        <input type="number" class="form-control" id="kgs" name="kgs" step=".01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="lbs" class="col-form-label">LBs</label>
                        <input type="number" class="form-control" id="lbs" name="lbs" step=".01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="yds" class="col-form-label">YDs</label>
                        <input type="number" class="form-control" id="yds" name="yds" step=".01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="width" class="col-form-label">Width</label>
                        <input type="number" class="form-control" id="width" name="width" step=".01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-submit" onclick="submitForm('modal_fabric_roll')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Add and Edit Fabric Roll -->
@endsection


@section('js')
<script type="text/javascript">

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const packinglist_id = '{{ $packinglist->id }}';
    const column_action = '{{ $can_manage }}';
    const column_checkbox = ('{{ $can_manage }}' || '{{ $can_print }}');

    
    // ## URL List
    const show_url = "{{ route('fabric-roll.show',':id') }}";
    const store_url = "{{ route('fabric-roll.store') }}";
    const update_url = "{{ route('fabric-roll.update',':id') }}";
    const delete_url = "{{ route('fabric-roll.destroy',':id') }}";
    const mass_delete_url = "{{ route('fabric-roll.mass-delete') }}";
    const dtable_url = "{{ route('fabric-roll.dtable') }}";
    const packinglist_information_url = "{{ route('packinglist.information-card', ':id') }}";
    const print_qrcode_url = "{{ route('packinglist.print-qrcode') }}";

    
    const show_modal_create = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Add New Roll",
            btn_submit : "Save",
            form_action_url : store_url,
        }
        clear_form(modal_data);
        $('#packinglist_id').val(packinglist_id);
        $('#roll_number').attr('readonly',false);
        
        $(`#${modal_element_id}`).modal('show');
    }

    const show_modal_edit = async (modal_element_id, fabric_roll_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Edit Roll",
            btn_submit : "Save",
            form_action_url : update_url.replace(':id',fabric_roll_id),
        }
        clear_form(modal_data);

        fetch_data = {
            url: show_url.replace(':id',fabric_roll_id),
            method: "GET",
            token: token,
        }
        result = await using_fetch(fetch_data);
        fabric_roll = result.data.fabric_roll;

        $('#roll_number').val(fabric_roll.roll_number);
        $('#kgs').val(fabric_roll.kgs);
        $('#lbs').val(fabric_roll.lbs);
        $('#yds').val(fabric_roll.yds);
        $('#width').val(fabric_roll.width);
        $('#edit_fabric_roll_id').val(fabric_roll.id);
        
        $('#roll_number').attr('readonly',true);
        
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
            
            let fetch_data = {};
            if(!formData.edit_fabric_roll_id) {
                // ## kalau tidak ada fabric roll id berarti STORE dan Method nya POST
                fetch_data = {
                    url: store_url,
                    method: "POST",
                    data: formData,
                    token: token,
                }
            } else {
                // ## kalau ada fabric roll id berarti UPDATE dan Method nya PUT
                fetch_data = {
                    url: update_url.replace(':id',formData.edit_fabric_roll_id),
                    method: "PUT",
                    data: formData,
                    token: token,
                }
            }

            const response = await using_fetch(fetch_data);
            if(response.status == 'success') {
                swal_info({ title: response.message })
                
                reload_dtable();

                $(`#${modal_id}`).modal('hide');
            } else {
                toastr.error(response.message)
            }

            submit_btn.removeAttribute('disabled');

        } catch (error) {
            console.error("Error:", error);
            swal_failed({ title: "An error occurred while processing the form." });
            let modal = document.getElementById(modal_id);
            let submit_btn = modal.querySelector('.btn-submit');
            submit_btn.removeAttribute('disabled');
        }

    }

    const show_modal_delete = async (fabric_roll_id) => {
        swal_data = {
            title: "Delete this roll?",
            text: "Roll will be remove from this packinglist",
            icon: "warning",
            confirmButton: "Delete Roll",
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary"
        };
        let confirm_delete = await swal_confirm(swal_data);
        if(!confirm_delete) { return false; };

        params_data = { id : fabric_roll_id };
        fetch_data = {
            url: delete_url.replace(':id',fabric_roll_id),
            method: "DELETE",
            data: params_data,
            token: token,
        }
        result = await using_fetch(fetch_data);

        if(result.status == "success"){
            swal_info({
                title : result.message,
            });

            reload_dtable();
        } else {
            swal_failed({ title: result.message });
        }
    }

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

    const getValidationRules = () => {
        return {
            roll_number: {
                required: true,
            },
            kgs: {
                required: true,
            },
            lbs: {
                required: true,
            },
            yds: {
                required: true,
            },
        };
    }
    const getValidationMessages = () => {
        return {
            roll_number: {
                required: "Please select Roll Number",
            },
            kgs: {
                required: "Please enter the KGs",
            },
            lbs: {
                required: "Please enter the LBs",
            },
            yds: {
                required: "Please enter the YDs",
            },
        };
    }

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
        if(document.getElementById('roll_checkbox_all')){
            document.getElementById('roll_checkbox_all').checked = checked_status_checkbox_all;
        }

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
        let selected_item_value = [];

        selected_element.forEach(element => {
            selected_item_value.push($(element).val());
        });
        return selected_item_value;
    }

    const mass_delete_roll = async () => {
        let selected_roll = get_selected_item();

        swal_data = {
            title: `Want to delete ${selected_roll.length} selected roll?`,
            text: "selected roll will be remove from this packinglist",
            icon: "warning",
            confirmButton: "Delete Roll",
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary"
        };
        let confirm_delete = await swal_confirm(swal_data);
        if(!confirm_delete) { return false; };

        params_data = { selected_roll_id : selected_roll };
        fetch_data = {
            url: mass_delete_url,
            method: "DELETE",
            data: params_data,
            token: token,
        }
        result = await using_fetch(fetch_data);

        if(result.status == "success"){
            swal_info({
                title : result.message,
            });
            disabled_action_wrapper(true); // ## disabled button inside action_wrapper
            reload_dtable();
        } else {
            swal_failed({ title: result.message });
        }

        $('#roll_checkbox_all').prop('checked', false);
    }

    const print_qrcode_btn = async () => {
        let selected_print = get_selected_item();

        if(selected_print.length > 0) {
            window.open(print_qrcode_url + "?id=" + selected_print, '_blank');
        } else {
            Swal.fire({
                title: 'Error',
                text: 'Please select at least one fabric roll',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    };

</script>

<script type="text/javascript">
    let fabric_roll_table = $('#fabric_roll_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_url,
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
                checkbox_clicked();
            },
        },
        order: [],
        columns: [
            { data: 'checkbox', orderable: false, searchable: false, visible: column_checkbox },
            { data: 'roll_number', name: 'roll_number'},
            { data: 'serial_number', name: 'serial_number'},
            { data: 'kgs', name: 'kgs'},
            { data: 'lbs', name: 'lbs'},
            { data: 'yds', name: 'yds'},
            { data: 'width', name: 'width'},
            { data: 'action', name: 'action', orderable: false, searchable: false, visible: column_action },
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


    // ## Form Validation
    let validator = $("#modal_fabric_roll form").validate({
        rules: getValidationRules(),
        messages: getValidationMessages(),
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

    setTimeout(reload_dtable, 500);
</script>

<script>
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