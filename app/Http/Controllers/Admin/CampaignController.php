<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = Campaign::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->through(function ($campaign) {
                return [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                    'description' => $campaign->description,
                    'is_active' => $campaign->is_active,
                    'starts_at' => $campaign->starts_at ? $campaign->starts_at->format('Y-m-d') : null,
                    'ends_at' => $campaign->ends_at ? $campaign->ends_at->format('Y-m-d') : null,
                    'codes_count' => $campaign->prizeCodes()->count(),
                    'created_at' => $campaign->created_at->format('d/m/Y'),
                ];
            })
            ->withQueryString();

        return Inertia::render('Admin/Campaigns', [
            'campaigns' => $campaigns,
            'filters' => $request->only(['search'])
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        Campaign::create($validated);

        return back()->with('success', 'Campaña creada exitosamente');
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        $campaign->update($validated);

        return back()->with('success', 'Campaña actualizada exitosamente');
    }

    public function destroy(Campaign $campaign)
    {
        // Verificar si tiene códigos asociados
        if ($campaign->prizeCodes()->count() > 0) {
            return back()->with('error', 'No se puede eliminar una campaña que tiene códigos asociados');
        }

        $campaign->delete();
        return back()->with('success', 'Campaña eliminada exitosamente');
    }
}
