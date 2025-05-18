<?php

namespace App\Http\Controllers\ScratchGame;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScratchController extends Controller
{
    public function index()
    {
        return Inertia::render('scratch-game/App');
    }
}
