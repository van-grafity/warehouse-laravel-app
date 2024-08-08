@extends('layouts.template')

@section('title', $title)
@section('page_title', $page_title)

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->

<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <h2 class="chart-title">Invoice Chart</h2>
            <div class="chart-container-pie">
                <canvas id="PieChart"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
    .chart-container-pie {
        /* margin: auto; */
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px;
    }
    
    .chart-title {
        text-align: center;
        padding-top: 10px;
    }
</style>
@endsection

@section('js')
<script type="text/javascript">
    var labelsPie =  {{ Js::from($labels) }};
    var datasPie =  {{ Js::from($datas) }};

    const dataPie = {
        labels: labelsPie,
        datasets: [{
            label: 'Invoice Chart',
            backgroundColor: [
              'rgb(220, 20, 60)',
              'rgb(255, 99, 71)',
              'rgb(255, 69, 0)',
              'rgb(255, 127, 80)',
              'rgb(205, 92, 92)',
              'rgb(240, 128, 128)',
              'rgb(233, 150, 122)',
              'rgb(255, 140, 0)',
              'rgb(255, 165, 0)',
            ],
            data: datasPie,
        }]
    };

    const configPie = {
        type: 'pie',
        data: dataPie,
        options: {
          legend: {
            position: "bottom",
            labels: {
              fontSize: 10
            }
          }
        }
    };

    const PieChart = new Chart(
        document.getElementById('PieChart'),
        configPie
    );
</script>
@stop