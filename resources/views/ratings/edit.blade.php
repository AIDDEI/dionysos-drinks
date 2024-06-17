@extends('layouts.app')

@section('title', 'Edit Rating - ' . $beer->name)

@section('content')
<div class="details-container">
        <a href="{{ url()->previous() }}">&lt; Return</a>
        <div class="beer-data">
            <img src="{{ asset($beer->image ?? 'img/placeholder.png') }}" alt="{{ $beer->name }}">
            <br>
            <h1>Edit rating for: {{ $beer->name }} - {{ $beer->alcohol }}% ABV</h1>
            <h3>{{ $sort->name }} - {{ $brewery->name }}</h3>
            <br>
            <form action="{{ route('ratings.update', $rating->id) }}" method="POST">
                @csrf
                @method('PUT')

                <label for="rating" class="big-label">Main Rating (0-10):</label>
                <br>
                <input type="number" name="rating" id="rating" value="{{ number_format($rating->rating, 1) }}" min="0" max="10" step="0.1" placeholder="0-10" required>

                <br><br>

                <label for="review" class="big-label">Short Review:</label>
                <br>
                <textarea name="review" id="review" rows="1" cols="75" placeholder="Very tasty/Not tasty at all" required>{{ $rating->review }}</textarea>

                <br><br>

                <label for="flavor_ratings" class="big-label">Flavors:</label>
                <div class="flavor-ratings-container">
                    @foreach ($flavors as $flavor)
                        <div class="flavor-rating-item">
                            <label>{{ $flavor->name }}</label>
                            <select name="flavor_ratings[{{ $flavor->id }}]">
                                @if ($rating->flavorRatings)
                                    @php
                                        $flavorRating = $rating->flavorRatings->where('flavor_id', $flavor->id)->first();
                                    @endphp
                                    <option value="0" {{ $flavorRating && $flavorRating->rating == 0 ? 'selected' : '' }}>Nasty</option>
                                    <option value="1" {{ $flavorRating && $flavorRating->rating == 1 ? 'selected' : '' }}>Neutral</option>
                                    <option value="2" {{ $flavorRating && $flavorRating->rating == 2 ? 'selected' : '' }}>Tasty</option>
                                @else
                                    <option value="0">Nasty</option>
                                    <option value="1">Neutral</option>
                                    <option value="2">Tasty</option>
                                @endif
                            </select>
                        </div>
                    @endforeach
                    <div class="flavor-rating-item">
                        <button type="submit" class="btn btn-success rate-button">Update Rating!</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection