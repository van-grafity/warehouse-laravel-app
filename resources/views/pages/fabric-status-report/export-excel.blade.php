<table>
    <thead>
        <tr>
            <th rowspan="2" valign="center" style="text-align:center">Incoming Date</th>
            <th rowspan="2" valign="center" style="text-align:center">Supplier</th>
            <th rowspan="2" valign="center" style="text-align:center">Invoice Number</th>
            <th rowspan="2" valign="center" style="text-align:center">Buyer</th>
            <th rowspan="2" valign="center" style="text-align:center">Container Number</th>
            <th rowspan="2" valign="center" style="text-align:center">Batch Number</th>
            <th rowspan="2" valign="center" style="text-align:center">GL Number</th>
            <th rowspan="2" valign="center" style="text-align:center">PO Number</th>
            <th rowspan="2" valign="center" style="text-align:center">Style</th>
            <th rowspan="2" valign="center" style="text-align:center">Fabric Content</th>
            <th rowspan="2" valign="center" style="text-align:center">Color</th>
            <th rowspan="1" colspan="3" valign="center" style="text-align:center">In Store</th>
            <th rowspan="1" colspan="3" valign="center" style="text-align:center">Issue Out</th>
            <th rowspan="1" colspan="3" valign="center" style="text-align:center">Balance</th>
        </tr>
        <tr>
            <th rowspan="1" valign="center" style="text-align:center" width="100px">Roll</th>
            <th rowspan="1" valign="center" style="text-align:center" width="100px">Length (Yards)</th>
            <th rowspan="1" valign="center" style="text-align:center" width="100px">Weight (Kgs)</th>
            <th rowspan="1" valign="center" style="text-align:center" width="100px">Roll</th>
            <th rowspan="1" valign="center" style="text-align:center" width="100px">Length (Yards)</th>
            <th rowspan="1" valign="center" style="text-align:center" width="100px">Weight (Kgs)</th>
            <th rowspan="1" valign="center" style="text-align:center" width="100px">Roll</th>
            <th rowspan="1" valign="center" style="text-align:center" width="100px">Length (Yards)</th>
            <th rowspan="1" valign="center" style="text-align:center" width="100px">Weight (Kgs)</th>
        </tr>
    </thead>
    <tbody>
    @foreach($body as $value)
        <tr>
            <td style="text-align:center;" >{{ $value->incoming_date }}</td>
            <td style="text-align:center" >{{ $value->supplier }}</td>
            <td style="text-align:center" >{{ $value->invoice_number }}</td>
            <td style="text-align:center" >{{ $value->buyer }}</td>
            <td style="text-align:center" >{{ $value->container_number }}</td>
            <td style="text-align:center" >{{ $value->batch_number }}</td>
            <td style="text-align:center" >{{ $value->gl_number }}</td>
            <td style="text-align:center" >{{ $value->po_number }}</td>
            <td style="text-align:center" >{{ $value->style }}</td>
            <td style="text-align:center" >{{ $value->fabric_content }}</td>
            <td style="text-align:center" >{{ $value->color }}</td>
            <td style="text-align:center" >{{ $value->stock_in_data->total_roll }}</td>
            <td style="text-align:center" >{{ $value->stock_in_data->total_length_yds }}</td>
            <td style="text-align:center" >{{ $value->stock_in_data->total_weight_kgs }}</td>
            <td style="text-align:center" >{{ $value->stock_out_data->total_roll }}</td>
            <td style="text-align:center" >{{ $value->stock_balance_data->total_roll }}</td>
            <td style="text-align:center" >{{ $value->stock_out_data->total_length_yds }}</td>
            <td style="text-align:center" >{{ $value->stock_balance_data->total_length_yds }}</td>
            <td style="text-align:center" >{{ $value->stock_out_data->total_weight_kgs }}</td>
            <td style="text-align:center" >{{ $value->stock_balance_data->total_weight_kgs }}</td>
        </tr>
    @endforeach
</table>

                