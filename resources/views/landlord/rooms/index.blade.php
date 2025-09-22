@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Rooms for {{ $property->title }}</h4>
    <a href="{{ route('landlord.rooms.create', $property->id) }}" class="btn btn-primary mb-3">+ Add Room</a>

    @foreach($rooms as $room)
    <div class="card mb-2 p-3">
        <h5>{{ $room->name }}</h5>
        <p>Price: ₱{{ number_format($room->price, 2) }} | Status: 
            <span class="{{ $room->status === 'Available' ? 'text-success' : 'text-danger' }}">
                {{ $room->status }}
            </span>
        </p>

        @if ($room->images->isNotEmpty())
            <div class="d-flex flex-wrap gap-2">
                @foreach ($room->images as $image)
                    <img src="{{ asset('storage/' . $image->image_path) }}"
                        style="width: 120px; height: 90px; object-fit: cover; border-radius: 6px;">
                @endforeach
            </div>
        @endif
        
        <div class="card-footer d-flex justify-content-start">
            <a href="{{ route('landlord.rooms.edit', $room->id) }}" class="btn btn-warning btn-sm m-1">Edit</a>
            <form action="{{ route('landlord.rooms.destroy', $room->id) }}" method="POST" class="d-inline m-1">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm">Delete</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection
