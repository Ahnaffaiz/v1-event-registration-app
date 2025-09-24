<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Technology',
            'Business',
            'Education',
            'Networking',
            'Conference',
            'Workshop',
            'Training',
            'Seminar',
            'Webinar',
            'Innovation',
            'Startup',
            'Leadership',
            'Digital',
            'Marketing',
            'Development'
        ];

        foreach ($tags as $tagName) {
            Tag::firstOrCreate(['name' => $tagName]);
        }
    }
}
