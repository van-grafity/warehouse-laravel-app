<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Department;
use Spatie\Permission\Models\Role;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $department = Department::get();
        $user = User::find(auth()->user()->id);
        $user->role = $user->roles[0]->title;        
        $data = [
            'title' => 'Home',
            'page_title' => 'Dashboard',
            'user' => $user,
            'department' => $department,

        ];
        return view('home', $data);
    }
}