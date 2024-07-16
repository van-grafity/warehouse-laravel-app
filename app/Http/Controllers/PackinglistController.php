<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Packinglist;
use App\Models\Supplier;
use App\Models\Invoice;
use App\Models\Color;
use App\Models\FabricRoll;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;

use App\Imports\PackinglistsImport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class PackinglistController extends Controller
{
    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('packinglist.manage');
        });
        Gate::define('print', function ($user) {
            return $user->hasPermissionTo('packinglist.print-qrcode');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::get();
        $packinglist = Packinglist::select('gl_number')->distinct()->get();

        $data = [
            'title' => 'Packing List',
            'page_title' => 'Packing List',
            'suppliers' => $suppliers,
            'packinglist' => $packinglist,
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.packinglist.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = Packinglist::query();

        return DataTables::of($query)
        ->addIndexColumn()
        ->escapeColumns([])
        ->editColumn('serial_number', function($row){
                $serial_number = "<a href='". route('packinglist.detail',$row->id)."' class='' data-toggle='tooltip' data-placement='top' title='Packing List Detail'>$row->serial_number</a>";
                return $serial_number;
            })
            
            ->addColumn('action', function($row){
                $action_button = "";
                if($row->getRollSummaryInPackinglist($row->id, 'stock_in')){
                    $action_button .= '
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_packinglist\', '.$row->id.')">Edit</a>
                    <div data-toggle="tooltip" data-placement="top" title="There are fabric rolls that already stock in" class="btn btn-danger btn-sm disabled" >Delete</div>
                    ';
                } else {
                    $action_button .= '
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_packinglist\', '.$row->id.')">Edit</a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="show_modal_delete('.$row->id.')" >Delete</a>
                    ';
                }
                return $action_button; 
            })
            
            ->addColumn('invoice', function($row){
                return $row->invoice->invoice_number;
            })
            ->addColumn('color', function($row){
                return $row->color->color;
            })
            ->addColumn('roll_qty', function($row){
                return $row->fabric_rolls->count();
            })

            ->filter(function ($query, $gl_filter){
                if (request('gl_filter')) {
                    $query->where('gl_number', request()->gl_filter);
                }
                if (request('color_filter')) {
                    $query->where('color_id', request()->color_filter);
                }
                if (request('invoice_filter')) {
                    $query->where('invoice_id', request()->invoice_filter);
                }
                
            }, true)
            ->toJson();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $color = Color::find($request->color);
        if(!$color){ 
            $data_return = [
                'status' => 'error',
                'message' => 'Color Not Found!',
            ];
            return response()->json($data_return, 200);
        }

        try {
            $packinglist_model = new Packinglist;
            $packinglist = Packinglist::firstOrCreate([
                'serial_number' => $packinglist_model->generate_serial_number($color->code),
                'invoice_id' => $request->invoice,
                'buyer' => $request->buyer,
                'gl_number' => $request->gl_number,
                'po_number' => $request->po_number,
                'color_id' => $request->color,
                'batch_number' => $request->batch_number,
                'style' => $request->style,
                'fabric_content' => $request->fabric_content,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new packing list (' . $packinglist->serial_number . ')',
                'data' => [
                    'packinglist' => $packinglist,
                ]
            ];
            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $packinglist = Packinglist::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get packing list (' . $packinglist->serial_number . ')',
                'data' => [
                    'packinglist' => $packinglist,
                ]
            ];
            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $packinglist = Packinglist::find($id);
            $packinglist->invoice_id = $request->invoice;
            $packinglist->buyer = $request->buyer;
            $packinglist->gl_number = $request->gl_number;
            $packinglist->po_number = $request->po_number;
            $packinglist->color_id = $request->color;
            $packinglist->batch_number = $request->batch_number;
            $packinglist->style = $request->style;
            $packinglist->fabric_content = $request->fabric_content;
            $packinglist->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated packing list ('. $packinglist->serial_number .')',
                'data' => $packinglist
            ];
            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $packinglist = Packinglist::find($id);
            $packinglist->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $packinglist,
                'message'=> 'Packing list '.$packinglist->serial_number.' successfully Deleted!',
            ];
            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }

    /**
     * Display a detail of this resource.
     */
    public function detail(int $id)
    {
        $packinglist = Packinglist::find($id);

        $data = [
            'title' => 'Packing List Detail',
            'page_title' => 'Packing List Detail',
            'packinglist' => $packinglist,
            'can_manage' => auth()->user()->can('manage'),
            'can_print' => auth()->user()->can('print'),
        ];
        return view('pages.packinglist.detail', $data);
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file_excel' => 'required|file|mimes:xlsx,xls',
            ]);

            $file = $request->file('file_excel');
            $excel_data = new PackinglistsImport();
            Excel::import($excel_data, $file);
            $packinglist_header = $excel_data->getHeader();
            $packinglist_data = $excel_data->getData();
            $is_valid_header = $this->isValidHeader($packinglist_header);
            if (!$is_valid_header){
                return redirect()->route('packinglist.index')->with('error', 'Incorrect header format!');
            }

            // ## set required Column. cannot be empty / null on excel
            $required_column = ['invoice','buyer','gl_number','po_number','color','batch','style','fabric_content','roll','kgs','yds','width'];
    
            // ## cleaning data
            $cleaned_data = removeEmptyData($packinglist_data, $required_column);
            $cleaned_data = removeWhitespace($cleaned_data);

            $is_invoice_available = $this->isInvoicesAvailable($cleaned_data);
            
            if(!$is_invoice_available) { 
                return redirect()->route('packinglist.index')->with('error', 'There are Invoice that has not registered in the system! Please create Invoice first' );
            }

            $message = '';
            DB::transaction(function () use ($cleaned_data, &$message) {

                $statusCreate = $this->createMasterDataIfNotExists($cleaned_data);
                if(!$statusCreate) { 
                    throw new \Exception("Failed to Create Color Master Data!");
                }
    
                $inserted_packinglist = $this->insertPackinglist($cleaned_data);
                $inserted_roll = $this->insertFabricRoll($cleaned_data);
                
                $message = 'Successfully inserted '. count($inserted_packinglist) . ' packing list and '. count($inserted_roll) . ' rolls';
            });
            return redirect()->route('packinglist.index')->with('success', $message);


        } catch (\Throwable $th) {
            return redirect()->route('packinglist.index')->with('error', $th->getMessage());
        }
    }

    private function isValidHeader($header_array)
    {
        // ## get first row in header_array and check valid name
        $header_from_excel = array_values($header_array);
        $header_list = ['invoice','buyer','gl_number','po_number','color','batch','style','fabric_content','roll','kgs','lbs','yds','width'];

        foreach ($header_list as $key => $header) {
            if ($header != $header_from_excel[$key]) return false;
        }
        return true;
    }

    private function isInvoicesAvailable(Array $data_array_from_excel) : bool
    {
        $array_invoice = array_unique(array_column($data_array_from_excel,'invoice'));
        foreach ($array_invoice as $key => $invoice) {
            $invoice = Invoice::where('invoice_number', $invoice)->first();
            if(!$invoice) { return false; }
        }
        return true;
    }

    private function createMasterDataIfNotExists(Array $data_array_from_excel) : bool
    {
        // ## set header (key_name) and model
        $master_data_list = [
            [
                'key_name' => 'color',
                'model' => new Color,
                'column_to_insert' => ['color']
            ],
        ];

        try {
            foreach ($master_data_list as $key => $master_data) {

                $filtered_column = filterArrayByKeys($data_array_from_excel, $master_data['column_to_insert']);
                $filtered_unique_data = filterUniqueValueByKey($filtered_column, $master_data['key_name']);
                foreach ($filtered_unique_data as $key_data => $value) {
                    $model_data[] = $master_data['model']->getOrCreateDataByName($value);
                }
            }
            return true;

        } catch (DatabaseException $e) {
            exit($e->getMessage());
        }
    }

    private function insertPackinglist(Array $data_array_from_excel)
    {
        $inserted_packinglist = [];
        $packinglist_model = new Packinglist;
        try {
            
            $column_to_insert = ['invoice','buyer','gl_number','po_number','batch','style','fabric_content','color'];
            $filtered_column = filterArrayByKeys($data_array_from_excel, $column_to_insert);
            $filtered_unique_data = filterUniqueValueByKey($filtered_column, 'batch');

            foreach ($filtered_unique_data as $key_data => $batch) {
                $inserted_packinglist[] = $packinglist_model->getOrCreateDataByName($batch);
            }
            return $inserted_packinglist;

        } catch (DatabaseException $e) {
            exit($e->getMessage());
        }
    }
    
    private function insertFabricRoll(Array $data_array_from_excel)
    {
        $inserted_roll = [];
        try {
            
            $column_to_insert = ['batch','color','roll','kgs','lbs','yds','width'];
            $roll_data_from_excel = filterArrayByKeys($data_array_from_excel, $column_to_insert);
            
            foreach ($roll_data_from_excel as $key_data => $roll) {
                $color = Color::where('color', $roll['color'])->first();
                $packinglist = Packinglist::where('batch_number', $roll['batch'])
                    ->where('color_id', $color->id)
                    ->first();
                
                if(!$packinglist){
                    throw new \Exception("Packinglist Not Found. There is strange data. Please contant the Administrator");
                }

                $is_roll_number_exist = FabricRoll::is_roll_number_exist($packinglist->id, $roll['roll']);
                
                if ($is_roll_number_exist) {
                    throw new \Exception("Roll {$roll['roll']} is already on packing list {$packinglist->serial_number} - ({$packinglist->color->color} | {$packinglist->batch_number})");
                }

                $roll_data_to_insert = [
                    'packinglist_id' => $packinglist->id,
                    'serial_number' => FabricRoll::generate_serial_number($color->code, $roll['batch'], $roll['roll']),
                    'roll_number' => $roll['roll'],
                    'kgs' => $roll['kgs'] ? $roll['kgs'] : null,
                    'lbs' => $roll['lbs'] ? round($roll['lbs'],2) : null,
                    'yds' => $roll['yds'] ? $roll['yds'] : null,
                    'width' => $roll['width'] ? $roll['width'] : null,
                ];

                $inserted_roll[] = FabricRoll::create($roll_data_to_insert);
            }
            
            return $inserted_roll;

        } catch (DatabaseException $e) {
            exit($e->getMessage());
        }
    }

    public function information_card(string $id)
    {
        $packinglist = Packinglist::find($id);
        $collapsed_card_class = request()->collapsed_card_class;
        
        $PackinglistModel = new Packinglist;
        $roll_summary_in_packinglist = $PackinglistModel->getRollSummaryInPackinglist($packinglist->id);
        $stock_in_summary = $PackinglistModel->getRollSummaryInPackinglist($packinglist->id, 'stock_in');

        if($roll_summary_in_packinglist) {
            $roll_data = (object)[];
            $roll_data->category = 'Roll';
            $roll_data->packinglist_qty = $roll_summary_in_packinglist->total_roll;
            $roll_data->stock_in = ($stock_in_summary ? $stock_in_summary->total_roll : '0' );
            $roll_data->balance = $roll_summary_in_packinglist->total_roll - ($stock_in_summary ? $stock_in_summary->total_roll : 0);
            
            $length_data = (object)[];
            $length_data->category = 'Length (YDs)';
            $length_data->packinglist_qty = $roll_summary_in_packinglist->total_length_yds . ' yds';
            $length_data->stock_in = ($stock_in_summary ? $stock_in_summary->total_length_yds : '0' ) . ' yds';
            $length_data->balance = $roll_summary_in_packinglist->total_length_yds - ($stock_in_summary ? $stock_in_summary->total_length_yds : 0) . ' yds';
            
            $weight_data = (object)[];
            $weight_data->category = 'Weight (KGs)';
            $weight_data->packinglist_qty = $roll_summary_in_packinglist->total_weight_kgs . ' kgs';
            $weight_data->stock_in = ($stock_in_summary ? $stock_in_summary->total_weight_kgs : '0' ) . ' kgs';
            $weight_data->balance = $roll_summary_in_packinglist->total_weight_kgs - ($stock_in_summary ? $stock_in_summary->total_weight_kgs : 0) . ' kgs';
            
            $roll_summary = [
                $roll_data,
                $length_data,
                $weight_data,
            ];
        } else {
            $roll_summary = [];
        }

        $data = [
            'packinglist' => $packinglist,
            'roll_summary' => $roll_summary,
            'collapsed_card_class' => $collapsed_card_class,
        ];
        $component = view('pages.packinglist.card-information', $data)->render();
        return response()->json(['component' => $component], 200);
    }

    // ## Print QRCode
    public function print_qrcode(Request $request)
    {
        $id = explode(',', $request->id);
        $fabricrolls = FabricRoll::with('packinglist.color')->whereIn('id', $id)->get();
        // ## 6cm x 4cm uk sticker
        $customepaper = array(0,0,170,113.38);
        
        $data = [
            'fabricrolls' => $fabricrolls,
        ];
        
        $pdf = PDF::loadview('pages.packinglist.qrcode', $data)->setPaper($customepaper, 'landscape');
        return $pdf->stream('fabric-roll.pdf');
    }
}