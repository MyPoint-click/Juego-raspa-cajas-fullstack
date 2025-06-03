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
                    'is_current' => $campaign->is_current,
                    'starts_at' => $campaign->starts_at ? $campaign->starts_at->format('Y-m-d') : null,
                    'ends_at' => $campaign->ends_at ? $campaign->ends_at->format('Y-m-d') : null,
                    'codes_count' => $campaign->prizeCodes()->count(),
                    'created_at' => $campaign->created_at->format('d/m/Y'),
                ];
            })
            ->withQueryString();

        return Inertia::render('Admin/Campaigns', [
            'campaigns' => $campaigns,
            'filters' => $request->only(['search']),
            'message' => session('message')
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

        try {
            Campaign::create($validated);

            session()->flash('message', [
                'type' => 'success',
                'text' => 'Campaña creada exitosamente'
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear la campaña']);
        }

        return back();
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

        try {
            $campaign->update($validated);

            session()->flash('message', [
                'type' => 'success',
                'text' => 'Campaña actualizada exitosamente'
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar la campaña']);
        }

        return back();
    }

    public function destroy(Campaign $campaign)
    {
        try {
            if ($campaign->prizeCodes()->count() > 0) {
                session()->flash('message', [
                    'type' => 'error',
                    'text' => 'No se puede eliminar una campaña que tiene códigos asociados'
                ]);
                return back();
            }

            $campaign->delete();

            session()->flash('message', [
                'type' => 'success',
                'text' => 'Campaña eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            session()->flash('message', [
                'type' => 'error',
                'text' => 'Error al eliminar la campaña: ' . $e->getMessage()
            ]);
        }

        return back();
    }

    public function setCurrent(Campaign $campaign)
    {
        try {
            // Primero, desactivar todas las campañas actuales
            Campaign::where('is_current', true)->update(['is_current' => false]);

            // Establecer la nueva campaña como actual
            $campaign->update(['is_current' => true]);

            session()->flash('message', [
                'type' => 'success',
                'text' => "La campaña '{$campaign->name}' ha sido establecida como actual"
            ]);
        } catch (\Exception $e) {
            session()->flash('message', [
                'type' => 'error',
                'text' => 'Error al establecer la campaña actual'
            ]);
        }

        return back();
    }
}
