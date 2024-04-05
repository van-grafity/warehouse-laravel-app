<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
    public function dtable(Request $request)
    {
        if(auth()->user()->can('developer-menu')){
            
            $query = User::withTrashed();

        } else {
            
            $query = User::withTrashed()->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['developer','admin']);
            });
        }
        
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
                        <a href='javascript:void(0)' class='btn btn-sm mb-1 bg-orange' onclick='show_modal_restore($row->id)' >Restore</a>
                        <a href='javascript:void(0)' class='btn btn-sm mb-1 btn-danger' onclick='show_modal_delete_permanent($row->id)'>Delete Permanently  <i class='fas fa-exclamation-triangle'></i></a>
                    ";
                }
                return $action_button;
                
            })
            ->addColumn('department', function($row){
                return $row->department->department;
            })
            ->addColumn('role', function($row){
                $roles = $row->getRoleTitles();

                $result = implode(' | ', $roles->toArray());
                return $result;
            })
            ->filter(function ($query) {
                if (request()->has('data_status')) {
                    if (request('data_status') == 1) {
                        $query->where('deleted_at', null);
                    }
                    if (request('data_status') == 2) {
                        $query->where('deleted_at', '!=', null);
                    }
                }
            }, true)
            ->addColumn('created_date', function($row){
                $readable_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at);
                $readable_datetime = $readable_datetime->format('d F y, H:i');
                return $readable_datetime;
            })
            ->toJson();
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
                'message' => 'Successfully create user ' . $user->name,
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
                'message' => 'Successfully update user '. $user->name,
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
            $user = User::withTrashed()->find($id);
            $data_return = [
                'status' => 'success',
                'data'=> $user,
            ];

            if($user->deleted_at) {
                $user->forceDelete();
                $data_return['message'] = 'Successfully Permanetly Delete user ' . $user->name . ' !';
            } else {
                $user->delete();
                $data_return['message'] = 'Successfully Delete user ' . $user->name . ' !';
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

    public function restore(string $id)
    {
        try {
            User::withTrashed()
                ->where('id', $id)
                ->restore();

            $user = User::find($id);
            $data_return = [
                'status' => 'success',
                'message' => 'Successfully Restore User ' . $user->name,
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

    public function profile()
    {
        $user = User::find(auth()->user()->id);
        $user->role = $user->roles[0]->title;
        
        $data = [
            'title' => 'Profile',
            'page_title' => 'User Profile',
            'user' => $user,
        ];
        return view('pages.profile.index', $data);
    }

    public function change_password(Request $request)
    {
        try {
            $user = auth()->user();
            if(!Hash::check($request->old_password, $user->password)) {
                $date_return = [
                    'status' => 'failed',
                    'message'=> 'Incorrect old Password!',
                ];
                return response()->json($date_return, 200);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            $date_return = [
                'status' => 'success',
                'message'=> 'your password has changed',
            ];
            return response()->json($date_return, 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
    
}
