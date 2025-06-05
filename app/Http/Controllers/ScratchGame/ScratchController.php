<?php

namespace App\Http\Controllers\ScratchGame;

use App\Http\Controllers\Controller;
use App\Mail\AdminPrizeCodeNotification;
use App\Models\Campaign;
use App\Models\PrizeCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class ScratchController extends Controller
{
    public function __construct()
    {
        // Resetear códigos expirados antes de cada acción
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

    //     El constructor se ejecuta cada vez que se crea una instancia del controlador
    //     resetExpiredCodes() busca códigos que:
    //      Estén marcados como 'viewed'
    //      Su tiempo de sesión haya expirado
    //     Los resetea a su estado inicial 'unused'

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

    // Obtiene el ID de sesión del usuario actual
    // Busca si el usuario ya tiene un código reservado y válido
    // Si existe, renderiza la página con ese código
    // Si no, renderiza la página sin código

    //Obtener un código de premio
    // Este método se encarga de obtener un código de premio que esté disponible y no haya expirado.
    public function getCode()
    {
        $sessionId = session()->getId();

        // 1. Primero verificar si ya tiene un código reservado
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

        // 2. Si no tiene código, buscar uno nuevo
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


        // Marcar el código como visto
        $validCode->update([
            'status' => 'viewed',
            'viewed_at' => Carbon::now(),
            'session_id' => $sessionId,
            'session_expires_at' => Carbon::now()->addMinutes(1) // Establecer la expiración de la sesión
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

    // Verifica código existente:
    // Busca si el usuario ya tiene un código reservado
    // Si existe y no ha expirado, lo devuelve

    // Busca nuevo código:
    // Estado 'unused'
    // Sin sesión asignada
    // No expirado (o sin fecha de expiración)

    // Maneja errores:
    // Si no hay códigos disponibles, retorna error 404

    // Reserva el código:
    // Marca como 'viewed'
    // Registra cuándo fue visto
    // Asigna la sesión del usuario
    // Establece tiempo de expiración (1 minuto)
}
