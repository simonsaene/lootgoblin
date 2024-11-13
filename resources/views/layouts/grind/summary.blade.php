@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Summary</h2>
            </div>
        </div>

        <div class="col-md-4 col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    Total Hours
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($totalHours, 2) }}</h5>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    Total Silver
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($totalSilver) }}</h5>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    Silver Per Hour
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($totalSilverPerHour) }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-12 mb-12">
            @include('layouts.grind.sum-hours')
            @include('layouts.grind.sum-silver-per-spot')
            @include('layouts.grind.sum-silver-per-spot-avg')
            @include('layouts.grind.sum-grind-spots')
        </div>
    </div>
</div>


@endsection
