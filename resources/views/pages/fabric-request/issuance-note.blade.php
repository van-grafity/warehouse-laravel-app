<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fabric Issuance Note</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <style type="text/css">
        .table-request-report thead th {
            border: 1px black solid;
            text-align: center;
            vertical-align: middle;
            font-size: 9px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
            padding-left: 5 !important;
            padding-right: 5 !important;
        }

        .table-request-report tbody td {
            border: 1px black solid;
            vertical-align: middle;
            font-size: 9px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
            padding-left: 3 !important;
            padding-right: 3 !important;
        }
        .table-detail thead th {
            font-size: 10px;
        }

        @page { 
            margin: 10px; 
        }
	</style>
</head>
<body>
    <div>
        <div class="row">
        </div>
        <table class="table-detail">
            <thead>
                <tr>
                    <th width="60"> Serial Number </th>
                    <th>: {{ $fabric_request->apiFabricRequest->fbr_serial_number }} </th>
                </tr>
                <tr>
                    <th width="60"> Gl Number </th>
                    <th>: {{ $fabric_request->apiFabricRequest->fbr_gl_number }} </th>
                </tr>
                <tr>
                    <th width="60" class="no-border"> Color </th>
                    <th>: {{ $fabric_request->apiFabricRequest->fbr_color }} </th>
                </tr>
                <tr>
                    <th width="60"> Fabric Detail </th>
                    <th>: {{ $fabric_request->apiFabricRequest->fbr_fabric_type }} </th>
                </tr>
            </thead>
        </table>
        <table style="margin-top: 10px;" width="100%" class="table-request-report table-bordered">
            <thead>
                <tr style="background-color: #d9d9d9;">
                    <th width="5%">No.</th>
                    <th width="30%">Location</th>
                    <th width="35%">Color</th>
                    <th width="15%">Batch No.</th>
                    <th width="15%">Roll No.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fabric_roll_issuance as $fabric_roll)
                <tr style="text-align: center;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{$fabric_roll['location']}} ({{$fabric_roll['rack_number']}})</td>
                    <td>{{$fabric_roll['color']}}</td>
                    <td>{{$fabric_roll['batch']}}</td>
                    <td>{{$fabric_roll['roll_number']}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </br>
</body>
</html>