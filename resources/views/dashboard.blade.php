@extends('layouts.app')
@push('style')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/select2/select2.min.css')}}">
@endpush
@section('content')

<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-md-6 layout-spacing">
            <div class="widget ">
                <div class="widget-heading">
                    <h5 class="">Performance {{ date('F Y') }}</h5>
                </div>
                <div id="container1" style="margin-top: 20px"></div>
            </div>
        </div>

    </div>

</div>

@endsection
@push('scripts')
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
    $('#location_id').select2();
    $('#pegawai_id').select2({
        placeholder: 'Pilih Lokasi Dahulu!'
    });
</script>
<script>
   
let dataPerformance = {!! json_encode($chart_performance) !!};

Highcharts.chart('container1', {
    chart: {
        type: 'pie'
    },
    title: {
        text: ''
    },
    tooltip: {
        valueSuffix: '%'
    },
    subtitle: {
        text:
        ''
    },
    plotOptions: {
        pie: {
        colors: [
            '#50B432', 
            '#DDDF00', 
            '#ED561B', 
            '#24CBE5', 
            '#64E572', 
            '#FF9655', 
            '#FFF263', 
            '#6AF9C4'
        ],
        allowPointSelect: true,
        cursor: 'pointer',
        dataLabels: {
            enabled: false
        },
        showInLegend: true
        },
        series: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: [{
                enabled: true,
                distance: 20
            }, {
                enabled: true,
                distance: -40,
                format: '{point.percentage:.1f}%',
                style: {
                    fontSize: '1.2em',
                    textOutline: 'none',
                    opacity: 0.7
                },
                filter: {
                    operator: '>',
                    property: 'percentage',
                    value: 10
                }
            }]
        }
    },
    series: [
        {
            name: 'Percentage',
            colorByPoint: true,
            data: dataPerformance
        }
    ]
});

        
    </script>

@endpush