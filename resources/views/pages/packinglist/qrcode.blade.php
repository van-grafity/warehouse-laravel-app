<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fabric Roll QR Code</title>

    <style type="text/css">
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
        }
	</style>
</head>
    @foreach ($fabricrolls as $fabricroll)
        <table style=" display: inline-table; border: 1px solid black; padding-top:3px; padding-bottom:3px; padding-left:3px; padding-right:3px; margin-top: 10px;" width="80">
            <tbody>
                <tr style="">
                    <td width="50">
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::size(60)->generate($fabricroll->serial_number))!!} ">
                    </td>
                    <td style="font-size: 20px; ">
                        {{ $fabricroll->roll_number }}
                    </td>
                    <tr>
                    <td colspan="2" style="font-size: 10px; text-align: center; ">
                        {{ $fabricroll->serial_number }}
                    </td>
                    </tr>
                </tr>
            </tbody>
        </table>
    @endforeach
</html>