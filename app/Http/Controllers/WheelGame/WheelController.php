<?php

namespace App\Http\Controllers\WheelGame;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WheelController extends Controller
{
    public function index()
    {
        return Inertia::render('wheel-game/App');
    }
}
