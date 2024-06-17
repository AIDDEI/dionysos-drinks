@extends('layouts.app')

@section('title', 'Dionysos Drinks - Welcome')

@section('content')
    <div class="text-container">
        @if (Auth::check())
            <h1>Welcome back, {{ Auth::user()->name }}!</h1>
            <br>
            <p>Nice to see you again!</p>
            <p>What do you want to do today?</p>
            <br>
            <p>I want to:</p>
            <a href="{{ url('/beers') }}" class="underline">- find beers and rate them</a>
            <br>
            <a href="{{ url('/recommendations') }}" class="underline">- want to get recommendations for new beers</a>
            <br><br>
            <h4>Cheers!</h4>
            <br>
            <img src="img/prost.jpg" alt="Two people toast a beer">
        @else
            <h1>Welcome to Dionysos Drinks!</h1>
            <br>
            <p>
                At Dionysos Drinks, you can do all sorts of things with alcohol! Search for alcoholic beverages, rate them, and
                receive personalized recommendations based on your taste profile!
            </p>
            <br>
            <p>
                Without an account, you can discover various drinks and learn information about each drink, such as alcohol 
                percentages, the brewery that crafted it, and the flavors it offers! 
                <a href="{{ url('/beers') }}" class="underline">Take a look around!</a>
            </p>
            <br>
            <p>
                With an account, you can do even more! For example, give drinks a detailed rating. Or receive personalized 
                recommendations for new drinks, which might lead you to discover your new favorite! 
                <a href="{{ route('register') }}" class="underline">Sign up now!</a> 
                Already have an account?
                <a href="{{ route('login') }}" class="underline">Log in quickly!</a>
            </p>
            <br>
            <p>
                Cheers!
            </p>
            <br>
            <img src="img/logo.png" alt="Logo of Dionysos Drinks">
        @endif
    </div>
@endsection