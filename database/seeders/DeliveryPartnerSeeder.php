<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryPartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = [
            ['name' => 'delperson1', 'email' => 'deliveryper1@gmail.com', 'phone' => '1234567891'],
            ['name' => 'delperson2', 'email' => 'deliveryper2@gmail.com', 'phone' => '1234567892'],
            ['name' => 'delperson3', 'email' => 'deliveryper3@gmail.com', 'phone' => '1234567893'],
            ['name' => 'delperson4', 'email' => 'deliveryper4@gmail.com', 'phone' => '1234567894'],
            ['name' => 'delperson5', 'email' => 'deliveryper5@gmail.com', 'phone' => '1234567895'],
        ];

        foreach ($partners as $partner) {
            \App\Models\DeliveryPartner::create(array_merge($partner, [
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]));
        }
    }
}
