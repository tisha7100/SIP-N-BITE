<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Food;

class HomeController extends Controller
{
    public function index(Request $request)
{
    $categories = Category::orderBy('name', 'asc')->get()->unique('name');
    $query = Food::with('category');

    if ($request->has('category') && $request->category != 'all') {
        $query->whereHas('category', function($q) use ($request) {
            $q->where('name', $request->category);
        });
    }

    // 👇 SHOW ONLY 4 ITEMS ON HOME
    $foods = $query->take(4)->get();

    return view('home', compact('categories', 'foods'));
}

    public function menu(Request $request)
{
    $categories = Category::orderBy('name', 'asc')->get()->unique('name');

    $query = Food::with('category')
        ->withCount('reviews')
        ->withAvg('reviews', 'rating');

    if ($request->has('category') && $request->category != 'all') {
        $query->whereHas('category', function($q) use ($request) {
            $q->where('name', $request->category);
        });
    }

    // 👇 FULL MENU WITH PAGINATION
    $foods = $query->paginate(8);

    $customizableCategories = Category::with('addonGroups.addons')
        ->where('is_customizable', true)
        ->get();

    return view('menu', compact('categories', 'foods', 'customizableCategories'));
}

    public function foodDetail($id)
{
    $food = Food::with(['category', 'reviews.user'])->findOrFail($id);

    $groups = [];
    if ($food->category && ($food->is_customizable || $food->category->is_customizable)) {
        $groups = $food->category->addonGroups()
            ->with(['addons' => function ($query) use ($food) {
                $query->where('category_id', $food->category_id);
            }])
            ->get()
            ->filter(function ($group) {
                return $group->addons->isNotEmpty();
            });
    }

    return view('food_detail', compact('food', 'groups'));
}

    public function submitReview(Request $request, $id)
{
    $food = Food::findOrFail($id);

    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    $food->reviews()->updateOrCreate(
        ['user_id' => auth()->id()],
        ['rating' => $request->rating, 'comment' => $request->comment]
    );

    return back()->with('success', 'Your review has been saved successfully.');
}
}