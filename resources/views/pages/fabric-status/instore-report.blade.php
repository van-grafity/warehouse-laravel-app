<table>
    <thead>
    <tr>
        <th>Supplier</th>
        <th>Invoice Number</th>
        <th>PO Number</th>
        <th>GL Number</th>
        <th>Style</th>
        <th>Fabric Content</th>
        <th>Color</th>
        <th>Roll</th>
        <th>Yds</th>
        <th>Kgs</th>
        <th>Batch Number</th>
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
            <td>{{ $packinglist->fabric_rolls->count() }}</td>
            <td>{{ $packinglist->fabric_rolls->count() }}</td>
            <td>{{ $packinglist->fabric_rolls->count() }}</td>
            <td>{{ $packinglist->batch_number }}</td>
        </tr>
    @endforeach
    </tbody>
</table>