@extends('layouts.app')

@section('page-title', 'Alliance Health Index ' . $alliance->alliance_name )

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h2 class="page-header">
                Alliance Health Index for {{ $alliance->alliance_name }}
            <small> - 6 months of health index </small>
            <div class="pull-right">
                <ol class="breadcrumb">
                    <li><a href="{{ route('dashboard') }}">@lang('app.home')</a></li>
                    <li><a href="{{ route('alliance_health.index') }}">Alliance Health Index</a></li>
                    <li class="active">{{ $alliance->alliance_name }}</li>
                </ol>
            </div>
        </h2>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default mineral-chart">
            <div class="panel-heading">{{ $alliance->alliance_name }} Health Index, Be gentle.</div>
            <div class="panel-body chart">
                <div>
                    <canvas id="myChart" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('styles')
<style>
    .mineral-chart .chart {
        zoom: 1.235;
    }
</style>
@stop

@section('scripts')
<script>
    var labels = {!! json_encode(array_keys($health)) !!};
    var health = {!! json_encode(array_values($health)) !!};
    var ihub_count = {!! json_encode(array_values($ihub_count)) !!};
    var average_adm = {!! json_encode(array_values($average_adm)) !!};
</script>
{!! HTML::script('assets/js/chart.min.js') !!}
{!! HTML::script('assets/js/as/alliance_health.history.js') !!}

@stop