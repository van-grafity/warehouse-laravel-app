<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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
        $query = User::join('departments', 'departments.id' ,'=', 'users.department_id','left')
            ->select('users.*','departments.department')
            ->withTrashed()
            ->get();
        
        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('action', function($row){
                $action_button = "";
                if(!$row->deleted_at){
                    $action_button .= "
                        <a href='javascript:void(0)' class='btn btn-sm mb-1 btn-primary' onclick='show_modal_edit(\"modal_user\", $row->id)' >Edit</a>
                        <a href='javascript:void(0)' class='btn btn-sm mb-1 btn-info'  onclick='show_modal_reset_password($row->id)'>Reset Password</a>    
                        <a href='javascript:void(0)' class='btn btn-sm mb-1 btn-danger'  onclick='show_modal_delete($row->id)'>Delete</a>
                    ";
                } else {
                    $action_button .= "
                        <a href='javascript:void(0)' class='btn btn-sm mb-1 bg-orange' onclick='show_modal_undo_delete($row->id)' >Undo Delete</a>
                        <a href='javascript:void(0)' class='btn btn-sm mb-1 btn-danger' onclick='show_modal_delete_permanent($row->id)'>Delete Permanently  <i class='fas fa-exclamation-triangle'></i></a>
                    ";
                }
                return $action_button;
                
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
            $check_duplicate_email = User::where('email', $request->email)->withTrashed()->first();
            if($check_duplicate_email){
                throw new \Exception("Email already exists, please provide another email", 1);
            }
            
            $user = User::firstOrCreate([
                'name' => $request->name,
                'email' => $request->email,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'department_id' => $request->department,
                'password' => env('DEFAULT_PASSWORD') ? env('DEFAULT_PASSWORD') : '123456789',
            ]);

            $user->syncRoles($request->role);

            $data_return = [
                'status' => 'success',
                'message' => 'Successfully created user ' . $user->name,
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
                'message' => 'Successfully get user ' . $user->user,
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
                'message' => 'Successfully updated user '. $user->name,
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

    public function reset_password(string $id)
    {
        try {
            $user = User::find($id);
            $user->password = env('DEFAULT_PASSWORD') ? env('DEFAULT_PASSWORD') : '123456789';
            $user->save();
            
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully reset password for User ' . $user->name,
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
}
