<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::paginate(5);

        return view('facility.index', [
            'facilities' => $facilities,
        ]);
    }

    public function create()
    {
        return view('facility.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'detail' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
        ]);

        Facility::create($request->all());

        return redirect()->route('facility.index')->with('success', 'Facility added successfully.');
    }

    public function edit(Facility $facility)
    {
        return view('facility.edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'detail' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
        ]);

        $facility->update($request->all());

        return redirect()->route('facility.index')->with('success', 'Facility updated successfully.');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();

        return redirect()->route('facility.index')->with('success', 'Facility deleted successfully.');
    }
}
