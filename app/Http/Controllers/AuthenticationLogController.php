<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Yajra\Datatables\Datatables;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog as Log;
use App\Models\User;
use Carbon\Carbon;


class AuthenticationLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'title' => 'Authentication Log',
            'page_title' => 'Authentication Log List',
        ];
        return view('pages.authentication-log.index', $data);
    }

    public function dtable()
    {
        $query = Log::join('users','users.id','=','authentication_log.authenticatable_id');

        return Datatables::of($query)
            ->addIndexColumn()
            ->escapeColumns([])
            ->addColumn('user_email', function($row){
                return $row->authenticatable ? $row->authenticatable->email : User::withTrashed()->find($row->authenticatable_id)->email;
            })
            ->addColumn('user_name', function($row){
                return $row->authenticatable ? $row->authenticatable->name : User::withTrashed()->find($row->authenticatable_id)->name;
            })
            ->editColumn('login_at', function($row){
                
                return $row->login_at ? Carbon::parse($row->login_at)->format('Y-m-d H:i:s') : '-';
            })
            ->addColumn('login_status', function($row){
                return $row->login_successful ? 'Yes' : 'No';
            })
            ->editColumn('logout_at', function($row){
                return $row->logout_at ? Carbon::parse($row->logout_at)->format('Y-m-d H:i:s') : '-';
            })
            ->toJson();
    }
}
