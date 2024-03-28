@extends('adminlte::page')

@section('css')
    @vite(['resources/js/main.js'])
@stop

@section('content_header')
    <div class="row mb-2 d-none">
        <div class="col-sm-6">
            <h1 class="m-0">@yield('page_title')</h1>
        </div>
    </div>
@stop

@push('js')
    @vite(['resources/js/utils.js'])
    
    <script>
        $(document).ready(function() {
            // ## Show Flash Message
            let session = <?= json_encode(session()->all()) ?>;
            show_flash_message(session);
        });
    </script>
@endpush
