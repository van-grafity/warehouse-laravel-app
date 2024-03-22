<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;

class InvoicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'invoice_number' => 'D2310/016',
                'container_number' => 'CAIU 9181529',
                'incoming_date' => date('Y-m-01 H:i:s'),
                'offloaded_date' => null,
                'supplier_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'received_at' => date('Y-m-d H:i:s'),
            ],
            [
                'invoice_number' => 'D2310/017',
                'container_number' => 'CAIU 9181530',
                'incoming_date' => date('Y-m-02 H:i:s'),
                'offloaded_date' => null,
                'supplier_id' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'received_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($data as $key => $invoice) {
            Invoice::create($invoice);
        }
    }
}
