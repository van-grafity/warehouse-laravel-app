<?php

namespace App\Exports;

// use App\Invoice;
use App\Models\Invoice;
use App\Models\Packinglist;

use Carbon\Carbon;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class FabricStatusReportsExport implements FromView, Responsable
{
    use Exportable;
    /**
    * It's required to define the fileName within
    * the export class when making use of Responsable.
    */
    private $fileName = 'Fabric Status Report.xlsx';
    
    /**
    * Optional Writer Type
    */
    private $writerType = Excel::XLSX;
    
    /**
    * Optional headers
    */
    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    private $startDate;
    private $endDate;

    public function __construct()
    {
        $this->startDate = Carbon::now()->subMonth()->startOfMonth();
        $this->endDate = Carbon::now()->addMonth()->startOfMonth();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $header = [
            'Incoming Date',
            'Supplier',
            'Invoice Number',
            'Buyer',
            'Container Number',
            'Batch Number',
            'GL Number',
            'PO Number',
            'Style',
            'Fabric Content',
            'Color',
        ];

        $query = Packinglist::join('invoices','invoices.id','=','packinglists.invoice_id')
            ->join('suppliers','suppliers.id','=','invoices.supplier_id')
            ->join('colors','colors.id','=','packinglists.color_id')
            ->select([
                'invoices.incoming_date',
                'suppliers.supplier',
                'invoices.invoice_number',
                'packinglists.buyer',
                'invoices.container_number',
                'packinglists.id as packinglist_id',
                'packinglists.batch_number',
                'packinglists.gl_number',
                'packinglists.po_number',
                'packinglists.style',
                'packinglists.fabric_content',
                'colors.color',
            ]);

        if ($this->startDate) {
            $query->where('invoices.incoming_date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->where('invoices.incoming_date', '<=', $this->endDate);
        }

        $packinglists = $query->get();

        foreach ($packinglists as $key => $packinglist) {
            $packinglist_object = Packinglist::find($packinglist->packinglist_id);
            
            $initial_stock = (object) [
                'total_roll' => 0,
                'total_length_yds' => 0,
                'total_weight_kgs' => 0,
            ];

            $stock_in_data = $packinglist_object->getRollSummaryInPackinglist($packinglist_object->id, 'stock_in') ?? $initial_stock;
            $stock_out_data = $packinglist_object->getRollSummaryInPackinglist($packinglist_object->id, 'stock_out') ?? $initial_stock;
            $stock_balance_data = (object) [
                'total_roll' => $stock_in_data->total_roll - $stock_out_data->total_roll,
                'total_length_yds' => $stock_in_data->total_length_yds - $stock_out_data->total_length_yds,
                'total_weight_kgs' => $stock_in_data->total_weight_kgs - $stock_out_data->total_weight_kgs,
            ];
            
            $packinglist->stock_in_data = $stock_in_data;
            $packinglist->stock_out_data = $stock_out_data;
            $packinglist->stock_balance_data = $stock_balance_data;
            
        }
        // $data->prepend($header);
        $data = [
            'header' => $header,
            'body' => $packinglists,
        ];


        return view('pages.fabric-status-report.export-excel', $data);
    }

    public function startDateFilter($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function endDateFilter($endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

}
