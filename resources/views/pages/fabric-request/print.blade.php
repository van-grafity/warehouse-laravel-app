<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fabric Request Report</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">


    <style type="text/css">
        .table-request-report tbody td,
        .table-request-report thead th {
            border: 1px black solid;
            vertical-align: middle;
            text-align: center;
            padding: 5px 2px;
        }
        .table-request-report thead th {
            font-size: 11px;
        }

        .table-request-report tbody td {
            font-size: 10px;
        }
	</style>
</head>
<body>
    <div>
        <table width="100%" style="margin-bottom:12px;">
            <tr>
                <td style="font-weight: bold; font-size: 24px; text-align:center">
                    Fabric Request Report
                </td>
            </tr>
        </table>
        <br>
        
        <table width="100%" class="table-request-report">
            <thead>
                <tr style="background-color: #d9d9d9;">
                    <th width="20px">No.</th>
                    <th width="">Fabric Request Serial Number</th>
                    <th width="70px">Gl Number</th>
                    <th width="">Color</th>
                    <th width="40px">Table No</th>
                    <th width="60px">Status</th>
                    <th width="75px">Qty Required</th>
                    <th width="75px">Qty Issued</th>
                    <th width="70px">Requested Time</th>
                    <th width="70px">Issued Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fabric_requests as $key => $fabric_request)
                    <tr style="text-align: center;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $fabric_request->fbr_serial_number }}</td>
                        <td>{{ $fabric_request->fbr_gl_number }}</td>
                        <td>{{ $fabric_request->fbr_color }}</td>
                        <td>{{ $fabric_request->fbr_table_number }}</td>
                        <td>{{ $fabric_request->status }}</td>
                        <td>{{ $fabric_request->fbr_qty_required }}</td>
                        <td>{{ $fabric_request->qty_issued ?? '0' }}</td>
                        <td>{{ $fabric_request->fbr_requested_at }}</td>
                        <td>{{ $fabric_request->issued_at ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </br>
    <div>
        <table align="center" width="85%" class="table-request-report table-bordered">
            <thead>
                <tr style="background-color: #d9d9d9;">
                    <th colspan="3" style="text-align: left; padding-left: 10px;">Summary : </th>
                </tr>
                <tr>
                    <th>Total Form Requested: {{ $total_form_requested }}</th>
                    <th>Total Form Qty Requested: {{ $total_form_qty_requested }} Yds</th>
                    <th>Actual Roll Qty Issued: {{ $actual_roll_qty_issued }} Roll</th>
                </tr>
                <tr>
                    <th>Total Form Issued: {{ $total_form_issued }}</th>
                    <th>Total Form Qty Issued: {{ $total_form_qty_issued }} Yds</th>
                    <th>Actual Length Issued: {{ $actual_length_issued }} Yds</th>
                </tr>
                <tr>
                    <th>Total Form Pending: {{ $total_form_pending }}</th>
                    <th>Total Form Qty Pending: {{ $total_form_qty_pending }} Yds</th>
                    <th>Actual Length Pending: {{ $actual_length_pending }} Yds</th>
                </tr>
            </thead>
        </table>
    </div>
</body>
</html>
