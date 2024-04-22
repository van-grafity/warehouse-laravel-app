<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FabricStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Fabric Status',
            'page_title' => 'Fabric Status',
            'can_manage' => auth()->user()->can('manage'),
        ];
        return view('pages.fabric-status.index', $data);
    }
}
