@extends('layouts.app')

@section('title', 'Dionysos Drinks - No Recommendations Found')

@section('content')
    <div class="text-container">
        <h1>No Recommendations Found :(</h1>
        <br>
        <p>Unfortunately no recommendations were found...</p>
        <br>
        <p>This could be due to three things:</p>
        <p>
            1. You haven't rated any drinks yet, so no recommendations are possible. -> But don't worry,
            <a href="{{ url('/beers') }}" class="underline">get started here!</a>
        </p>
        <p>
            2. You haven't given any drink a higher rating than a maximum of 5.9... You'll probably find something tasty right?
            <a href="{{ url('/beers') }}" class="underline">Get started here!</a>
        </p>
        <p>
            3. You found a bug in our system... In this case: we're very sorry :(
            But don't hesitate to contact us, then we will quickly pursue the problem!
            <a href="mailto: 1025560@hr.nl" class="underline">Mail us!</a>
        </p>
        <br><br>
        <a href="{{ url('/') }}" class="underline">Go back to the home page</a>
    </div>
@endsection