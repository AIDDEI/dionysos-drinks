@extends('layouts.app')

@section('title', 'Dionysos Drinks - ' . $beer->name)

@section('content')
    <div class="details-container">
        @if (strpos(url()->previous(), '/ratings') !== false)
            <a href="{{ url('/beers') }}">&lt; Return</a>
        @else
            <a href="{{ url()->previous() }}">&lt; Return</a>
        @endif
        <div class="beer-data">
            <img src="{{ asset($beer->image ?? 'img/placeholder.png') }}" alt="{{ $beer->name }}">
            <br>
            @if (Auth::check())
                @if ($userRating)
                    <div class="rating-circle">
                        {{ number_format($userRating->rating, 1) }}
                    </div>
                @endif
            @endif
            <h1>{{ $beer->name }}</h1>
            <h4>{{ $brewery->name }}</h4>
            <p>{{ $sort->name }}</p>
            <p>{{ $beer->alcohol }}% ABV</p>
            @if (Auth::check())
                @if ($userRating)
                    <a href="{{ route('ratings.edit', $userRating->id) }}" class="btn btn-primary rate-button">Edit Rating!</a>
                @else
                    <a href="{{ route('ratings.rate', $beer->id) }}" class="btn btn-success rate-button">Rate Now!</a>
                @endif
            @endif
        </div>
        <p>{{ $beer->description }}</p>
        <p class="flavors">
            @php
                $sortedFlavors = $flavors->sortBy('name');
            @endphp
            @foreach ($sortedFlavors as $flavor)
                {{ $flavor->name }}{{ !$loop->last ? ' | ' : '' }}
            @endforeach
        </p>
    </div>
@endsection