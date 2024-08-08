<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Supplier;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AnalysisController extends Controller
{
    public function index()
    {
        $labels = [];
        $datas = [];
        $invoices = Invoice::Join('suppliers', 'suppliers.id', 'invoices.supplier_id')
        ->select(DB::raw('count(*) as total, suppliers.supplier as supplier'))
        ->groupBy('supplier_id')
        ->get();
        foreach ($invoices as $invoice) {
            $labels[] = $invoice->supplier;
            $datas[] = $invoice->total;
        }
        
        $data = [
            'title' => 'Invoice Chart',
            'page_title' => 'Invoice Chart',
            'invoices' => $invoices,
            'labels' => $labels,
            'datas' => $datas,
        ];

        return view('pages.analysis.invoice-chart', $data);
    }
}
