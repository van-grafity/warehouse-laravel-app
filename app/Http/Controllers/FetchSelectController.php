<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\Invoice;
use App\Models\Rack;


class FetchSelectController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display a fetch select option.
     */
    public function index()
    {
        try {
            $fetch_list = [
                'fetch-select.invoice' => [
                    'title' => 'Get Invoice for select2',
                    'description' => 'Ambil semua invoice yang ada untuk list di select2 form',
                    'url' => route('fetch-select.invoice'),
                ],
                'fetch-select.color' => [
                    'title' => 'Get Color for select2',
                    'description' => 'Ambil semua warna yang ada untuk list di select2 form',
                    'url' => route('fetch-select.color'),
                ],
                'fetch-select.rack' => [
                    'title' => 'Get Rack for select2',
                    'description' => 'Ambil semua warna yang ada untuk list di select2 form',
                    'url' => route('fetch-select.rack'),
                ],
            ];
            $data_return = [
                'status' => 'success',
                'data'=> $fetch_list,
                'message'=> 'Fetch List',
            ];
            return response()->json($data_return);

        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage()
            ];
            return response()->json($data_return);
        }
    }

    
    /**
     * Retrived color list for select2 option.
     */
    public function select_color()
    {
        try {
            $id = request()->id;
            if($id) {
                $color = Color::
                    select('id','colors.color as text')
                    ->find($id);

                if($color) {
                    $data_return = [
                        'status' => 'success',
                        'data'=> [
                            'items' => $color,
                        ],
                        'message'=> 'Color Found',
                    ];
                } else {
                    $data_return = [
                        'status' => 'error',
                        'data'=> [],
                        'message'=> 'Color Not Found',
                    ];
                }
                return response()->json($data_return);
                
            } else {
                $color = request()->search;
                $color_list = Color::
                    when($color, static function ($query, $color) {
                        $query->where('color','LIKE','%'.$color.'%');
                    })
                    ->select('id','colors.color as text')
                    ->get();
                
                $data_return = [
                    'status' => 'success',
                    'data'=> [
                        'items' => $color_list,
                    ],
                    'message'=> 'Color List',
                ];
                return response()->json($data_return);
            }

        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage()
            ];
            return response()->json($data_return);
        }
    }

    public function select_invoice()
    {
        try {
            $id = request()->id;
            if($id) {
                $invoice = Invoice::
                    select('id','invoices.invoice_number as text')
                    ->find($id);

                if($invoice) {
                    $data_return = [
                        'status' => 'success',
                        'data'=> [
                            'items' => $invoice,
                        ],
                        'message'=> 'Invoice Found',
                    ];
                } else {
                    $data_return = [
                        'status' => 'error',
                        'data'=> [],
                        'message'=> 'Invoice Not Found',
                    ];
                }
                return response()->json($data_return);
                
            } else {
                $invoice_number = request()->search;
                $invoice_list = Invoice::
                    when($invoice_number, static function ($query, $invoice_number) {
                        $query->where('invoice_number','LIKE','%'.$invoice_number.'%');

                    })
                    ->select('id','invoices.invoice_number as text')
                    ->get();
                
                $data_return = [
                    'status' => 'success',
                    'data'=> [
                        'items' => $invoice_list,
                    ],
                    'message'=> 'Invoice List',
                ];
                return response()->json($data_return);
            }

        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage()
            ];
            return response()->json($data_return);
        }
    }

    public function select_rack()
    {
        try {
            $id = request()->id;
            if($id) {
                $rack = Rack::
                    select('id','racks.serial_number as text')
                    ->find($id);

                if($rack) {
                    $data_return = [
                        'status' => 'success',
                        'data'=> [
                            'items' => $rack,
                        ],
                        'message'=> 'Rack Found',
                    ];
                } else {
                    $data_return = [
                        'status' => 'error',
                        'data'=> [],
                        'message'=> 'Rack Not Found',
                    ];
                }
                return response()->json($data_return);
                
            } else {
                $rack = request()->search;
                $rack_list = Rack::
                    when($rack, static function ($query, $rack) {
                        $query->where('serial_number','LIKE','%'.$rack.'%');

                    })
                    ->select('id','racks.serial_number as text')
                    ->get();
                
                $data_return = [
                    'status' => 'success',
                    'data'=> [
                        'items' => $rack_list,
                    ],
                    'message'=> 'Rack List',
                ];
                return response()->json($data_return);
            }

        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage()
            ];
            return response()->json($data_return);
        }
    }
}
