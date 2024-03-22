<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Packinglist;

class PackinglistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'serial_number' => 'WHA-PL-SYXZ-BALERINAPIA5-2401-001',
                'invoice_id' => 1,
                'buyer' => 'CALVIN KLEIN',
                'gl_number' => '63534-02/03',
                'po_number' => '160021330',
                'color_id' => 1,
                'batch_number' => '105651A1',
                'style' => 'K3CHL824/K3XHV824/M3XHV824/K2XHJ824/Q2XHJ824/K2XHH824/Q2XHH824/M2XHH824/M3DHL824/M3EH2824/M3KHX824/M3XH8824/M2XHU824/M2XHM824',
                'fabric_content' => '95% Cotton 5% Spandex Jersey',
                'remark' => '',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];

        foreach ($data as $key => $packinglist) {
            Packinglist::create($packinglist);
        }
    }
}
