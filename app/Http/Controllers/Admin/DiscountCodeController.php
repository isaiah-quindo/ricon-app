<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use App\Models\RaceCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DiscountCodeController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');

        $query = DiscountCode::with('raceCategories')->withCount('registrations');

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $codes = $query->orderByDesc('created_at')->get();

        // Lets the list collapse a code covering everything into one "All categories" pill.
        $allCategoryCount = RaceCategory::count();

        return view('admin.discount_codes.index', compact('codes', 'status', 'allCategoryCount'));
    }

    public function create()
    {
        $categories = RaceCategory::orderBy('name')->get();
        return view('admin.discount_codes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCode($request);
        $categoryIds = $validated['race_category_ids'];
        unset($validated['race_category_ids']);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = (bool) ($validated['is_active'] ?? false);
        $validated['one_per_email'] = (bool) ($validated['one_per_email'] ?? false);

        DiscountCode::create($validated)->raceCategories()->sync($categoryIds);

        return redirect()->route('admin.discount-codes.index')
            ->with('success', 'Discount code created.');
    }

    public function edit(DiscountCode $discountCode)
    {
        $categories = RaceCategory::orderBy('name')->get();
        $discountCode->load('raceCategories');

        return view('admin.discount_codes.edit', compact('discountCode', 'categories'));
    }

    public function update(Request $request, DiscountCode $discountCode)
    {
        $validated = $this->validateCode($request, $discountCode->id);
        $categoryIds = $validated['race_category_ids'];
        unset($validated['race_category_ids']);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = (bool) ($validated['is_active'] ?? false);
        $validated['one_per_email'] = (bool) ($validated['one_per_email'] ?? false);

        $discountCode->update($validated);
        $discountCode->raceCategories()->sync($categoryIds);

        return redirect()->route('admin.discount-codes.index')
            ->with('success', 'Discount code updated.');
    }

    private function validateCode(Request $request, ?string $ignoreId = null): array
    {
        return $request->validate([
            'code'                => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('discount_codes', 'code')->ignore($ignoreId)],
            // One code, one percentage, but any number of categories.
            'race_category_ids'   => 'required|array|min:1',
            'race_category_ids.*' => 'exists:race_categories,id',
            'discount_percentage' => 'required|numeric|min:0.01|max:100',
            'max_uses'            => 'nullable|integer|min:1',
            'expires_at'          => 'nullable|date|after:now',
            'one_per_email'       => 'nullable|boolean',
            'is_active'           => 'nullable|boolean',
            'description'         => 'nullable|string|max:255',
        ], [
            'race_category_ids.required' => 'Choose at least one race category.',
            'race_category_ids.min'      => 'Choose at least one race category.',
        ]);
    }
}
