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
                            <a class="nav-link" href="{{ route('home') }}">
                                {{ __('Profile') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('settings') }}">
                                {{ __('Settings') }}
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
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#grindSpots" role="button" aria-expanded="false" aria-controls="grindSpots">
                    {{ __('Grind') }}
                </a>
                <div class="collapse" id="grindSpots">
                    <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                            <a class="nav-link" href="{{ route('grind.summary') }}">
                                {{ __('Summary') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('grind.location', ['location' => 'jade-forest']) }}">
                                {{ __('Jade Forest') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('grind.location', ['location' => 'gyfin-under']) }}">
                                {{ __('Gyfin Rhasia Temple: Underground') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('grind.location', ['location' => 'd-cres-shrine']) }}">
                                {{ __('Dekia: Crescent Shrine') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>