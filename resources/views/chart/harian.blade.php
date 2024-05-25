@extends('layout.app')

@section('title', 'Grafik Harian')

@section('content_header')
    <h1>Grafik Harian</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rekap Kunjungan Pasien</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <input type="date" id="startDate" class="form-control" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <button id="filterButton" class="btn btn-primary">Filter</button>
                        </div>
                        <div class="col-md-4 text-right">
                            <button id="printButton" class="btn btn-primary">Print</button>
                        </div>
                    </div>
                    <div class="chart">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Hari ini'],
                datasets: [
                    {
                        label: 'Baru',
                        data: [{{ $newPatientsCount }}],
                        backgroundColor: '#205375',
                        borderColor: 'rgba(54, 162, 235, 1)',
                    },
                    {
                        label: 'Lama',
                        data: [{{ $oldPatientsCount }}],
                        backgroundColor: '#112B3C',
                        borderColor: 'rgba(255, 159, 64, 1)',
                    },
                    {
                        label: 'Total',
                        data: [{{ $totalPatientsCount }}],
                        backgroundColor: '#F66B0E',
                        borderColor: 'rgba(75, 192, 192, 1)',
                    }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Handle print button click
        document.getElementById('printButton').addEventListener('click', function () {
            var canvas = document.getElementById('myChart');
            var win = window.open('', 'Print Chart');
            win.document.write('<html><head><title>Print Chart</title></head><body>');
            win.document.write('<img src="' + canvas.toDataURL() + '"/>');
            win.document.write('</body></html>');
            win.document.close();
            win.print();
        });

        // Handle filter button click
        document.getElementById('filterButton').addEventListener('click', updateChart);

        function updateChart() {
            var startDate = document.getElementById('startDate').value;
            
            // Fetch and update the chart data based on the selected date range
        fetch(`/chart/harian?startDate=${startDate}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            myChart.data.datasets[0].data = [data.newPatientsCount];
            myChart.data.datasets[1].data = [data.oldPatientsCount];
            myChart.data.datasets[2].data = [data.totalPatientsCount];
            myChart.update();
        })
        .catch(error => console.error('Error fetching data:', error));
        }
    });
</script>
<style>
    .card-body .chart {
        position: relative;
        height: 300px; /* Adjust the height as needed */
    }

    #myChart {
        width: 100% !important;
        height: 100% !important;
    }
</style>
@stop
