@extends('layout.app')

@section('title', 'Monthly Report')

@section('content_header')
<center><h1>Laporan Bulanan</h1></center>
@stop

@section('content')
    <div class="container">
        
        <div class="row mb-3">
            <div class="col-md-2">
                <button class="btn btn-success" id="print-table">Print</button>
            </div>
            <div class="col-md-4">
                <form method="GET" action="{{ route('reports.monthly') }}">
                    <div class="input-group">
                        <input type="month" name="filter_month" class="form-control" value="{{ request('filter_month') }}">
                        <button class="btn btn-primary" type="submit">Filter</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body table-responsive">
                <table id="report-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="3">Tanggal</th>
                            <th colspan="4">P. UMUM</th>
                            <th colspan="4">POLI TB</th>
                            <th colspan="4">IGD</th>
                            <th colspan="4">R. INAP</th>
                            <th colspan="4">KIA</th>
                            <th colspan="4">GIGI</th>
                            <th colspan="2">KB</th>
                            <th colspan="2">VK</th>
                        </tr>
                        <tr>
                            <th colspan="2">BPJS</th>
                            <th colspan="2">UMUM</th>
                            <th colspan="2">BPJS</th>
                            <th colspan="2">UMUM</th>
                            <th colspan="2">BPJS</th>
                            <th colspan="2">UMUM</th>
                            <th colspan="2">BPJS</th>
                            <th colspan="2">UMUM</th>
                            <th colspan="2">BPJS</th>
                            <th colspan="2">UMUM</th>
                            <th colspan="2">BPJS</th>
                            <th colspan="2">UMUM</th>
                            <th rowspan="2">BPJS</th>
                            <th rowspan="2">UMUM</th>
                            <th rowspan="2">BPJS</th>
                            <th rowspan="2">UMUM</th>
                        </tr>
                        <tr>
                            <th>L</th>
                            <th>P</th>
                            <th>L</th>
                            <th>P</th>
                            <th>L</th>
                            <th>P</th>
                            <th>L</th>
                            <th>P</th>
                            <th>L</th>
                            <th>P</th>
                            <th>L</th>
                            <th>P</th>
                            <th>L</th>
                            <th>P</th>
                            <th>L</th>
                            <th>P</th>
                            <th>L</th>
                            <th>P</th>
                            <th>L</th>
                            <th>P</th>
                            <th>L</th>
                            <th>P</th>
                            <th>L</th>
                            <th>P</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData as $report)
                        <tr>
                            <td>{{ $report->tanggal }}</td>
                            <td>{{ $report->UMUM_UMUM_L }}</td><td>{{ $report->UMUM_UMUM_P }}</td><td>{{ $report->UMUM_BPJS_L }}</td><td>{{ $report->UMUM_BPJS_P }}</td> {{-- P. UMUM --}}
                            <td>{{ $report->TB_UMUM_L }}</td><td>{{ $report->TB_UMUM_P }}</td><td>{{ $report->TB_BPJS_L }}</td><td>{{ $report->TB_BPJS_P }}</td>   {{-- POLI TB --}}
                            <td>{{ $report->IGD_UMUM_L }}</td><td>{{ $report->IGD_UMUM_P }}</td><td>{{ $report->IGD_BPJS_L }}</td><td>{{ $report->IGD_BPJS_P }}</td>   {{-- IGD --}}
                            <td>{{ $report->Inap_UMUM_L }}</td><td>{{ $report->Inap_UMUM_P }}</td><td>{{ $report->Inap_BPJS_L }}</td><td>{{ $report->Inap_BPJS_P }}</td>   {{-- R. INAP --}}
                            <td>{{ $report->KIA_UMUM_L }}</td><td>{{ $report->KIA_UMUM_P }}</td><td>{{ $report->KIA_BPJS_L }}</td><td>{{ $report->KIA_BPJS_P }}</td>   {{-- KIA --}}
                            <td>{{ $report->GIGI_UMUM_L }}</td><td>{{ $report->GIGI_UMUM_P }}</td><td>{{ $report->GIGI_BPJS_L }}</td><td>{{ $report->GIGI_BPJS_P }}</td>   {{-- GIGI --}}
                            <td>{{ $report->KB_UMUM }}</td><td>{{ $report->KB_BPJS }}</td>                       {{-- KB --}}
                            <td>{{ $report->VK_UMUM }}</td><td>{{ $report->VK_BPJS }}</td>                       {{-- VK --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $reportData->links() }}
            </div>
        </div>
    </div>
    <style>
        /* Mengatur ukuran tanda panah pada tombol pagination */
        svg {
            width: 1.25rem; /* Sesuaikan dengan ukuran kelas w-5 (1.25rem) */
            height: 1.25rem; /* Sesuaikan dengan ukuran kelas h-5 (1.25rem) */
        }
    </style>
    
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .card, .card * {
                visibility: visible;
            }
            .card {
                position: absolute;
                top: 0;
                left: 0;
            }
        }
        
    </style>
    
    <script>
        document.getElementById('print-table').addEventListener('click', function () {
        let printContents = document.getElementById('report-table').outerHTML;
        let originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
    });
    </script>
@stop
