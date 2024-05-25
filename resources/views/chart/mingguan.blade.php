@extends('layout.app')

@section('title', 'Grafik Mingguan')

@section('content_header')
    <h1>Grafik Mingguan</h1>
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
                        <div class="col-md-3">
                            <input type="date" id="startDate" class="form-control" value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="date" id="endDate" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <button id="filterButton" class="btn btn-primary">Filter</button>
                        </div>
                        <div class="col-md-3 text-right">
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
                labels: {!! json_encode($labels) !!},
                datasets: [
                    {
                        label: 'Baru',
                        data: {!! json_encode($new_patients) !!},
                        backgroundColor: '#205375',
                        borderColor: 'rgba(54, 162, 235, 1)',
                    },
                    {
                        label: 'Lama',
                        data: {!! json_encode($old_patients) !!},
                        backgroundColor: '#112B3C',
                        borderColor: 'rgba(255, 159, 64, 1)',
                    },
                    {
                        label: 'Total',
                        data: {!! json_encode($total_patients) !!},
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

        document.getElementById('filterButton').addEventListener('click', updateChart);

        function updateChart() {
            var startDate = document.getElementById('startDate').value;
            var endDate = document.getElementById('endDate').value;

            fetch('/chart/mingguan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    startDate: startDate,
                    endDate: endDate
                })
            })
            .then(response => response.json())
            .then(data => {
                myChart.data.labels = data.labels;
                myChart.data.datasets[0].data = data.new_patients;
                myChart.data.datasets[1].data = data.old_patients;
                myChart.data.datasets[2].data = data.total_patients;
                myChart.update();
            })
            .catch(error => console.error('Error:', error));
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
