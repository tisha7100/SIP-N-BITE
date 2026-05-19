<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Addon;
use App\Models\TableLayout;
use App\Models\Food;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin - using updateOrCreate to avoid duplicate errors
        Admin::updateOrCreate(
            ['email' => 'admin@bitnsip.com'],
            [
                'name' => 'Sip N Bite Admin',
                'password' => 'admin123',
            ]
        );

        // Categories
        $cats = ['Starters', 'Main Course', 'Beverages', 'Desserts', 'Fast Food'];
        foreach ($cats as $cat) {
            Category::updateOrCreate(['name' => $cat]);
        }

        // Addons
        $addons = [
            ['name' => 'Extra Cheese', 'price' => 30],
            ['name' => 'Peri Peri Masala', 'price' => 20],
            ['name' => 'Mayo Dip', 'price' => 15],
            ['name' => 'Coke 250ml', 'price' => 45],
        ];
        foreach ($addons as $a) {
            Addon::updateOrCreate(['name' => $a['name']], $a);
        }

        // Tables
        for ($i = 1; $i <= 10; $i++) {
            TableLayout::updateOrCreate(
                ['table_number' => 'T-' . $i],
                [
                    'capacity' => rand(2, 6),
                    'status' => 'available'
                ]
            );
        }

        // Sample Foods
        $starters = Category::where('name', 'Starters')->first();
        if ($starters) {
            Food::updateOrCreate(
                ['name' => 'Paneer Tikka'],
                [
                    'category_id' => $starters->id,
                    'description' => 'Grilled paneer marinated in spicy yogurt',
                    'price' => 180,
                ]
            );
        }

        $fastfood = Category::where('name', 'Fast Food')->first();
        if ($fastfood) {
            Food::updateOrCreate(
                ['name' => 'Maharaja Veg Burger'],
                [
                    'category_id' => $fastfood->id,
                    'description' => 'Giant veg patty with signature sauce',
                    'price' => 120,
                ]
            );
        }

        $maincourse = Category::where('name', 'Main Course')->first();
        if ($maincourse) {
            Food::updateOrCreate(
                ['name' => 'Veg Kolhapuri'],
                [
                    'category_id' => $maincourse->id,
                    'description' => 'Spicy mixed vegetable curry',
                    'price' => 220,
                ]
            );
        }
    }
}
