{{-- Side Bar --}}
<nav id="sidebar" class="bg-light sidebar" style="width: 200px;">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#userSection" role="button" aria-expanded="false" aria-controls="userSection">
                    User
                </a>
                <div class="collapse" id="userSection">
                    <ul class="nav flex-column ms-3">

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.home') }}">
                                {{ __('Profile') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.settings') }}">
                                {{ __('Settings') }}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.search') }}">
                                {{ __('Search') }}
                            </a>
                        </li>

                        @if(auth()->user() && auth()->user()->is_admin)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.home') }}">
                                    {{ __('Admin Dashboard') }}
                                </a>
                            </li>
                        @endif

                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#grindSection" role="button" aria-expanded="false" aria-controls="grindSection">
                    {{ __('Grind') }}
                </a>
                <div class="collapse" id="grindSection">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                                <a class="nav-link" href="{{ route('show.summary') }}">
                                    {{ __('Summary') }}
                                </a>
                        </li>
                        @foreach ($grindSpots as $grindSpot)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('grind.location', ['id' => $grindSpot->id]) }}">
                                    {{ __($grindSpot->name) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>