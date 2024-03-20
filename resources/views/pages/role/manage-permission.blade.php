@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@push('css')
<style>
    .card-body.p-0 table>thead>tr>th:first-of-type,
    .card-body.p-0 table>thead>tr>th:last-of-type,
    .card-body.p-0 table>tbody>tr>td:first-of-type,
    .card-body.p-0 table>tbody>tr>td:last-of-type {
        padding: .75rem !important;
    }
</style>
@endpush('css')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Manage Permisssion for {{ $role->title }} </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ route('role.manage-permission-update', $role->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        @php $key_checkbox = 1; @endphp
                        @foreach ($permission_by_categories as $key_category => $category)
                        <div class="col-sm-6 col-md-4 col-xl-3">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ $category->category_name }} </h3>
                                </div>
                                <div class="card-body p-0">
                                    <table id="permission_table" class="table table-bordered table-hover">
                                        <thead>
                                            <tr class="text-center">
                                                <th width="50px;">No</th>
                                                <th>Permission</th>
                                                <th width="50px;">
                                                    <div class="form-group mb-0">
                                                        <div class="custom-control custom-checkbox">
                                                            <input id="permission_group_checkbox_{{ $category->category_id }}" class="custom-control-input checkbox-group-control" type="checkbox" {{ ($category->is_checked) ? 'checked' : '' }}>
                                                            <label for="permission_group_checkbox_{{ $category->category_id }}" class="custom-control-label"></label>
                                                        </div>
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($category->permissions as $key_permission => $permission)
                                            <tr class="text-center">
                                                <td>{{ $key_permission + 1 }}</td>
                                                <td class="text-left">{{ $permission->name }}</td>
                                                <td>
                                                    <div class="form-group mb-0">
                                                        <div class="custom-control custom-checkbox">
                                                            <input id="permission_checkbox_{{ $key_checkbox }}" 
                                                            name="selected_permission[]" 
                                                            class="custom-control-input checkbox-permission-control" 
                                                            type="checkbox" 
                                                            value="{{ $permission->id }}" 
                                                            @if ($permission->is_role_has_permission) 
                                                                checked 
                                                            @endif 
                                                            >
                                                            <label for="permission_checkbox_{{ $key_checkbox }}" class="custom-control-label"></label>
                                                            @php $key_checkbox++; @endphp
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="row mt-5">
                        <div class="col-sm-12">
                            <div class="text-right">
                                <a href="{{ url('role')}}" type="button" class="btn btn-secondary">Back</a>
                                <button type="submit" class="btn btn-success" id="btn_save_permission">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
@endsection

@section('js')
    <script type="text/javascript">
    $('.checkbox-group-control').on('click', function(e) {
        let is_checked = $(this).prop('checked');
        let table = $(this).parents('table');
        table.find('.checkbox-permission-control').prop('checked', is_checked);
    })
    </script>
@stop