@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- Display Session Status or Error Messages --}}
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
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
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="mb-3">Tables</h2>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn me-2" onclick="fetchData('itemsTable')">
                        <i class="bi bi-chevron-expand"></i> Items
                    </button>
                    <button type="button" class="btn me-2" onclick="fetchData('grindSpotItemsTable')">
                        <i class="bi bi-chevron-expand"></i> Grind Spot Items
                    </button>
                    <button type="button" class="btn" onclick="fetchData('grindSpotsTable')">
                        <i class="bi bi-chevron-expand"></i> Grind Spots
                    </button>
                </div>
            </div>
        </div>
    
        {{-- Tables --}}
        <div class="row py-5 bg-body-tertiary justify-content-center">
            <div class="col-md-12">
                <div id="tablesContainer">

                    {{-- Displays tables --}}
                    @include('layouts.admin.tables.items')
                    @include('layouts.admin.tables.grind-spot-items')
                    @include('layouts.admin.tables.grind-spots')
    
                </div>
            </div>
        </div>

        {{-- Modals for adding items, items to grind spots and grind spots --}}
        @include('layouts.admin.modals.items.add-item-modal')
        @include('layouts.admin.modals.grind-spot-items.add-grind-spot-item-modal', ['items' => $items, 'grindSpots' => $grindSpots])
        @include('layouts.admin.modals.grind-spots.add-grind-spot-modal')

    </div>

    {{-- AJAX for showing tables --}}
    @include('layouts.admin.display-tables')

    {{-- Creates modals for all items, needs to be done after javascript has filled the tables with the items --}}
    @foreach ($items as $item)
        @include('layouts.admin.modals.items.edit-item-modal')
    @endforeach

    {{-- Creates modals for all grind spots, needs to be done after javascript has filled the tables with the grind spots --}}
    @foreach ($grindSpots as $spot)
        @include('layouts.admin.modals.grind-spots.edit-grind-spot-modal')
    @endforeach

@endsection