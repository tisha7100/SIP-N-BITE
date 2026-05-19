<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $foods = Food::with('category')->latest()->paginate(10);
        return view('admin.menu.index', compact('foods'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:50|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $request->except('image');
        $data['is_customizable'] = $request->has('is_customizable');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('foods', 'public');
        }

        Food::create($data);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Food item added successfully');
    }

    public function edit($id)
    {
        $food = Food::findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('admin.menu.edit', compact('food', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $food = Food::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:50|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $request->except('image');
        $data['is_customizable'] = $request->has('is_customizable');

        if ($request->hasFile('image')) {
            if ($food->image) {
                Storage::disk('public')->delete($food->image);
            }

            $data['image'] = $request->file('image')->store('foods', 'public');
        }

        $food->update($data);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Food updated successfully');
    }

    public function destroy($id)
    {
        $food = Food::findOrFail($id);

        if ($food->image) {
            Storage::disk('public')->delete($food->image);
        }

        $food->delete();

        return back()->with('success', 'Food deleted successfully');
    }
}