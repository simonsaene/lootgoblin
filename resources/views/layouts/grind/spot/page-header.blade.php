<div class="container" id="featured-3">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    {{-- Page Header --}}
    <h1 class="border-bottom">{{ $grindSpot->name }}</h1>

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

    <div class="d-flex justify-content-center mb-4">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add{{ $grindSpot->id }}Modal">
            <i class="bi bi-plus-square-fill"></i> Session
        </button>
    </div>

    <div class="b-example-divider"></div>
    @include('layouts.grind.spot.display-tables')
    <div class="b-example-divider"></div>
    @include('layouts.grind.spot.display-posts')
    @include('layouts.grind.modals.add-session-modal')
</div>

