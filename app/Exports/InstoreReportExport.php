<?php

namespace App\Exports;

use App\Models\Packinglist;
use Maatwebsite\Excel\Concerns\FromCollection;

class InstoreReportExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Packinglist::all();
    }
}