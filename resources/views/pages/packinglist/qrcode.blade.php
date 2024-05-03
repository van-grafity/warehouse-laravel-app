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
        <table style=" display: inline-table; border: 1px solid black; margin-left: 5px; margin-top: 10px;" cellpadding="8" cellspacing="0" width="105">
            <tbody>
                <tr style="">
                    <td width="50">
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::size(60)->generate($fabricroll->serial_number))!!} ">
                    </td>
                    <td style="font-size: 35px; padding-left:5px;">
                        {{ $fabricroll->roll_number }}
                    </td>
                    <tr>
                    <td  colspan="2" style="font-size: 9px; text-align: center; border: 1px solid black; ">
                        {{ $fabricroll->serial_number }}
                    </td>
                    </tr>
                    <tr>
                    <td  colspan="2" style="font-size: 10px; border: 1px solid black; ">
                        PO Number : {{ $fabricroll->packinglist->po_number }}
                    </td>
                    </tr>
                     <tr>
                    <td  colspan="2" style="font-size: 10px; border: 1px solid black; ">
                        GL Number : {{ $fabricroll->packinglist->gl_number }}
                    </td>
                    </tr>
                    <tr>
                    <td  colspan="2" style="font-size: 10px; border: 1px solid black; ">
                        Batch : {{ $fabricroll->packinglist->batch_number }}
                    </td>
                    </tr>
                    <tr>
                    <td  colspan="2" style="font-size: 10px; border: 1px solid black; ">
                        Color : {{ $fabricroll->packinglist->color->color }}
                    </td>
                    </tr>
                </tr>
            </tbody>
        </table>
    @endforeach
</html>