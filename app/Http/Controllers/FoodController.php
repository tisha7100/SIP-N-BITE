<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FoodController extends Controller
{
     public function show($id)
    {
        $food = Food::with('category.addonGroups.addons')->findOrFail($id);

        $groups = $food->category->addonGroups;

        return view('food_detail', compact('food', 'groups'));
    }
}
