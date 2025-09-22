@extends('layouts.app')

@section('content')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/create_room.css') }}">
@endpush

<div class="container">
    <div class="container-rooms-create">
        <h4>Create Room</h4>
        <form action="{{ route('landlord.rooms.store', $property->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-2">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-2">
                <label>Price Monthly</label>
                <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price') }}" required>
            </div>

            <div class="mb-2">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="Available" {{ old('status') === 'Available' ? 'selected' : '' }}>Available</option>
                    <option value="Not Available" {{ old('status') === 'Not Available' ? 'selected' : '' }}>Not Available</option>
                </select>
            </div>

            @php
                $maxImages = 5;
                $currentCount = isset($room) ? $room->images->count() : 0;
                $remaining = $maxImages - $currentCount;
            @endphp

            @if ($remaining > 0)
                <div class="mb-3">
                    <label for="images" class="form-label">Upload Room Images ({{ $remaining }} remaining)</label>
                    <input type="file" name="images[]" class="form-control" multiple {{ $remaining === 0 ? 'disabled' : '' }} accept="image/*">
                </div>
            @else
                <div class="alert alert-info">
                    Maximum of {{ $maxImages }} images reached. Delete existing ones to upload more.
                </div>
            @endif

            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div> 
</div>
    
@endsection
