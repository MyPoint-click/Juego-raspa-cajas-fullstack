<?php

namespace App\Http\Controllers\LandingPageGame;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LandingPage extends Controller
{
    public function index()
    {
        return Inertia::render('LandingPageGame/LandingPage');
    }
}
