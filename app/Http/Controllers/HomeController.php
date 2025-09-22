<?php

namespace App\Http\Controllers;
use App\Models\Property;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'is_approved']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
    $properties = Property::with('images', 'user')->latest()->take(6)->get(); // limit to 6 for homepage
    return view('home', compact('properties'));
    }
    
}
