<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RestaurantCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('menus')->orderBy('name')->get();

        return view('restaurant.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $hotelId = auth()->user()->hotel_id;

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->where('hotel_id', $hotelId)],
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'hotel_id' => $hotelId,
        ]);

        return redirect()->back()->with('success', 'Catégorie ajoutée avec succès.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $hotelId = auth()->user()->hotel_id;

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->where('hotel_id', $hotelId)->ignore($id)],
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->back()->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Catégorie supprimée avec succès.');
    }
}
