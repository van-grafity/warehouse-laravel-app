<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RackDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Rack Details',
            'page_title' => 'Rack Details'
        ];
        return view('pages.reports.rackdashboard', $data);
    }
}
