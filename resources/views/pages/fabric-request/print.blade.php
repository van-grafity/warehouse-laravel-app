<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fabric Request Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <style type="text/css">
        .table-request-report thead th {
            border: 1px black solid;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            padding-top: 2 !important;
            padding-bottom: 2 !important;
        }

        .table-request-report tbody td {
            border: 1px black solid;
            vertical-align: middle;
            font-size: 10px;
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
                <h4>Fabric Request Report</h4>
            </div>
        </div>
        <table width="100%" class="table-request-report">
            <thead>
                <tr style="background-color: #d9d9d9;">
                    <th width="3%">No.</th>
                    <th width="30%">Fabric Request Serial Number</th>
                    <th width="10%">Gl Number</th>
                    <th width="">Color</th>
                    <th width="">Table No</th>
                    <th width="10%">Status</th>
                    <th width="">Qty Required</th>
                    <th width="">Qty Issued</th>
                    <th width="10%">Requested Time</th>
                    <th width="10%">Issued Time</th>
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
                    <th width="">Total Form Requested : {{ $total_form_requested}} </th>
                    <th width="">Total Form Qty Requested : {{ $total_form_qty_requested }} Yds </th>
                    <th width="">Actual Roll Qty Issued : {{ $actual_roll_qty_issued }} Roll </th>
                </tr>
                <tr>
                    <th width="">Total Form Issued : {{ $total_form_issued }} </th>
                    <th width="">Total Form Qty Issued : {{ $total_form_qty_issued }} Yds </th>
                    <th width="">Actual Length Issued : {{ $actual_length_issued }} Yds </th>
                </tr>
                <tr>
                    <th width="">Total Form Pending : {{ $total_form_pending }} </th>
                    <th width="">Total Form Qty Pending : {{ $total_form_qty_pending }} Yds</th>
                    <th width="">Actual Length Pending : {{ $actual_length_pending }} Yds </th>
                </tr>
            </thead>
        </table>
    </div>
</body>
</html>
