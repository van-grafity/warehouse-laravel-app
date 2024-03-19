<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\PermissionCategory;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permission_categories = PermissionCategory::get();
        $data = [
            'title' => 'Permission',
            'page_title' => 'Permission List',
            'permission_categories' => $permission_categories
        ];
        return view('pages.permission.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = Permission::get();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_permission\', '.$row->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="show_modal_delete('.$row->id.')">Delete</a>';
            })
            ->addColumn('permission_category', function($row){
                return $row->permission_category->name;
            })
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $permission = Permission::create([
                'name' => $request->permission,
                'description' => $request->description,
                'permission_category_id' => $request->permission_category,
            ]);
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new permission (' . $permission->name . ')',
                'data' => [
                    'permission' => $permission,
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
            $permission = Permission::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get permission (' . $permission->permission . ')',
                'data' => [
                    'permission' => $permission,
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
            $permission = Permission::find($id);
            $permission->name = $request->permission;
            $permission->description = $request->description;
            $permission->permission_category_id = $request->permission_category;
            $permission->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated permission ('. $permission->name .')',
                'data' => $permission
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
            $permission = Permission::find($id);
            $permission->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $permission,
                'message'=> 'Permission '.$permission->permission.' successfully Deleted!',
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
