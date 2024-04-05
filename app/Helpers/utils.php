<?php

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 8)
    {
        $characters = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $serialNumber = '';
        for ($i = 0; $i < $length; $i++) {
            $serialNumber .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $serialNumber;
    }
}


if (!function_exists('normalizeNumber')) {
    function normalizeNumber($number, $add_zero = 1) {
        // Menghilangkan semua nol di depan
        $result = ltrim($number, '0');

        // Jika setelah dihilangkan nol semua, hasilnya kosong, set menjadi '0'
        if (empty($result)) {
            $result = '0';
        }

        // Menambahkan nol di depan jika hanya terdapat 1 digit
        $result = str_pad($result, $add_zero + 1, '0', STR_PAD_LEFT);

        return $result;
    }
}
if (!function_exists('generateColorCode')) {
    function generateColorCode($color, $special_word_opt = true) {

        $special_words = array();
        if($special_word_opt){
            $special_words = specialColorCode();
        }
        foreach ($special_words as $search => $word) {
            $color = str_replace(strtoupper($search), $word, strtoupper($color));
        }

        // Ubah ke huruf besar dan hapus spasi
        $color = str_replace(' ', '', strtoupper($color));

        // Maksimum panjang karakter untuk bagian warna adalah 12
        $color = substr($color, 0, 10);

        // Jika kurang dari 12 karakter, tambahkan 'X' untuk mencapai panjang 12
        $colorCode = str_pad($color, 12, str_shuffle('123456ABCDEF'), STR_PAD_RIGHT);

        return $colorCode;
    }
    
}

if (!function_exists('specialColorCode')) {
    function specialColorCode() {
        $special_words = [
            'classic' => 'cls',
            'dark' => 'drk',
            'light' => 'lgh',
            'white' => 'wht',
            'black' => 'blck',
            'night' => 'nght',
            'midnight' => 'mdnght',
            'bright' => 'brht',
            'true' => 'tru',
            'heather' => 'htr',
            'stone' => 'stn',
            'chambray' => 'chamb',
            'diamond' => 'diamd',
            'sparkling' => 'sprkl',
            'spark' => 'sprk',
            
            'captain' => 'capt',
            'ballerina' => 'bal',
            'balerina' => 'bal',
            'balerina' => 'bal',
            'alabaster' => 'ala',
            'scarlet' => 'scarlt',
            'ultra' => 'ult',
            'pitch' => 'ptch',
            'platinum' => 'pltnm',
            'hisbiscus' => 'hisbcs',
            'marine' => 'mar',
        ];
        return $special_words;
    }
    
}
