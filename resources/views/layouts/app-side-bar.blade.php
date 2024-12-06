{{-- Side Bar --}}
<nav id="sidebar" class="bg-dark sidebar" style="width: 200px; height: 100vh;">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item text-center border-bottom">
                <span class="fs-5 fw-semibold">User</span>
            </li>
            <ul class="nav flex-column ms-3">
                <li class="nav-item">
                    <a class="nav-link link-body-emphasis" href="{{ route('user.home') }}">
                        <i class="bi bi-person-circle"> </i>{{ __('Profile') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link link-body-emphasis" href="{{ route('user.settings') }}">
                        <i class="bi bi-gear"></i> {{ __('Settings') }}
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
            <li class="nav-item text-center border-bottom">
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
                            {{ __($grindSpot->name) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </ul>
    </div>
</nav>

