@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<!-- // !! file ini nanti di hapus kalau memang tidak perlu-->

<h4 class="text-center">Fabric Warehouse Rack Information</h4>
<table class="table table-bordered table-hover">
    <thead>
        <tr class="table-primary">
            <th colspan="4" class="text-center" width="10%">Rack A</th>
            <th colspan="4" class="text-center" width="10%">Rack B</th>
            <th colspan="4" class="text-center" width="10%">Rack C</th>
            <th colspan="4" class="text-center" width="10%">Rack D</th>
            <th colspan="4" class="text-center" width="10%">Rack E</th>
        </tr>
        <tr class="table-primary">
            <th class="text-center"> Brand </th>
            <th class="text-center"> GL </th>
            <th class="text-center"> Colour </th>
            <th class="text-center"> Rack </th>

            <th class="text-center"> Brand </th>
            <th class="text-center"> GL </th>
            <th class="text-center"> Colour </th>
            <th class="text-center"> Rack </th>

            <th class="text-center"> Brand </th>
            <th class="text-center">GL</th>
            <th class="text-center">Clour </th>
            <th class="text-center"> Rack </th>

            <th class="text-center"> Brand </th>
            <th class="text-center"> GL </th>
            <th class="text-center"> Colour </th>
            <th class="text-center"> Rack </th>

            <th class="text-center">Brand</th>
            <th class="text-center"> GL </th>
            <th class="text-center"> Colour </th>
            <th class="text-center"> Rack </th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; ?>
        <tr>
            <td class=" text-center" scope="row"><?= $i++; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
</div>
@endsection