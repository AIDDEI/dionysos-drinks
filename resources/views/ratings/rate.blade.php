@extends('layouts.app')

@section('title', 'Dionysos Drinks - Rate ' . $beer->name)

@section('content')
    <div class="details-container">
        <a href="{{ url()->previous() }}">&lt; Return</a>
        <div class="beer-data">
            <img src="{{ asset($beer->image ?? 'img/placeholder.png') }}" alt="{{ $beer->name }}">
            <br>
            <h1>Rate: {{ $beer->name }} - {{ $beer->alcohol }}% ABV</h1>
            <h3>{{ $sort->name }} - {{ $brewery->name }}</h3>
            <br>
            <form action="{{ route('ratings.store', $beer->id) }}" method="POST">
                @csrf

                <label for="rating" class="big-label">Main Rating (0-10):</label>
                <br>
                <input type="number" name="rating" id="rating" min="0" max="10" step="0.1" placeholder="0-10" required>

                <br><br>

                <label for="review" class="big-label">Short Review:</label>
                <br>
                <textarea name="review" id="review" rows="1" cols="75" placeholder="Very tasty/Not tasty at all" required></textarea>

                <br><br>

                <label for="flavor_ratings" class="big-label">Flavors:</label>
                <div class="flavor-ratings-container">
                    @foreach ($flavors as $flavor)
                        <div class="flavor-rating-item">
                            <label>{{ $flavor->name }}</label>
                            <select name="flavor_ratings[{{ $flavor->id }}]">
                                <option value="0">Nasty</option>
                                <option value="1">Neutral</option>
                                <option value="2">Tasty</option>
                            </select>
                        </div>
                    @endforeach
                    <div class="flavor-rating-item">
                        <button type="submit" class="btn btn-success rate-button">Save Rating!</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection