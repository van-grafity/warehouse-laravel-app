<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rack Order Record</title>

    <style type="text/css">
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
	</style>
</head>
@foreach ($racks as $rack)
    <table style=" display: inline-table; border: 1px solid black; margin-top: 5px; width: 1in !important;">
        <tbody>
            <tr style="">
                <td width="5"></td>
                <td style="align: center; margin-top: 5px;" width="60" >
                {!! DNS1D::getBarcodeHTML($rack->serial_number, 'C128', 0.9, 35) !!}</td>
                <td width="5"></td>
                <td style="font-size: 40px; text-align: center;">
                    <b>{{ normalizeNumber($rack->basic_number,2) }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-size: 15px; text-align: center;">{{ $rack->serial_number }}</td>
                <td colspan="2"></td>
                <td width="5"></td>
            </tr>
        </tbody>
    </table>
@endforeach
</html>