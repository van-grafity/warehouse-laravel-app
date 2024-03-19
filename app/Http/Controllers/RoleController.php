<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;


use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Role',
            'page_title' => 'Role List',
        ];
        return view('pages.role.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = Role::get();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_role\', '.$row->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="show_modal_delete('.$row->id.')">Delete</a>
                <a href="" class="btn btn-info btn-sm">Permission</a>
                ';
            })
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $role = Role::create([
                'name' => $request->role,
                'title' => $request->title,
                'description' => $request->description,
            ]);
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new role (' . $role->name . ')',
                'data' => [
                    'role' => $role,
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
            $role = Role::find($id);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get role (' . $role->role . ')',
                'data' => [
                    'role' => $role,
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
            $role = Role::find($id);
            $role->name = $request->role;
            $role->title = $request->title;
            $role->description = $request->description;
            $role->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated role ('. $role->name .')',
                'data' => $role
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
            $role = Role::find($id);
            $role->delete();
            
            $data_return = [
                'status' => 'success',
                'data'=> $role,
                'message'=> 'Role '.$role->role.' successfully Deleted!',
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
