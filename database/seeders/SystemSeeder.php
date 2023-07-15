<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('system')->insert(['key' => 'token', 'value' => null]);
        DB::table('system')->insert(['key' => 'token_lifetime', 'value' => null]);
    }
}
