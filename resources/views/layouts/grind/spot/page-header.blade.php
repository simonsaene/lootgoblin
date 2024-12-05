<div class="container">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>{{ $grindSpot->name }}</h2>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add{{ $grindSpot->id }}Modal">
                    <i class="bi bi-plus-square-fill"></i> Session
                </button>
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
                    Average Silver/hour
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($totalSilverPerHour) }}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="b-example-divider"></div>
    @include('layouts.grind.spot.display-tables')
    <div class="b-example-divider"></div>
    @include('layouts.grind.spot.display-posts')
    @include('layouts.grind.modals.add-session-modal')


</div>

