<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\PrizeCode;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PrizeCodeController extends Controller
{
    public function index(Request $request)
    {
        $codes = PrizeCode::query()
            ->with('campaign')
            ->whereHas('campaign', function ($query) {
                $query->where('is_active', true);
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('expires_at', 'like', "%{$search}%")
                        ->orWhereHas('campaign', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->through(function ($code) {
                return [
                    'id' => $code->id,
                    'code' => $code->code,
                    'status' => $code->status,
                    'campaign' => $code->campaign ? [
                        'id' => $code->campaign->id,
                        'name' => $code->campaign->name
                    ] : null,
                    'session_expires_at' => $code->session_expires_at ? $code->session_expires_at->format('d/m/Y-H:i') : null,
                    'expires_at' => $code->expires_at ? $code->expires_at->format('d/m/Y') : null,
                    'created_at' => $code->created_at->format('d/m/Y'),
                ];
            })
            ->withQueryString();

        $campaigns = Campaign::select(['id', 'name', 'description', 'is_current'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/PrizeCodes', [
            'codes' => $codes,
            'filters' => $request->only(['search']),
            'campaigns' => $campaigns,
            'message' => session('message'),
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'quantity' => 'required|integer|min:1|max:100',
            'expires_at' => 'nullable|date|after:today'
        ]);
        try {
            $codes = collect()->pad($request->quantity, null)->map(function () use ($request) {
                return PrizeCode::create([
                    'campaign_id' => $request->campaign_id,
                    'code' => strtoupper(Str::random(8)),
                    'status' => 'unused',
                    'expires_at' => $request->expires_at
                ]);
            });

            session()->flash('message', [
                'type' => 'success',
                'text' => "{$codes->count()} códigos generados exitosamente"
            ]);
        } catch (\Exception $e) {
            session()->flash('message', [
                'type' => 'error',
                'text' => 'Error al generar los códigos: ' . $e->getMessage()
            ]);
        }

        return back();
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $code = PrizeCode::where('code', $request->code)->first();

        if (!$code) {
            session()->flash('message', [
                'type' => 'error',
                'text' => 'Código no encontrado'
            ]);
            return back();
        }

        if ($code->status === 'unused') {
            session()->flash('message', [
                'type' => 'error',
                'text' => 'Este código aún no ha sido visualizado'
            ]);
            return back();
        }

        if ($code->status === 'used') {
            session()->flash('message', [
                'type' => 'error',
                'text' => 'Este código ya ha sido utilizado'
            ]);
            return back();
        }

        // Solo verificar expiración si no está permitido verificar expirados
        if (!$request->allow_expired && $code->status === 'viewed' && $code->expires_at && $code->expires_at < now()) {
            session()->flash('message', [
                'type' => 'error',
                'text' => 'Este código ha expirado y no puede ser verificado'
            ]);
            return back();
        }

        // Si el código está en estado 'viewed', actualizarlo a 'used'
        try {
            $code->update([
                'status' => 'used',
                'used_at' => now()
            ]);

            session()->flash('message', [
                'type' => 'success',
                'text' => 'Código verificado y marcado como usado exitosamente'
            ]);
        } catch (\Exception $e) {
            session()->flash('message', [
                'type' => 'error',
                'text' => 'Error al verificar el código'
            ]);
        }

        return back();
    }

    public function destroy(PrizeCode $prizeCode)
    {
        try {
            $prizeCode->delete();
            session()->flash('message', [
                'type' => 'success',
                'text' => "Código {$prizeCode->code} eliminado exitosamente"
            ]);
        } catch (\Exception $e) {
            session()->flash('message', [
                'type' => 'error',
                'text' => 'Error al eliminar el código'
            ]);
        }
        return back();
    }

    // Método para eliminar códigos expirados
    public function bulkDelete(Request $request)
    {

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:all,unused,viewed,used',
            'date_before' => 'nullable|date',
            'campaign_id' => 'nullable|exists:campaigns,id',
        ]);
        try {
            $query = PrizeCode::query();

            // Filtrar por estado si no es 'all'
            if ($request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // Filtrar por fecha si se especificó
            if ($request->date_before) {
                $query->whereDate('expires_at', $request->date_before);
            }

            // Filtrar por campaña si se especificó
            if ($request->campaign_id) {
                $query->where('campaign_id', $request->campaign_id);
            }


            // Obtener la cantidad de registros que se eliminarán
            $codesCount = $query->count();

            if ($codesCount === 0) {
                session()->flash('message', [
                    'type' => 'error',
                    'text' => 'No se encontraron códigos que cumplan con los criterios especificados'
                ]);
                return back();
            }

            // Limitar la cantidad a eliminar según lo especificado
            $actuallyDeleted = $query->take($request->quantity)->delete();
            session()->flash('message', [
                'type' => 'success',
                'text' => "Se eliminaron {$actuallyDeleted} códigos exitosamente"
            ]);
        } catch (\Exception $e) {
            session()->flash('message', [
                'type' => 'error',
                'text' => 'Error al eliminar los códigos: ' . $e->getMessage()
            ]);
        }
        return back();
    }
}
