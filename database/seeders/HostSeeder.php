<?php

namespace Database\Seeders;

use App\Models\Host;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hosts = [
            [
                'name' => 'Tech Conference Organizers',
                'desc' => 'Leading organizer of technology conferences and workshops',
                'web' => 'https://techconf.example.com'
            ],
            [
                'name' => 'Business Summit Inc.',
                'desc' => 'Professional business event management company',
                'web' => 'https://businesssummit.example.com'
            ],
            [
                'name' => 'EduEvents',
                'desc' => 'Educational event specialist focused on learning and development',
                'web' => 'https://eduevents.example.com'
            ],
            [
                'name' => 'Innovation Hub',
                'desc' => 'Startup and innovation focused event organizer',
                'web' => 'https://innovationhub.example.com'
            ],
            [
                'name' => 'Digital Marketing Agency',
                'desc' => 'Marketing and digital transformation event specialist',
                'web' => 'https://digitalmarketing.example.com'
            ]
        ];

        foreach ($hosts as $hostData) {
            Host::firstOrCreate(['name' => $hostData['name']], $hostData);
        }
    }
}
