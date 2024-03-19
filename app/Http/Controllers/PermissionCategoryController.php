<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermissionCategory;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class PermissionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Permission Category',
            'page_title' => 'Permission Category List'
        ];
        return view('pages.permission-category.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = PermissionCategory::get();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_permission_category\', '.$row->id.')">Edit</a>
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
            $permission_category = PermissionCategory::firstOrCreate([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new permission (' . $permission_category->name . ')',
                'data' => [
                    'permission_category' => $permission_category,
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
            $permission_category = PermissionCategory::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get permission (' . $permission_category->name . ')',
                'data' => [
                    'permission_category' => $permission_category,
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
            $permission_category = PermissionCategory::find($id);
            $permission_category->name = $request->name;
            $permission_category->description = $request->description;

            $permission_category->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated permission ('. $permission_category->name .')',
                'data' => $permission_category
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
            $permission_category = PermissionCategory::find($id);
            $permission_category->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $permission_category,
                'message'=> 'Permission '.$permission_category->name.' successfully Deleted!',
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
}
