<table>
    <thead>
    <tr>
        <th rowspan="2">Supplier</th>
        <th rowspan="2">Invoice Number</th>
        <th rowspan="2">PO Number</th>
        <th rowspan="2">GL Number</th>
        <th rowspan="2">Style</th>
        <th rowspan="2">Fabric Content</th>
        <th rowspan="2">Color</th>
        <th rowspan="2">Batch Number</th>
        <th colspan="3">Packinglist (supplier)</th>
        <th colspan="3">Actual Received</th>
        <!-- <th colspan="3">Balance</th> -->
    </tr>
    <tr>
        <th>Rolls</th>
        <th>Yds</th>
        <th>Kgs</th>
        <th>Rolls</th>
        <th>Yds</th>
        <th>Kgs</th>
        <!-- <th>Rolls</th>
        <th>Yds</th>
        <th>Kgs</th> -->
    </tr>  
    
    </thead>
    <tbody>
      @foreach ($packinglists as $packinglist)
        <tr>
            <td>{{ $packinglist->invoice->supplier->supplier }}</td>
            <td>{{ $packinglist->invoice->invoice_number }}</td>
            <td>{{ $packinglist->po_number }}</td>
            <td>{{ $packinglist->gl_number}}</td>
            <td>{{ $packinglist->style }}</td>
            <td>{{ $packinglist->fabric_content }}</td>
            <td>{{ $packinglist->color->color }}</td>
            <td>{{ $packinglist->batch_number }}</td>
            <td>{{ $packinglist->fabric_rolls->count() }}</td>
            <td>{{ $packinglist->packinglist_qty }}</td>
            <td>{{ $packinglist->packinglist_qty }}</td>
            <td>{{ $packinglist->stock_in }}</td>
            <td>{{ $packinglist->stock_in }}</td>
            <td>{{ $packinglist->stock_in }}</td>
        </tr>
    @endforeach
    </tbody>
</table>