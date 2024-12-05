@extends('layouts.app')

@section('content')
    <div class="container" id="featured-3">
        <h1 class="border-bottom">Summary</h1>
        @if($grindSessions->isEmpty())
            <div class="row g-4 py-5 row-cols-1 row-cols-lg-3 justify-content-center">
                <p>You currently have no grind sessions</p>
            </div>
        @else
            <div class="row g-4 py-5 row-cols-1 row-cols-lg-3 justify-content-center">
                <div class="feature col">
                    <div class="feature-icon d-inline-flex fs-2 mb-3">
                        <i class="bi bi-alarm"></i>
                    </div>
                    <h3 class="fs-2 text-body-emphasis">Total Hours</h3>
                    <h5 class="card-title">{{ number_format($totalHours, 2) }}</h5>
                </div>
            
                <div class="feature col">
                    <div class="feature-icon d-inline-flex fs-2 mb-3">
                        <i class="bi bi-currency-exchange"></i>
                    </div>
                    <h3 class="fs-2 text-body-emphasis">Total Silver</h3>
                    <h5 class="card-title">{{ number_format($totalSilver) }}</h5>
                </div>
            
                <div class="feature col">
                    <div class="feature-icon d-inline-flex fs-2 mb-3">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <h3 class="fs-2 text-body-emphasis">Silver Per Hour</h3>
                    <h5 class="card-title">{{ number_format($totalSilverPerHour) }}</h5>
                </div>
            </div>

        <div class="container">
                <div class="row">
                    <div class="col-md-12 col-12 mb-12">
                        <div class="row g-4">
                            <!-- First Column for 'sum-grind-spots' -->
                            <div class="col-md-5 col-12">
                                @include('layouts.grind.sum-grind-spots')
                            </div>
                        
                            <!-- Second Column for 'sum-hours' -->
                            <div class="col-md-7 col-12">
                                @include('layouts.grind.sum-hours')
                            </div>
                        </div>
                        @include('layouts.grind.sum-silver-per-spot')
                        @include('layouts.grind.sum-silver-per-spot-avg')
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
