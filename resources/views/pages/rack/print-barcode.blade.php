<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rack Barcode</title>

    <style type="text/css">
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
	</style>
</head>
@foreach ($racks as $rack)
    <table style=" display: inline-table; border: 1px solid black; padding-top:2px; padding-bottom:2px; padding-left:5px; padding-right:5px; margin-top: 15px;">
        <tbody>
            <tr>
                <td style="align: center;">
                    {!! DNS1D::getBarcodeHTML($rack->serial_number, 'C128', 2.5, 75) !!}
                </td>
                <td style="font-size: 65px; padding-left:15px;">
                    <b>{{ normalizeNumber($rack->basic_number,2) }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="1" style="font-size: 12px; text-align: center;">{{ $rack->serial_number }}</td>
            </tr>
        </tbody>
    </table>
@endforeach
</html>