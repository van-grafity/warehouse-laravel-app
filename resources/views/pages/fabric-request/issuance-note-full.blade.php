<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fabric Issuance Note Full</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">

    <style type="text/css">
        .table-print-header tbody td {
            font-size: 12px;
            font-weight: bold;
        }
        .table-print tbody td,
        .table-print thead th {
            border: 1px black solid;
            vertical-align: middle;
            text-align: center;
            padding: 2px 5px;
        }
        .table-print thead th {
            font-size: 11px;
        }

        .table-print tbody td {
            font-size: 10px;
        }
	</style>
</head>
<body>
    <div>
        <table class="" width="100%" style="margin-bottom:12px;">
            <tr>
                <td style="font-weight: bold; font-size: 24px; text-align:center">
                    Fabric Issuance Note Full
                </td>
            </tr>
        </table>
        <br>
        <table class="table-print-header">
            <tbody>
                <tr>
                    <td width="100"> Gl Number </td>
                    <td width="200">: {{ $fabric_request->apiFabricRequest->fbr_gl_number }} </td>
                    <td width="100"> Qty Issued</td>
                    <td width="200">: {{ $fabric_request->qty_issued }} Yds</td>
                </tr>
                <tr>
                    <td width="100"> Color </td>
                    <td width="200">: {{ $fabric_request->apiFabricRequest->fbr_color }} </td>
                    <td width="100"> Difference</td>
                    <td width="200">: {{ $fabric_request->qty_difference }} Yds</td>
                </tr>
                <tr>
                    <td width="100"> Style </td>
                    <td width="200">: {{ $fabric_request->apiFabricRequest->fbr_style }} </td>
                    <td width="100"> Cutting Remark </td>
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
                     <td width="100"> Warehouse Remark </td>
                    <td width="200">: {{ ($fabric_request->remark) ? $fabric_request->remark : '-' }} </td>
                </tr>
                <tr>
                    <td width="100"> Qty Required </td>
                    <td width="200">: {{ $fabric_request->apiFabricRequest->fbr_qty_required }} Yds</td>
                </tr>
            </tbody>
        </table>
        <br>
        <table width="100%" class="table-print table-bordered">
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
                    <td>{{ $fabric_roll['color'] }}</td>
                    <td>{{ $fabric_roll['batch'] }}</td>
                    <td>{{ $fabric_roll['roll_number'] }}</td>
                    <td>{{ $fabric_roll['width'] }}</td>
                    <td>{{ $fabric_roll['yds'] }}</td>
                    <td>{{ $fabric_roll['rack_number'] }}</td>
                    <td>{{ $fabric_roll['location'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </br>
</body>
</html>