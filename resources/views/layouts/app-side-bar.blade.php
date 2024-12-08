{{-- Side Bar --}}
<nav id="sidebar" class="bg-dark sidebar" style="width: 250px; height: 100vh; position: sticky; top: 0; overflow-y: auto;">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item text-center border-bottom bg-secondary mt-3">
                <span class="fs-5 fw-semibold">User</span>
            </li>
            <ul class="nav flex-column ms-3">
                <li class="nav-item">
                    <a class="nav-link link-body-emphasis" href="{{ route('user.home') }}">
                        <i class="bi bi-person-circle"> </i>{{ __('Profile') }}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link link-body-emphasis" href="{{ route('user.search.page') }}">
                        <i class="bi bi-search"></i> {{ __('Search') }}
                    </a>
                </li>

                @if(auth()->user() && auth()->user()->is_admin)
                    <li class="nav-item">
                        <a class="nav-link link-body-emphasis" href="{{ route('admin.home') }}">
                            <i class="bi bi-speedometer"></i> {{ __('Admin Dashboard') }}
                        </a>
                    </li>
                @endif
            </ul>

            <!-- Grind Section -->
            <li class="nav-item text-center border-bottom bg-secondary mt-3">
                <span class="fs-5 fw-semibold">Grind</span>
            </li>
            <ul class="nav flex-column ms-3">
                <li class="nav-item">
                    <a class="nav-link link-body-emphasis" href="{{ route('show.summary') }}">
                        <i class="bi bi-graph-up"></i> {{ __('Summary') }}
                    </a>
                </li>
                @foreach ($grindSpots as $grindSpot)
                    <li class="nav-item">
                        <a class="nav-link link-body-emphasis" href="{{ route('grind.location', ['id' => $grindSpot->id]) }}">
                            <div class="row align-items-center">
                                @foreach ($grindSpot->grindSpotItems as $grindSpotItem)
                                    @if ($grindSpotItem->item && $grindSpotItem->item->is_trash)
                                        <div class="col-auto">
                                            <img src="{{ asset('storage/' . $grindSpotItem->item->image) }}" alt="Item Image" style="width: 30px; height: 30px;">
                                        </div>
                                        <div class="col">
                                            <span>{{ __($grindSpot->name) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </ul>
    </div>
</nav>

