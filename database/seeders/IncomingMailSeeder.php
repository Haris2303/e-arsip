<?php

namespace Database\Seeders;

use App\Models\IncomingMail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncomingMailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IncomingMail::factory(100)->create();
    }
}
