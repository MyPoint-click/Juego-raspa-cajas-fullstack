<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrizeCode;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PrizeCodeController extends Controller
{
    public function index()
    {
        $codes = PrizeCode::latest()
            ->paginate(10)
            ->through(function ($code) {
                return [
                    'id' => $code->id,
                    'code' => $code->code,
                    'status' => $code->status,
                    'expires_at' => $code->expires_at ? $code->expires_at->format('d/m/Y') : null,
                    'created_at' => $code->created_at->format('d/m/Y'),
                ];
            });
        return Inertia::render('Admin/PrizeCodes', compact('codes'));
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

    public function destroy(PrizeCode $prizeCode)
    {
        $prizeCode->delete();
        return back()->with('success', 'Código eliminado exitosamente');
    }
}
