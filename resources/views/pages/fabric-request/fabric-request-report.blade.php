@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #ff8000;
        border: 1px solid #ff8000;
        color: #fff;
        padding: 0 10px;
        height: 1.75rem;
        margin-top: .30rem;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: rgba(255,255,255,.7);
        float: right;
        margin-left: 5px;
        margin-right: -2px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff;
    }

    .select2-container--default .select2-selection--multiple {
        border-radius: 0;
        border-color: #006fe6;
        min-height: 38px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #006fe6;
        box-shadow: none;
    }
</style>


</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="gl_filter">Gl Number</label>
                        <select name="gl_filter" id="gl_filter" class="form-control select2">
                            <option value="" selected> Select GL Number </option>    
                            @foreach ($gl_numbers as $gl_numbers)
                                <option value="{{$gl_numbers->fbr_gl_number}}" >{{$gl_numbers->fbr_gl_number}}</option>    
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="color_filter">Color</label>
                        <select name="color_filter" id="color_filter" class="form-control select2">
                            <option value="" selected> Select Color </option>    
                            @foreach ($colors as $colors)
                                <option value="{{$colors->fbr_color}}" >{{$colors->fbr_color}}</option>    
                            @endforeach
                        </select>
                    </div>
                     <div class="form-group">
                        <label for="date_filter" class="mb-0 align-self-center col-form-label" style="width:150px;">Date Filter</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control daterangepicker-select" id="date_filter" name="date_filter" autocomplete="off" placeholder="Fabric Request Date Filter">
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <a href="javascript:void(0)" class="btn btn-primary mb-2 mr-2" id="btn_print_report">Show Report</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

<script type="text/javascript">
    // ## Page Variable
    let start_date_filter = moment().format('YYYY-MM-DD');
    let end_date_filter = moment().format('YYYY-MM-DD');

    const default_daterangepicker = () => {
        start_date_filter = moment().format('YYYY-MM-DD');
        end_date_filter = moment().format('YYYY-MM-DD');
        $('#date_filter').data('daterangepicker').setStartDate(moment());
        $('#date_filter').data('daterangepicker').setEndDate(moment());
    }

    $('#date_filter').daterangepicker({
        maxDate: moment(),
        opens: 'left',
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel: 'Clear'
        },
        startDate: moment().startOf('month'),
        endDate: moment().add(1, 'month').endOf('month'),
        alwaysShowCalendars: true,
        showCustomRangeLabel: false,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 2 Weeks': [moment().subtract(13, 'days'), moment()],
        },
        maxSpan: {
            "days": 13
        },
        
    }, function(start, end, label) {
        start_date_filter = start.format('YYYY-MM-DD');
        end_date_filter = end.format('YYYY-MM-DD');
    });

    $('#date_filter').on('apply.daterangepicker', function(ev, picker) {
        start_date_filter = picker.startDate.format('YYYY-MM-DD');
        end_date_filter = picker.endDate.format('YYYY-MM-DD');
    });
    $('#date_filter').on('cancel.daterangepicker', function(ev, picker) {
        start_date_filter = '';
        end_date_filter = '';
        $(this).val('');
    });

    $(document).ready(function(){
        $('#gl_filter, #color_filter').select2();
    });
</script>

@endpush