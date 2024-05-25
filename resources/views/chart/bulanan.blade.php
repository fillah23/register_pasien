@extends('layout.app')

@section('title', 'Grafik Bulanan')

@section('content_header')
    <h1>Grafik Bulanan</h1>
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
                        <div class="col-md-4">
                            <form method="GET" action="">
                                <div class="input-group">
                                    <input type="month" name="filter_month" class="form-control" value="{{ $filterMonth }}">
                                    <button class="btn btn-primary" type="submit">Filter</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-8 text-right">
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
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5'],
                datasets: [
                    {
                        label: 'Baru',
                        data: [0, 0, 0, 0, 0],
                        backgroundColor: '#205375',
                        borderColor: 'rgba(54, 162, 235, 1)',
                    },
                    {
                        label: 'Lama',
                        data: [0, 0, 0, 0, 0],
                        backgroundColor: '#112B3C',
                        borderColor: 'rgba(255, 159, 64, 1)',
                    },
                    {
                        label: 'Total',
                        data: [0, 0, 0, 0, 0],
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
            myChart.data.datasets[0].data = data.new_patients;
            myChart.data.datasets[1].data = data.old_patients;
            myChart.data.datasets[2].data = data.total_patients;
            myChart.update();
        }

        fetch('/chart/bulanan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                filter_month: '{{ $filterMonth }}'
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
