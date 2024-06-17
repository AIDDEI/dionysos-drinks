@extends('layouts.app')

@section('title', 'Dionysos Drinks - Beers')

@section('content')
    <div class="container">
        <div class="beer-grid">
            @foreach ($beers as $beer)
                <div class="beer-item">
                    <h3><a href="{{ route('beers.show', $beer->id) }}">{{ $beer->name }} - {{ $beer->alcohol }}% ABV</a></h3>
                    <a href="{{ route('beers.show', $beer->id) }}">
                        <img src="{{ $beer->image }}" alt="{{ $beer->name }}">
                    </a>
                    <h4>{{ $beer->sort->name }} - {{ $beer->brewery->name }}</h4>
                </div>
            @endforeach
        </div>
    </div>
@endsection