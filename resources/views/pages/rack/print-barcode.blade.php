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
    <table style=" display: inline-table; border: 1px solid black; padding-top:3px; padding-bottom:3px; padding-left:10px; padding-right:10px; margin-top: 10px;">
        <tbody>
            <tr>
                <td style="align: center;">
                    {!! DNS1D::getBarcodeHTML($rack->serial_number, 'C128', 2.5, 110) !!}
                </td>
                <td style="font-size: 100px; padding-left:20px;">
                    <b>{{ normalizeNumber($rack->basic_number,2) }}</b>
                </td>
            </tr>
            <tr>
                <td colspan="1" style="font-size: 15px; text-align: center;">{{ $rack->serial_number }}</td>
            </tr>
        </tbody>
    </table>
@endforeach
</html>