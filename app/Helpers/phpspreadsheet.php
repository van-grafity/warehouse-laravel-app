<?php

if (!function_exists('parseExceltoArray')) {
    function parseExceltoArray($worksheet) : array 
    {
        $data = [];
        $firstRow = true;
        $header = [];

        foreach ($worksheet->getRowIterator() as $row) {
            if ($firstRow) {
                foreach ($row->getCellIterator() as $cell) {
                    $header[$cell->getColumn()] = $cell->getValue();
                }
                $firstRow = false;
            } else {
                $rowData = [];
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                foreach ($cellIterator as $cell) {
                    $columnIndex = $cell->getColumn();
                    $headerIndex = array_key_exists($columnIndex, $header);
                    if ($headerIndex !== false) {
                        $rowData[$header[$columnIndex]] = $cell->getCalculatedValue();
                    }
                }
                if (!empty($rowData)) {
                    $data[] = $rowData;
                }
            }
        }

        $data_return = [
            'header' => $header,
            'data' => $data,
        ];

        return $data_return;
    }
}

if (!function_exists('removeEmptyData')) {
    function removeEmptyData(Array $data_array_from_excel, Array $required_column) : array
    {
        $result = array();
        foreach ($data_array_from_excel as $key => $array_row) {
            $is_valid_data = true;
            foreach ($required_column as $column) {
                if(!$array_row[$column]  && $array_row[$column] !== "0"){
                    $is_valid_data = false;
                    break;
                }
            }
            if($is_valid_data) {
                $result[] = $array_row; 
            }
        }

        foreach ($result as $key => $array_row) {
            $result[$key] = array_filter($array_row, fn($key_array_row) => $key_array_row !== '', ARRAY_FILTER_USE_KEY);  
        }
        return $result;
    }
}

if (!function_exists('removeWhitespace')) {
    function removeWhitespace($data_array) : array
    {
        // ## Cleaning data. remove whitespace from beginning and end of string
        foreach ($data_array as $key => $array_row) {
            foreach ($array_row as $prop => $value) {
                $data_array[$key][$prop] = trim($value);
            }
        }
        return $data_array;
    }
}

if (!function_exists('filterArrayByKeys')) {
    function filterArrayByKeys(Array $data_array, Array $allowed_keys) : Array
    {
        /*
         * filter array to get only allowed keys
         * mengembalikan array yang berisi kolom atau key tertentu saja => $allowed_keys
         * ------------------------------------------------------------------------------------------------
         * step: 
         * array mentah => $data_array
         * key yang diinginkan => $allowed_keys
         * array yang berisi allowed keys ($allowed_keys) ditukar antara keys dan value nya menggunakan array_flip()
         * tujuan ditukar agar dapat digunakan sebagai parameter dalam intersect key (proses selanjutnya)
         * perulangan pada $data_array
         * untuk setiap baris dicari yang cocok dengan $allowed_keys menggunakan array_intersect_key()
         * hasil pencocokan ditampung dalam $result
         */

        $result      = array();
        $allowed_keys = array_flip($allowed_keys); // switch keys and values. key as value and value as key
        
        foreach ($data_array as $key => $data_row) {
            // getting only those key value pairs, which matches $allowed_keys
            $result[$key] = array_intersect_key($data_row, $allowed_keys);
        }
        return $result;
    }
}

if (!function_exists('filterUniqueValueByKey')) {
    function filterUniqueValueByKey(Array $data_array, String $key) : Array
    {
        /*
        * Filter array to get only unique values based on the specified column name ($key)
        * Mengembalikan array yang hanya berisi nilai unik pada kolom yang ditentukan
        * ----------------------------------------------------------------------------
        * Langkah:
        * 1. Array mentah => $data_array
        * 2. Pilih kolom => $key
        * 3. Ambil seluruh nilai dari kolom $key menggunakan array_column()
        * 4. Ambil nilai unik dari kolom $key menggunakan array_unique()
        * 5. Ambil nilai untuk semua kolom di baris yang memiliki nilai unik, menggunakan array_intersect_key()
        * 6. Atur ulang keys atau index pada array menggunakan array_values()
        */

        // Step 3: Ambil seluruh nilai dari kolom $key menggunakan array_column()
        $column_values = array_column($data_array, $key);

        // Step 4: Ambil nilai unik dari kolom $key menggunakan array_unique()
        $unique_values = array_unique($column_values);

        // Step 5: Ambil nilai untuk semua kolom di baris yang memiliki nilai unik
        $unique_rows = array_intersect_key($data_array, array_flip(array_keys($unique_values)));

        // Step 6: Atur ulang keys atau index pada array
        $result = array_values($unique_rows);

        return $result;
    }
}


