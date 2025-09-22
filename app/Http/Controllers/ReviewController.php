<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Property;

class ReviewController extends Controller
{
    public function store(Request $request, Property $property)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already reviewed this property
        $existingReview = Review::where('tenant_id', auth()->id())
                                ->where('property_id', $property->id)
                                ->first();

        if ($existingReview) {
            return back()->withErrors(['error' => 'You have already reviewed this property.']);
        }

        $comment = $request->comment ?: 'No comment provided.';
        
        Review::create([
            'tenant_id' => auth()->id(),
            'property_id' => $property->id,
            'rating' => $request->rating,
            'comment' => $comment,
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }

    public function destroy(Review $review)
{
    if(auth()->id() !== $review->tenant_id) {
        return back()->withErrors(['error' => 'You are not authorized to delete this comment.']);
    }

    $review->delete();

    return back()->with('success', 'Comment deleted successfully!');
}
}
