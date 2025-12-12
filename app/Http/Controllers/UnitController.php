<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Http\Requests\Unit\StoreUnitRequest;
use App\Http\Requests\Unit\UpdateUnitRequest;

class UnitController extends Controller
{
    public function __construct()
    {
        // Only super admins can manage units
        $this->middleware('role:admin');
    }

    public function index()
    {
        // Units management - admin only
        $units = Unit::query()
            ->select(['id', 'name', 'slug', 'short_code', 'created_by'])
            ->with('creator:id,name')
            ->latest()
            ->get();

        return view('units.index', [
            'units' => $units,
        ]);
    }

    public function create()
    {
        return view('units.create');
    }

    public function show(Unit $unit)
    {
        // Ensure related products are loaded on the unit instance
        $unit->loadMissing('products');

        return view('units.show', [
            'unit' => $unit
        ]);
    }

    public function store(StoreUnitRequest $request)
    {
        Unit::create($request->validated());

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit has been created!');
    }

    public function edit(Unit $unit)
    {
        return view('units.edit', [
            'unit' => $unit
        ]);
    }

    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $unit->update($request->all());

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit has been updated!');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit has been deleted!');
    }
}
