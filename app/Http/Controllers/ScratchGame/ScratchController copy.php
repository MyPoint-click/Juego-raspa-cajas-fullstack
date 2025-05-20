<?php

namespace App\Http\Controllers\ScratchGame;

use App\Http\Controllers\Controller;
use App\Models\PrizeCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScratchController extends Controller
{
    public function index()
    {
        $sessionId = session()->getId();

        // Buscar si el usuario ya tiene un código reservado
        $existingCode = PrizeCode::where('session_id', $sessionId)
            ->where('session_expires_at', '>', Carbon::now())
            ->first();

        if ($existingCode) {
            return Inertia::render('scratch-game/App', [
                'reservedCode' => $existingCode->code
            ]);
        }
        return Inertia::render('scratch-game/App');
    }

    //Obtener un código de premio
    // Este método se encarga de obtener un código de premio que esté disponible y no haya expirado.
    public function getCode()
    {
        // Buscar un código válido (no usado y no expirado)
        $validCode = PrizeCode::where('status', 'unused')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now());
            })->first();

        if (!$validCode) {
            return response()->json([
                'error' => 'No hay códigos disponibles. Por favor, contacta con el administrador.'
            ], 404);
        }


        // Marcar el código como visto
        $validCode->update([
            'status' => 'viewed',
            'viewed_at' => Carbon::now()
        ]);

        return response()->json([
            'code' => $validCode->code
        ]);
    }
}
