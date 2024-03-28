<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Events\AfterImport;

class PackinglistsImport implements ToCollection, WithCalculatedFormulas
{
    protected $header;
    protected $data = [];

    /**
     * Import data from collection
     *
     * @param \Illuminate\Support\Collection $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        // ## Get Header (First Row)
        $this->header = $rows->first();

        foreach ($rows as $key => $row) {
            if ($key === 0) {
                continue; // ## Skip first row (header)
            }

            $packinglist = [
                'invoice' => $row[0],
                'buyer' => $row[1],
                'gl_number' => $row[2],
                'po_number' => $row[3],
                'color' => $row[4],
                'batch' => $row[5],
                'style' => $row[6],
                'fabric_content' => $row[7],
                'roll' => $row[8],
                'kgs' => $row[9],
                'lbs' => $row[10],
                'yds' => $row[11],
            ];

            $this->data[] = $packinglist;
        }
    }

    /**
     * Register events for the import process.
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                $this->data = $event->getReader()->getDelegate()->toArray();
            },
        ];
    }

     /**
     * Get the header of the imported file.
     *
     * @return array|null
     */
    public function getHeader(): ?array
    {
        return $this->header ? $this->header->toArray() : null;
    }

    /**
     * Get the imported data.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}

