<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Conference',
            'Workshop',
            'Seminar',
            'Webinar',
            'Meetup',
            'Training',
            'Exhibition',
            'Networking Event',
            'Product Launch',
            'Panel Discussion',
            'Hackathon',
            'Community Event',
            'Corporate Event',
            'Educational Event',
            'Entertainment'
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(['name' => $categoryName]);
        }
    }
}
