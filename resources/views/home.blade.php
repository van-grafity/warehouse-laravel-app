@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')

    <!-- Content Header (Page header) -->
            <div class="row">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                </div><!-- /.col -->
            </div><!-- /.row -->
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
    
            <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Hai, {{ $user->name }}</div>
                    <div class="card-body">
                        <h2>Welcome to Warehouse App</h2>
                        <p>Name : {{ $user->name }}</p>
                        <p>Department : {{ $user->department->department}}</p>
                        <p>Role : {{ $user->role }} </p>
                    </div>
                </div>
            </div>
        </div>
            <!-- /.row -->
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

</div>

@stop