<?php

namespace App\Http\Controllers\WheelGame;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\PrizeCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail; // Importa la fachada Mail
use App\Mail\AdminPrizeCodeNotification; // Importa tu Mailable

class WheelController extends Controller
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
        $sessionId = session()->getId();
        $existingCode = PrizeCode::where('session_id', $sessionId)
            ->where('session_expires_at', '>', Carbon::now())
            ->first();

        if ($existingCode) {
            return Inertia::render('wheel-game/App', [
                'reservedCode' => $existingCode->code
            ]);
        }
        return Inertia::render('wheel-game/App');
    }

    public function getCode()
    {
        $sessionId = session()->getId();

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

        $validCode->update([
            'status' => 'viewed',
            'viewed_at' => Carbon::now(),
            'session_id' => $sessionId,
            'session_expires_at' => Carbon::now()->addMinutes(1)
        ]);

        // --- AQUI SE ENVIA EL EMAIL EN COLA ---
        // $adminEmail = config('app.admin_email'); // Obtiene el correo del admin desde la configuración
        $adminEmail = 'faviogarciaguzmam@gmail.com';

        if ($adminEmail) { // Asegúrate de que el correo del admin esté configurado
            Mail::to($adminEmail)->queue(new AdminPrizeCodeNotification($validCode->code, $sessionId));
            // 'queue()' envía el Mailable a la cola.
            // Si usaras 'send()', se enviaría en primer plano.
        } else {
            return response()->json([
                'error' => 'No se pudo enviar la notificación al administrador. Por favor, contacta con el soporte.'
            ], 500);
        }

        return response()->json([
            'code' => $validCode->code
        ]);
    }
}
