<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrizeCode;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PrizeCodeController extends Controller
{
    public function index(Request $request)
    {
        $codes = PrizeCode::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('expires_at', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(5)
            ->through(function ($code) {
                return [
                    'id' => $code->id,
                    'code' => $code->code,
                    'status' => $code->status,
                    'session_expires_at' => $code->session_expires_at ? $code->session_expires_at->format('d/m/Y-H:i') : null,
                    'expires_at' => $code->expires_at ? $code->expires_at->format('m/d/Y') : null,
                    'created_at' => $code->created_at->format('d/m/Y'),
                ];
            })
            ->withQueryString();

        return Inertia::render('Admin/PrizeCodes', [
            'codes' => $codes,
            'filters' => $request->only(['search'])
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
            'expires_at' => 'nullable|date|after:today'
        ]);

        $codes = collect()->pad($request->quantity, null)->map(function () use ($request) {
            return PrizeCode::create([
                'code' => strtoupper(Str::random(8)),
                'status' => 'unused',
                'expires_at' => $request->expires_at
            ]);
        });

        return back()->with('success', "{$codes->count()} códigos generados exitosamente");
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $code = PrizeCode::where('code', $request->code)->first();

        if (!$code) {
            return back()->with('error', 'Código no encontrado');
        }

        if ($code->status === 'unused') {
            return back()->with('error', 'Este código aún no ha sido visualizado');
        }

        if ($code->status === 'used') {
            return back()->with('error', 'Este código ya ha sido utilizado');
        }

        // Solo verificar expiración si no está permitido verificar expirados
        if (!$request->allow_expired && $code->status === 'viewed' && $code->expires_at && $code->expires_at < now()) {
            return back()->with('error', 'Este código ha expirado y no puede ser verificado');
        }

        // Si el código está en estado 'viewed', actualizarlo a 'used'
        $code->update([
            'status' => 'used',
            'used_at' => now()
        ]);

        return back()->with('success', 'Código verificado y marcado como usado exitosamente');
    }

    public function destroy(PrizeCode $prizeCode)
    {
        $prizeCode->delete();
        return back()->with('success', 'Código eliminado exitosamente');
    }

    // Método para eliminar códigos expirados
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:all,unused,viewed,used',
            'date_before' => 'nullable|date',
        ]);

        $query = PrizeCode::query();

        // Filtrar por estado si no es 'all'
        if ($request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filtrar por fecha si se especificó
        if ($request->date_before) {
            $query->whereDate('expires_at', $request->date_before);
        }

        // Obtener la cantidad de registros que se eliminarán
        $codesCount = $query->count();

        if ($codesCount === 0) {
            return back()->with('error', 'No se encontraron códigos que cumplan con los criterios especificados');
        }

        // Limitar la cantidad a eliminar según lo especificado
        $actuallyDeleted = $query->take($request->quantity)->delete();

        return back()->with('success', "Se eliminaron {$actuallyDeleted} códigos exitosamente");
    }
}
