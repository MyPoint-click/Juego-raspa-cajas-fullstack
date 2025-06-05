<?php

namespace App\Http\Controllers\BoxGame;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\PrizeCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BoxController extends Controller
{
    public function __construct()
    {
        $this->resetExpiredCodes();
    }

    private function resetExpiredCodes()
    {
        PrizeCode::where('status', 'viewed')
            ->where('session_expires_at', '<', Carbon::now())
            ->update([
                'status' => 'unused',
                'viewed_at' => null,
                'session_id' => null,
                'session_expires_at' => null
            ]);
    }

    public function index()
    {
        return Inertia::render('box-game/App');
    }

    public function getCode()
    {
        $sessionId = session()->getId();

        // Verificar código existente
        $existingCode = PrizeCode::where('session_id', $sessionId)
            ->where('session_expires_at', '>', Carbon::now())
            ->first();

        if ($existingCode) {
            return response()->json([
                'code' => $existingCode->code
            ]);
        }

        // Obtener la campaña activa
        $currentCampaign = Campaign::where('is_current', true)
            ->where('is_active', true)
            ->first();

        if (!$currentCampaign) {
            return response()->json([
                'error' => 'No hay una campaña activa en este momento.'
            ], 404);
        }

        $validCode = PrizeCode::where('status', 'unused')
            ->where('campaign_id', $currentCampaign->id)
            ->whereNull('session_id')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', Carbon::now());
            })->first();

        if (!$validCode) {
            return response()->json([
                'error' => 'No hay códigos disponibles para la campaña actual.'
            ], 404);
        }

        // Reservar el código
        $validCode->update([
            'status' => 'viewed',
            'viewed_at' => Carbon::now(),
            'session_id' => $sessionId,
            'session_expires_at' => Carbon::now()->addMinutes(1)
        ]);

        return response()->json([
            'code' => $validCode->code
        ]);
    }
}
