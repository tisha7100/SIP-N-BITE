@extends('layouts.app')

@section('content')

<h1>WORKING ✅</h1>
<p>{{ $food->name }}</p>

<div class="container py-5">

    <h2 class="fw-bold">{{ $food->name }}</h2>
    <p class="text-muted">{{ $food->description }}</p>
    <h4 class="mb-4">₹{{ $food->price }}</h4>

    <form action="{{ route('cart.add') }}" method="POST">
        @csrf
        <input type="hidden" name="food_id" value="{{ $food->id }}">

        {{-- CUSTOMIZATION --}}
        @if($food->is_customizable && $food->category && $food->category->addonGroups)

            @foreach($food->category->addonGroups as $group)
                <div class="mb-3">
                    <h5>{{ $group->name }}</h5>

                    @foreach($group->addons as $addon)
                        <div class="form-check">
                            <input type="checkbox"
                                   name="addons[]"
                                   value="{{ $addon->id }}"
                                   class="form-check-input">

                            <label class="form-check-label">
                                {{ $addon->name }} (+₹{{ $addon->price }})
                            </label>
                        </div>
                    @endforeach

                </div>
            @endforeach

        @endif

        <button type="submit" class="btn btn-primary mt-3">
            Add to Cart
        </button>

    </form>

</div>

@endsection