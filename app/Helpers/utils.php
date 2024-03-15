<?php

if (!function_exists('isSuperadmin')) {
    function isSuperadmin() : bool
    {
        return auth()->user()->inGroup('developer');
    }
}

if (!function_exists('isUserGroup')) {
    function isUserGroup(Array $allowedGroup = []) : bool
    {
        if(isSuperadmin()) return true;
        return auth()->user()->inGroup(...$allowedGroup);
    }
}

if (!function_exists('isUserCan')) {
    function isUserCan(Array $allowedPermission = []) : bool
    {
        if(isSuperadmin()) return true;
        return auth()->user()->can(...$allowedPermission);
    }
}

if (!function_exists('menuHasChildren')) {
    function menuHasChildren($menuId, $menus)
    {
        foreach ($menus as $menu) {
            if ($menu->parent_id === $menuId) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('renderSubMenu')) {
    function renderSubMenu($parent_id, $menu_list)
    {
        foreach ($menu_list as $submenu) {
            if(!auth()->user()->can($submenu->permission)) { continue; }
            
            if ($submenu->parent_id == $parent_id) {
                $submenu_url = ($submenu->slug) ? base_url($submenu->slug) : '';
                ?>
                <li class="nav-item">
                    <a href="<?= $submenu_url ?>" class="nav-link">
                        <?php if ($submenu->icon) : ?>
                            <i class="<?= $submenu->icon ?> nav-icon"></i>
                        <?php endif; ?>
                        <p><?= $submenu->title ?></p>
                        <?php if (menuHasChildren($submenu->id, $menu_list)) : ?>
                            <i class="right fas fa-angle-left"></i>
                        <?php endif; ?>
                    </a>
                    <?php if (menuHasChildren($submenu->id, $menu_list)) : ?>
                        <!-- Sub-submenu -->
                        <ul class="nav nav-treeview pl-3">
                            <?php renderSubMenu($submenu->id, $menu_list); ?>
                        </ul>
                    <?php endif; ?>
                </li>
                <?php
            }
        }
    }
}

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
