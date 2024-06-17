@extends('layouts.app')

@section('title', 'Dionysos Drinks - Adventurous Recommendations')

@section('content')
    <div class="text-container">
        <a href="{{ url('/recommendations') }}">&lt; Return</a>
        <br><br>
        <h1>Adventurous Recommendations</h1>
        <br><br>
    </div>
    <div class="container">
        <div class="beer-grid">
            @if (!empty($adventurousRecommendations))
                @foreach ($adventurousRecommendations as $recommendation)
                    <div class="beer-item">
                        <h3>
                            <a href="{{ route('beers.show', $recommendation['drink']->id) }}">
                                {{ $recommendation['drink']->name }} - {{ $recommendation['drink']->alcohol }}% ABV
                            </a>
                        </h3>
                        <a href="{{ route('beers.show', $recommendation['drink']->id) }}">
                            <img src="{{ asset($recommendation['drink']->image) }}" alt="{{ $recommendation['drink']->name }}">
                        </a>
                        <h4>{{ $recommendation['drink']->sorts->first()->name }} - {{ $recommendation['drink']->brewery->name }}</h4>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection