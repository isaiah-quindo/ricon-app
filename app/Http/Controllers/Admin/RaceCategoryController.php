<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RaceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RaceCategoryController extends Controller
{
    public function index()
    {
        $categories = RaceCategory::withCount('registrations')->get();
        return view('admin.race_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.race_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'price'            => 'required|numeric|min:0',
            'distance_km'      => 'required|string|max:50',
            'elevation_m'      => 'nullable|string|max:50',
            'description'      => 'nullable|string',
            'max_slots'        => 'required|integer|min:1',
            'bib_start_number' => 'required|integer|min:1',
            'is_active'        => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        RaceCategory::create($validated);

        return redirect()->route('admin.race-categories.index')
            ->with('success', 'Race category created.');
    }

    public function edit(RaceCategory $raceCategory)
    {
        return view('admin.race_categories.edit', compact('raceCategory'));
    }

    public function update(Request $request, RaceCategory $raceCategory)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'price'            => 'required|numeric|min:0',
            'distance_km'      => 'required|string|max:50',
            'elevation_m'      => 'nullable|string|max:50',
            'description'      => 'nullable|string',
            'max_slots'        => 'required|integer|min:1',
            'bib_start_number' => 'required|integer|min:1',
            'is_active'        => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $raceCategory->update($validated);

        return redirect()->route('admin.race-categories.index')
            ->with('success', 'Race category updated.');
    }

    public function destroy(RaceCategory $raceCategory)
    {
        $raceCategory->delete();
        return redirect()->route('admin.race-categories.index')
            ->with('success', 'Race category deleted.');
    }
}
