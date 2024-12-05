@extends('layouts.app')

@section('content')
    <div class="container">

        <h2>Looking for someone?</h2>

        <form action="{{ route('user.search.player') }}" method="GET" class="mb-4">
            @csrf
                <div class="form-floating col-md-8 mb-3">
                    <input type="text" name="family_name" id="form-float" class="form-control" value="{{ request()->get('family_name') }}" placeholder="Enter Family Name">
                    <label for="form-float">Enter Family Name</label>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
        </form>

        @if(isset($users) && $users->isNotEmpty())
            <h4>Search Results:</h4>
            <div class="row">
                @foreach($users as $user)
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">{{ $user->family_name }}</div>
                            <div class="card-body">
                                <a href="{{ route('user.player.profile', $user->id) }}" class="btn btn-primary">View Profile</a>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('grind.player', $user->id) }}" class="btn btn-primary">View Grind Sessions</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif(request()->get('family_name'))
            <p>No users found with the family name "{{ request()->get('family_name') }}".</p>
        @endif

    </div>
@endsection