@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        {{-- First row (mb-4 adds margin bottom) --}}
        <div class="row mb-4">

            {{-- First card --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('General') }}</div>
                    <div class="card-body">
                        {{ __('EEmpty') }}
                    </div>
                </div>
            </div>

            {{-- Second card --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('Window') }}</div>
                    <div class="card-body">
                        {{ __('Empty') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Second row --}}
        <div class="row">

            {{-- Third card --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('Verification') }}</div>
                    <div class="card-body">
                        {{ __('Verification Options') }}
                </div>
                </div>
            </div>

            {{-- Fourth card --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('Test') }}</div>
                    <div class="card-body">
                       
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin: 20px;">

        {{--  Buttons for showing tables --}}
        <div class="row mb-4 justify-content-center">
            <div class="col-md-12 text-center">
                <h2 class="mb-3">Tables</h2>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-primary me-2" onclick="fetchData('itemsTable')">
                        Show Items
                    </button>
                    <button type="button" class="btn btn-primary me-2" onclick="fetchData('grindSpotItemsTable')">
                        Show Grind Spot Items
                    </button>
                    <button type="button" class="btn btn-primary" onclick="fetchData('grindSpotsTable')">
                        Show Grind Spots
                    </button>
                </div>
            </div>
        </div>
    
        {{-- Tables --}}
        <div class="row">
            <div class="col-md-12">
                <div id="tablesContainer">

                    {{-- Displays tables --}}
                    @include('layouts.tables.items')
                    @include('layouts.tables.grind-spot-items')
                    @include('layouts.tables.grind-spots')
    
                </div>
            </div>
        </div>

        {{-- Modals for adding items, items to grind spots and grind spots --}}
        @include('layouts.modals.items.add-item-modal')
        @include('layouts.modals.grind-spot-items.add-grind-spot-item-modal', ['items' => $items, 'grindSpots' => $grindSpots])
        @include('layouts.modals.grind-spots.add-grind-spot-modal')

    </div>

    {{-- AJAX for showing tables --}}
    @include('layouts.display-tables')

    {{-- Creates modals for all items, needs to be done after javascript has filled the tables with the items --}}
    @foreach ($items as $item)
        @include('layouts.modals.items.edit-item-modal')
    @endforeach

    {{-- Creates modals for all grind spots, needs to be done after javascript has filled the tables with the grind spots --}}
    @foreach ($grindSpots as $spot)
        @include('layouts.modals.grind-spots.edit-grind-spot-modal')
    @endforeach

@endsection