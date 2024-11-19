@extends('layouts.app')

@section('content')
<div class="container">

    <h2>Search for Users by Family Name</h2>

    <form action="{{ route('user.search') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-8">
                <input type="text" name="family_name" class="form-control" value="{{ request()->get('family_name') }}" placeholder="Enter Family Name">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    @if($users->isNotEmpty())
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