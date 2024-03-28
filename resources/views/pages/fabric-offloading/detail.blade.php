@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')

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
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-12 d-flex">
                        <div class="action-wrapper mr-auto">
                            <button class="btn btn-sm btn-info" disabled onclick="show_modal_offloading('modal_fabric_offloading')" >Offloading <i class="fas fa-sign-in-alt ml-1"></i> </button>
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
                            <th width="100" class="text-center">Roll Number</th>
                            <th width="" class="text-center">Serial Number</th>
                            <th width="">KGs</th>
                            <th width="">LBs</th>
                            <th width="">YDs</th>
                            <th width="">Loaded at</th>
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



<!-- Modal Fabric Offloading from container -->
<div class="modal fade" id="modal_fabric_offloading" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Fabric Offloading</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="selected_roll_id" name="selected_roll_id">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <h6 class="title text-bold">Selected Roll: </h6>
                            <div id="display_roll_number"></div>
                            <p id="total_selected_roll" class="title mt-3 mb-0 text-bold"> </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-submit" onclick="submitForm('modal_fabric_offloading')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Fabric Offloading from container -->


@endsection

@section('js')

<script type="text/javascript">

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const packinglist_id = '{{ $packinglist->id }}';
    
    // ## URL List
    const dtable_roll_list_url = "{{ route('fabric-offloading.dtable-roll-list') }}";
    const store_url = "{{ route('fabric-offloading.store') }}";
    const packinglist_information_url = "";


    const reload_dtable = () => {
        $('#reload_table_btn').trigger('click');

        let is_card_collapsed = $('#packinglist_information_card').hasClass("collapsed-card");
        
        // load_component({
        //     url : packinglist_information_url,
        //     container_element_id : 'packinglist_information_container',
        //     data : {
        //         collapsed_card_class : is_card_collapsed ? 'collapsed-card' : '',
        //     }
        // })
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
            if(item.disabled) { continue; } // ## Abaikan yang disabled
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
        let selected_element = $('.checkbox-roll-control:checked:not([disabled]').toArray();
        let item_id = [];
        let item_name = [];

        selected_element.forEach(element => {
            item_id.push($(element).val());
            item_name.push($(element).data('roll-number'));
        });

        return {
            item_id,
            item_name
        }
    }

    const show_modal_offloading = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Fabric Offloading",
            btn_submit : "Save",
        }
        clear_form(modal_data);

        let selected_roll = get_selected_item();
        if(selected_roll.item_id.length <= 0) {
            swal_warning({title: "Please select at least one roll"});
            return false;
        }

        let selected_roll_number = selected_roll.item_name.sort(function(a, b){return a-b});
        let roll_number_element = '';
        selected_roll_number.forEach(roll_name => {
            roll_number_element += `<span class="badge bg-maroon mr-1">Roll ${roll_name}</span>`;
        });
        $('#display_roll_number').html(roll_number_element)
        $('#total_selected_roll').text(`Total Roll: ${selected_roll.item_id.length}`)
        $('#selected_roll_id').val(selected_roll.item_id);

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
                disabled_action_wrapper(true); // ## disabled button inside action_wrapper
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

</script>

<script type="text/javascript">
    let fabric_roll_table = $('#fabric_roll_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_roll_list_url,
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
            { data: 'checkbox', orderable: false, searchable: false },
            { data: 'roll_number', name: 'roll_number'},
            { data: 'serial_number', name: 'serial_number'},
            { data: 'kgs', name: 'kgs'},
            { data: 'lbs', name: 'lbs'},
            { data: 'yds', name: 'yds'},
            { data: 'offloaded_at', name: 'offloaded_at'},
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

    reload_dtable();
</script>

<script>
    // ## Checkbox Feature
    $('.checkbox-all-control').on('click', function(e) {
        let is_checked = $(this).prop('checked');
        let table = $(this).parents('table');
        table.find('.checkbox-roll-control:not([disabled])').prop('checked',is_checked);
    })

    $('#roll_checkbox_all').on('change', function(e) {
        checkbox_clicked();
    })
    checkbox_clicked();

</script>

@stop
