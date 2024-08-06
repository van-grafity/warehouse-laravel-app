<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packing List Report</title>
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
            font-size: 11px;
        }

	</style>
</head>
<body>
    <div>
        <table class="" width="100%" style="margin-bottom:12px;">
            <tr>
                <td style="font-weight: bold; font-size: 24px; text-align:center">
                    Packing List Report
                </td>
            </tr>
        </table>
        <br>
        <table width="100%" class="table-print-header">
            <tbody>
                <tr>
                    <td width="5%"> FABRIC </td>
                    <td width="55%">: {{ $packinglist->fabric_content }}  </td>
                    <td width="5%"> PO NO</td>
                    <td width="35%">: {{ $packinglist->po_number }}</td>
                </tr>
                <tr>
                    <td width="5%"> COLOR</td>
                    <td width="55%">: {{ $packinglist->color->color }} </td>
                    <td width="5%"> BATCH </td>
                    <td width="35%">: {{ $packinglist->batch_number }} </td>
                </tr>
                <tr>
                    <td width="5%"> BUYER </td>
                    <td width="55%">: {{ $packinglist->buyer }} </td>
                    <td width="5%"> STYLE </td>
                    <td width="35%">: {{ $packinglist->style }} </td>
                </tr>
                <tr>
                    <td width="5%"> GLA </td>
                    <td width="55%">: {{ $packinglist->gl_number }} </td>
                    <td width="5%"> INVOICE </td>
                    <td width="35%">: {{ $packinglist->invoice->invoice_number }} </td>
                </tr>
                <tr>
                    <td width="5%"> REMARK </td>
                    <td width="55%">:  {{ $packinglist->remark ? $packinglist->remark : '-'}} </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table width="100%" class="table-print table-bordered">
            <thead>
                <tr style="background-color: #d9d9d9;">
                    <th width="3%">No.</th>
                    <th width="">Roll No.</th>
                    <th width="10%">KGs</th>
                    <th width="10%">LBs</th>
                    <th width="10%">Yds</th>
                    <th width="10%">Width</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fabric_rolls as $fabric_roll)
                <tr style="text-align: center;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $fabric_roll['roll_number'] }}</td>
                    <td>{{ $fabric_roll['kgs'] }}</td>
                    <td>{{ $fabric_roll['lbs'] ? $fabric_roll['lbs'] : '-' }}</td>
                    <td>{{ $fabric_roll['yds'] }}</td>
                    <td>{{ $fabric_roll['width'] ? $fabric_roll['width'] : '-'}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </br>
        <table align="center" width="100%" class="table-print">
            <thead>
                <tr>
                    <th>Total : </th>
                    <th> {{ $total_roll }} Roll</th>
                    <th> {{ $total_kgs }} KGs</th>
                    <th> {{ $total_lbs }} LBs</th>
                    <th> {{ $total_yds }} YDs</th>
                </tr>
            </thead>
        </table>
</body>
</html>