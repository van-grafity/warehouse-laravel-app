<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Color;
use App\Models\Packinglist;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class ColorController extends Controller
{
    public function __construct()
    {
        Gate::define('manage', function ($user) {
            return $user->hasPermissionTo('color.manage');
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Color',
            'page_title' => 'Color List',
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.color.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = Color::get();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_color\', '.$row->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="show_modal_delete('.$row->id.')">Delete</a>';
            })
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $color = Color::firstOrCreate([
                'color' => $request->color,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new color (' . $color->color . ')',
                'data' => [
                    'color' => $color,
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
            $color = Color::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get color (' . $color->color . ')',
                'data' => [
                    'color' => $color,
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
            $color = Color::find($id);
            $color->color = $request->color;
            $color->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated color ('. $color->color .')',
                'data' => $color
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
            $color = Color::find($id);
            $is_packinglist_exists = Packinglist::where('color_id', $id)->exists();

            // ## Periksa apakah ada packinglist yang menggunakan color dengan id yang diberikan
            if ($is_packinglist_exists){
                $data_return = [
                    'status' => 'error',
                    'message' => 'Failed to delete color '.$color->color.', because this color has been used on packing list!'
                ];
            } else {
                $color->delete();
                $data_return = [
                    'status' => 'success',
                    'message' => 'Color '.$color->color.' Successfully Deleted!'
                ];
            }

            return response()->json($data_return, 200);
        } catch (\Throwable $th) {
            $data_return = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];
            return response()->json($data_return);
        }
    }
}
