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
                <h2>{{ $location }}</h2>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add{{ $modal }}Modal">
                    Add Session
                </button>
            </div>
        </div>
    </div>
    @include('layouts.grind.spot.display-tables')

    @include('layouts.grind.modals.add-session-modal')

</div>