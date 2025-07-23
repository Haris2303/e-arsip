<?php

namespace Database\Seeders;

use App\Models\OutgoingMail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OutgoingMailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OutgoingMail::factory(100)->create();
    }
}
