<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\PermissionCategory;
use App\Models\Permission;


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
                <a href="'. route('role.manage-permission', $row->id) .'" class="btn btn-info btn-sm">Permission</a>
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

    public function manage_permission(String $id)
    {
        $role = Role::find($id);
        $permission_categories = PermissionCategory::get();
        $permission_by_categories = [];

        foreach ($permission_categories as $key => $category) {
            $permissions = Permission::where('permission_category_id',$category->id)->get();
            
            if($permissions){
                $permission_checked_counter = 0;
                foreach ($permissions as $key_permission => $permission) {
                    
                    $permission->is_role_has_permission = $role->hasPermissionTo($permission->name);
                    if($permission->is_role_has_permission) { $permission_checked_counter++; }
                    
                }
                $is_checked = (count($permissions) == $permission_checked_counter && count($permissions) > 0) ? true : false;
            } else {
                $is_checked = false;
            }

            $category_data = (object) [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'permissions' => $permissions,
                'is_checked' => $is_checked,
            ];
            
            $permission_by_categories[] = $category_data;
        }

        $data = [
            'title' => 'Manage Permissions',
            'page_title' => '',
            'role' => $role,
            'permission_by_categories' => $permission_by_categories,
        ];

        return view('pages.role.manage-permission', $data);
    }

    public function manage_permission_update(Request $request, String $id)
    {
        $selected_permission = $request->selected_permission;
        try {
            DB::beginTransaction();
            $role = Role::find($id);
            if($selected_permission) {
                $permissions = Permission::whereIn('id', $selected_permission)->get()->pluck('name');
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }
            DB::commit();
            
            return redirect()->route('role.manage-permission', ['role' => $id])->with('success', 'Succesfully set permissions for '. $role->title);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('errors', $th->getMessage());
        }
    }
}
