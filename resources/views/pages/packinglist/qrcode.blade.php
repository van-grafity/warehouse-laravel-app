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
            margin: 0px;
        }

        .page-break {
            page-break-after: auto;
        }
        
        @page { 
            margin: 0px; 
        }
	</style>
</head>
    @foreach ($fabricrolls as $fabricroll)
        <div class="page-break">
            <table style=" display: inline-table; border: 1px solid black; margin-left: 4px; margin-right: 4px; margin-top: 5px;" cellpadding="8" cellspacing="0" width="105">
                <tbody>
                    <tr style="">
                        <td width="">
                            <img src="data:image/png;base64, {!! base64_encode(QrCode::size(55)->generate($fabricroll->serial_number))!!} ">
                        </td>
                        <td style="font-size: 25px; text-align: center;">
                            {{ $fabricroll->roll_number }}
                        </td>
                        <tr>
                        <td  colspan="2" style="font-size: 9px; text-align: center; border: 1px solid black; ">
                            {{ $fabricroll->serial_number }}
                        </td>
                        </tr>
                        <tr>
                        <td  colspan="2" style="font-size: 10px; border: 1px solid black; ">
                            PO : {{ $fabricroll->packinglist->po_number }} 
                            </br>
                            GL : {{ $fabricroll->packinglist->gl_number }}
                        </td>
                        </tr>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach
</html>