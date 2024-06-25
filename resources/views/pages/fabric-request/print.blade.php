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
                @foreach ($fabric_request_details as $key => $item)
                    <tr style="text-align: center;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->fbr_serial_number }}</td>
                        <td>{{ $item->fbr_gl_number }}</td>
                        <td>{{ $item->fbr_color }}</td>
                        <td>{{ $item->fbr_table_number }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->fbr_qty_required }}</td>
                        <td></td>
                        <td>{{ $item->fbr_requested_at }}</td>
                        <td>{{ $item->issued_at ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </br>
    <div>
        <table align="center" width="85%" class="table-request-report">
            <thead>
                <tr style="background-color: #d9d9d9;">
                    <th colspan="3" style="text-align: left; padding-left: 10px;">Summary : </th>
                    <tr>
                        <th width="">Total Form Requested : </th>
                        <th width="">Total Form Qty Requested : </th>
                        <th width="">Actual Roll Qty Issued : </th>
                    </tr>
                    <tr>
                        <th width="">Total Form Issued : </th>
                        <th width="">Total Form Qty Issued : </th>
                        <th width="">Actual Length Issued : </th>
                    </tr>
                    <tr>
                        <th width="">Total Form Pending : </th>
                        <th width="">Total Form Qty Pending : </th>
                        <th width="">Actual Length Pending : </th>
                    </tr>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</body>
</html>
