@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center mb-6">
            <div class="col-12 text-center">
                <h1>Looking for someone?</h1>
                <form action="{{ route('user.search.player') }}" method="GET" class="mb-4">
                    @csrf
                    <div class="form-floating col-md-8 mx-auto mb-3">
                        <input type="text" name="family_name" id="form-float" class="form-control" value="{{ request()->get('family_name') }}" placeholder="Enter Family Name">
                        <label for="form-float">Enter Family Name</label>
                    </div>
                    <div class="col-md-4 mx-auto">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(isset($users) && $users->isNotEmpty())
        <div class="container py-5 bg-body-tertiary">
            <div class="row">
                @foreach($users as $user)
                    <div class="col-lg-4 col-md-6 col-sm-12 text-center">
                        @if ($user->profile_image)
                            <img src="{{ asset('storage/' . $user->profile_image) }}" class="img-fluid rounded-circle" alt="Profile Image">
                        @else
                            <svg class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                                <title>Placeholder</title><rect width="100%" height="100%" fill="var(--bs-secondary-color)"></rect>
                            </svg>
                        @endif
                        <h2 class="fw-normal">{{ $user->family_name }}</h2>
                        <p>
                            <a class="btn btn-outline-primary" href="{{ route('user.player.profile', $user->id) }}">
                                <i class="bi bi-arrow-bar-right"></i> Profile
                            </a>
                            <a class="btn btn-outline-primary" href="{{ route('grind.player', $user->id) }}">
                                <i class="bi bi-arrow-bar-right"></i> Sessions
                            </a>
                        </p>
                    </div><!-- /.col-lg-4 -->
                @endforeach
            </div><!-- /.row -->
    @elseif(request()->get('family_name'))
            <p>No users found with the family name "{{ request()->get('family_name') }}".</p>
        </div> <!-- /.container -->
    @endif



@endsection