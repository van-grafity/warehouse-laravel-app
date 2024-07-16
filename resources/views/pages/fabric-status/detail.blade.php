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
                    @can('print')
                        <a href="{{ route('fabric-status.export', $packinglist->id) }}" class="btn btn-primary disabled">Report</a>
                    @endcan
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-12 d-flex">
                        <div class="action-wrapper mr-auto">
                            @can('remove')
                                <button class="btn btn-sm btn-danger" onclick="remove_roll()" data-toggle='tooltip' data-placement='top' title='remove roll'><i class="fas fa-times"></i></button>
                            @endcan
                            @can('change')
                                <button class="btn btn-info btn-sm" onclick="show_modal_change('modal_change_rack')">Change Rack</button>
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
                            <th width="100" class="text-center">Roll Number</th>
                            <th width="" class="text-center">Serial Number</th>
                            <th width="">KGs</th>
                            <th width="">LBs</th>
                            <th width="">YDs</th>
                            <th width="">Width</th>
                            <th width="">Rack Number</th>
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
<!-- /.row -->

<!-- Modal Change Rack-->
<div class="modal fade" id="modal_change_rack" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="post" onsubmit="stopFormSubmission(event)">

                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Select Rack</h5>
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
                    <div class="form-group">
                        <label for="rack" class="col-form-label">Rack</label>
                        <select name="rack" id="rack" class="form-control select2" required>
                            <option value=""> Select Rack </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-submit" onclick="submitForm('modal_change_rack')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Change Rack -->

<!-- Back Button -->
<div class="row text-right mb-5">
    <div class="col-12">
        @php $back_url = (url()->previous() == url()->current()) ? url('fabric-status') : url()->previous() @endphp
        <a href="<?= $back_url ?>" class="btn btn-secondary"><i class="fas fa-arrow-alt-circle-left mr-1"></i>Back</a>
    </div>
</div>

@endsection


@section('js')

<script type="text/javascript">

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    const packinglist_id = '{{ $packinglist->id }}';
    
    // ## URL List
    const dtable_list_url = "{{ route('fabric-status.dtable-roll-list') }}";
    const packinglist_information_url = "{{ route('packinglist.information-card', ':id') }}";
    const store_url = "{{ route('fabric-status.store') }}";
    const fetch_select_rack_url = "{{ route('fetch-select.rack') }}";
    const remove_url = "{{ route('fabric-status.remove-roll') }}";

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
            item_name
        }
    }

    const show_modal_change = (modal_element_id) => {
        let modal_data = {
            modal_id : modal_element_id,
            title : "Change Rack",
            btn_submit : "Save",
            form_action_url : store_url,
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
            } else {
                swal_failed({ title: response.message })
            }

            submit_btn.removeAttribute('disabled');

        } catch (error) {
            console.error("Error:", error);
        }

        $(`#${modal_id}`).modal('hide');
    }

    const remove_roll = async () => {
        let selected_roll = get_selected_item();

        swal_data = {
            title: `Want to remove ${selected_roll.item_id.length} selected roll?`,
            text: "selected roll will be remove from stock in",
            icon: "warning",
            confirmButton: "Remove Roll",
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-secondary"
        };
        let confirm_delete = await swal_confirm(swal_data);
        if(!confirm_delete) { return false; };

        params_data = { selected_roll_id : selected_roll.item_id };
        fetch_data = {
            url: remove_url,
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

</script>

<script type="text/javascript">
    let fabric_roll_table = $('#fabric_roll_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: dtable_list_url,
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
            { data: 'checkbox', name: 'checkbox',orderable: false, searchable: false },
            { data: 'roll_number', name: 'fabric_rolls.roll_number'},
            { data: 'serial_number', name: 'fabric_rolls.serial_number'},
            { data: 'kgs', name: 'fabric_rolls.kgs'},
            { data: 'lbs', name: 'fabric_rolls.lbs'},
            { data: 'yds', name: 'fabric_rolls.yds'},
            { data: 'width', name: 'fabric_rolls.width'},
            { data: 'rack_number', name: 'racks.serial_number'},
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

    $('#rack.select2').select2({
        dropdownParent: $('#modal_change_rack'),
        ajax: {
            url: fetch_select_rack_url,
            dataType: 'json',
            delay: 500,
            data: function (params) {
                var query = {
                    search: params.term,
                }
                return query;
            },
            processResults: function (fetch_result) {
                return {
                    results: fetch_result.data.items,
                };
            },
        }
    });
    $('#rack.select2').on('select2:open', function (e) {
        document.querySelector('.select2-search__field').focus();
    }).on('change', function() {
        // ## penyesuaian perlakuan untuk jquery validation di select2
        if ($(this).valid()) {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
            $(this).parent().find('.select2-container').removeClass('select2-container--error');
        }
    });

    // ## Form Validation
    let validator = $("#modal_change_rack form").validate({
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
