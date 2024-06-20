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
                            <option value="" selected>-- Select GL Number --</option>    
                            @foreach ($gl_numbers as $gl_numbers)
                                <option value="{{$gl_numbers->fbr_gl_number}}" >{{$gl_numbers->fbr_gl_number}}</option>    
                            @endforeach
                        </select>
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
    $(document).ready(function(){
        $('#gl_filter').select2();
        
        $('#btn_print_report').click(function(){
            var gl_number = $('#gl_filter').val();
            if(gl_number){
                window.open(url + '?gl_id=' + gl_number, '_blank');
            } else {
                alert('Please enter gl number');
            }
        });
    });
</script>
</script>

@endpush