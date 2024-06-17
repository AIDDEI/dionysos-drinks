@extends('layouts.app')

@section('title', 'Dionysos Drinks - Recommendations')

@section('content')
    <div class="text-container">
        <a href="{{ route('recommendations.personalized') }}">
            <h1 class="underline">Personal Recommendations</h1>
        </a>
        <br>
        <p>
            Get personalized recommendations now! These recommendations are specially made for you! But you might be wondering
            how these recommendations are generated? They are based on your ratings of the beers: both on the main rating and on
            the ratings you gave for the individual flavors.
        </p>
        <br>
        <p>
            The generation of recommendations starts by determining which type of beer you like the most. Imagine you have given
            a good rating to a total of 8 beers, of which 4 are weizen, 2 are IPAs, and 2 are pilsners, then your favorite type of
            beer at the moment is weizen. To avoid giving recommendations of only weizen (or your favorite type), a maximum of 2
            beers per type is selected. Then, you get 2 beers of another type as recommendations and then 1 beer of your third
            favorite type.
        </p>
        <br>
        <p>
            For each type, we also look at which flavors you like the most in that beer. A top three flavors list is created from
            this. Based on this top three, the flavors of each beer in the respective type are compared. The beers with the most
            matching flavors are considered the best recommendations! If more than two beers have the same number of matches, the
            two with the highest overall rating are chosen!
        </p>
        <br>
        <p>
            This results in 5 new beers for you to try! So go to the recommendations now and discover what might become your
            new favorite beer!
            <a href="{{ route('recommendations.personalized') }}" class="underline">Get personalized recommendations!</a>
        </p>
        <br>
    </div>

    <hr>

    <div class="text-container">
        <a href="{{ route('recommendations.adventurous') }}">
            <h1 class="underline">Adventurous Recommendations</h1>
        </a>
        <br>
        <p>
            So, you're feeling a bit adventurous today! You're in the right place for adventurous recommendations! But what does
            this mean? If you really want to try something completely new, something outside your usual types of beer but still
            within your favorite flavors, then these recommendations are for you!
        </p>
        <br>
        <p>
            This time, the generation of your recommendations doesn't start with finding your best types of beer; instead, we go
            straight to your favorite flavors! Once again, we take your highest-rated beers and gather all the different flavors
            from these beers. We look at how you've rated each flavor. After doing this, we get your top three flavors.
        </p>
        <br>
        <p>
            Then, we consider all the beers in our database that you haven't tried yet! For each beer, we compare the flavors of
            that beer with your top three flavors. This results in a long list of all the drinks and how well they match your taste.
            The top five beers from this list are your recommendations! If there are several beers (more than the top 5) that match
            your top three flavors equally, we again select the beers with the highest overall ratings!
        </p>
        <br>
        <p>
            This way, you can get a variety of beers that are completely out of your comfort zone! But I'd say: give them a try!
            Who knows, your new favorite might be among them!
            <a href="{{ route('recommendations.adventurous') }}" class="underline">Get adventurous recommendations!</a>
        </p>
        <br>
    </div>
@endsection