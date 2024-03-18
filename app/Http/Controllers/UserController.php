<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Spatie\Permission\Models\Role;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::get();
        $roles = Role::get();
        $data = [
            'title' => 'User',
            'page_title' => 'User List',
            'departments' => $departments,
            'roles' => $roles,
        ];
        return view('pages.user.index', $data);
    }

    /**
     * Show Datatable Data.
     */
    public function dtable()
    {
        $query = User::join('departments', 'departments.id' ,'=', 'users.department_id')
            ->select('users.*','departments.department')
            ->get();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($data){
                return '
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="show_modal_edit(\'modal_user\', '.$data->id.')">Edit</a>
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="show_modal_delete('.$data->id.')">Delete</a>';
            })
            ->addColumn('role', function($data){
                $roles = $data->getRoleTitles();

                $result = implode(' | ', $roles->toArray());
                return $result;
            })
            ->addColumn('created_date', function($row){
                $readable_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at);
                $readable_datetime = $readable_datetime->format('d F y, H:m');
                return $readable_datetime;
            })
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = User::firstOrCreate([
                'user' => $request->user,
                'description' => $request->description,
            ]);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully added new user (' . $user->user . ')',
                'data' => [
                    'user' => $user,
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
            $user = User::find($id);
            $user->role = $user->roles[0]->name;

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully get user (' . $user->user . ')',
                'data' => [
                    'user' => $user,
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
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->department_id = $request->department;

            $user->save();
            
            $user->syncRoles($request->role);
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully updated user ('. $user->name .')',
                'data' => $user
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
            $user = User::find($id);
            $user->delete();
            $data_return = [
                'status' => 'success',
                'data'=> $user,
                'message'=> 'User '.$user->user.' successfully Deleted!',
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
