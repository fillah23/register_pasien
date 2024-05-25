@extends('layout.app')

@section('title', 'Grafik Tahunan')

@section('content_header')
    <h1>Grafik Tahunan</h1>
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
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="">
                                <div class="input-group">
                                    <input type="number" name="filter_year" class="form-control" min="2020" max="2100" value="{{ $filterYear }}">
                                    <button class="btn btn-primary" type="submit">Filter</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-right">
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
                labels: [], // Initialize with empty labels
                datasets: [
                    {
                        label: 'Baru',
                        data: [],
                        backgroundColor: '#205375',
                        borderColor: 'rgba(54, 162, 235, 1)',
                    },
                    {
                        label: 'Lama',
                        data: [],
                        backgroundColor: '#112B3C',
                        borderColor: 'rgba(255, 159, 64, 1)',
                    },
                    {
                        label: 'Total',
                        data: [],
                        backgroundColor: '#F66B0E',
                        borderColor: 'rgba(75, 192, 192, 1)',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function updateChart(data) {
            myChart.data.labels = data.labels;
            myChart.data.datasets[0].data = data.new_patients;
            myChart.data.datasets[1].data = data.old_patients;
            myChart.data.datasets[2].data = data.total_patients;
            myChart.update();
        }

        fetch('/chart/tahunan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                filter_year: '{{ $filterYear }}'
            })
        })
        .then(response => response.json())
        .then(data => updateChart(data))
        .catch(error => console.error('Error:', error));

        document.getElementById('printButton').addEventListener('click', function () {
            var canvas = document.getElementById('myChart');
            var win = window.open('', 'Print Chart');
            win.document.write('<html><head><title>Print Chart</title></head><body>');
            win.document.write('<img src="' + canvas.toDataURL() + '"/>');
            win.document.write('</body></html>');
            win.document.close();
            win.print();
        });
    });
</script>
<style>
    .card-body .chart {
        position: relative;
        height: 400px; /* Adjust the height as needed */
    }

    #myChart {
        width: 100% !important;
        height: 100% !important;
    }
</style>
@stop
