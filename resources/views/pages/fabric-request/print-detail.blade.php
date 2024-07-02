<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fabric Request Detail Report</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <style type="text/css">
        .table-request-report thead th {
            border: 1px black solid;
            text-align: center;
            vertical-align: middle;
            font-size: 12px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
        }

        .table-request-report tbody td {
            border: 1px black solid;
            vertical-align: middle;
            font-size: 12px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
            padding-left: 5 !important;
            padding-right: 5 !important;
        }
	</style>
</head>
<body>
    <div>
        <div class="row">
            <div class="col-lg-12 text-center mb-3">
                <h4>Fabric Request Detail Report</h4>
            </div>
        </div>
        <table class="">
            <thead>
                <tr>
                    <td width="100"> Gl Number </td>
                    <td width="200">: {{ $fabric_request->apiFabricRequest->fbr_gl_number }} </td>
                    <td width="100"> Qty Issued</td>
                    <td width="200">: {{ $fabric_request->qty_issued }} Yds</td>
                </tr>
                <tr>
                    <td width="100" class="no-border"> Color </td>
                    <td width="200">: {{ $fabric_request->apiFabricRequest->fbr_color }} </td>
                    <td width="100"> Difference</td>
                    <td width="200">: {{ $fabric_request->qty_difference }} Yds</td>
                </tr>
                <tr>
                    <td width="100"> Style </td>
                    <td width="200">: {{ $fabric_request->apiFabricRequest->fbr_style }} </td>
                    <td width="100"> Remark </td>
                    <td width="200">: {{ ($fabric_request->apiFabricRequest->fbr_remark) ? $fabric_request->apiFabricRequest->fbr_remark : '-' }} </td>
                </tr>
                <tr>
                    <td width="100"> PO Number </td>
                    <td width="200">: {{ $fabric_request->apiFabricRequest->fbr_fabric_po }} </td>
                    <td width="100"> Fabric Detail </td>
                    <td width="400">: {{ $fabric_request->apiFabricRequest->fbr_fabric_type }} </td>
                </tr>
                <tr>
                    <td width="100"> Table Number </td>
                    <td width="200">: {{ $fabric_request->apiFabricRequest->fbr_table_number }} </td>
                </tr>
                <tr>
                    <td width="100"> Qty Required </td>
                    <td width="200">: {{ $fabric_request->apiFabricRequest->fbr_qty_required }} Yds</td>
                </tr>
                
                </tr>
            </thead>
        </table>
        <br>
        <table width="100%" class="table-request-report table-bordered">
            <thead>
                <tr style="background-color: #d9d9d9;">
                    <th width="3%">No.</th>
                    <th width="30%">Color</th>
                    <th width="10%">Batch No.</th>
                    <th width="">Roll No.</th>
                    <th width="">Width</th>
                    <th width="10%">Yds</th>
                    <th width="">Rack No.</th>
                    <th width="">Location</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fabric_roll_issuance as $fabric_roll)
                <tr style="text-align: center;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{$fabric_roll['color']}}</td>
                    <td>{{$fabric_roll['batch']}}</td>
                    <td>{{$fabric_roll['roll_number']}}</td>
                    <td>{{$fabric_roll['width']}}</td>
                    <td>{{$fabric_roll['yds']}}</td>
                    <td>{{$fabric_roll['rack_number']}}</td>
                    <td>{{$fabric_roll['location']}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </br>
</body>
</html>