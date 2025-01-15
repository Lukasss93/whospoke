<?php

namespace Database\Seeders;

use App\Models\Profession;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
    public function run(): void
    {
        $professions = [
            [
                'name' => 'Frontend Developer',
                'abbreviation' => 'FE',
                'color' => '#16a249',
            ],
            [
                'name' => 'Backend Developer',
                'abbreviation' => 'BE',
                'color' => '#2562e9',
            ],
            [
                'name' => 'Fullstack Developer',
                'abbreviation' => 'FS',
                'color' => '#4e45e3',
            ],
            [
                'name' => 'DevOps',
                'abbreviation' => 'DO',
                'color' => '#56524d',
            ],
            [
                'name' => 'Designer',
                'abbreviation' => 'DE',
                'color' => '#4a5462',
            ],
            [
                'name' => 'Project Manager',
                'abbreviation' => 'PM',
                'color' => '#c88904',
            ],
            [
                'name' => 'Quality Assurance',
                'abbreviation' => 'QA',
                'color' => '#e8570c',
            ],
            [
                'name' => 'Help Desk',
                'abbreviation' => 'HD',
                'color' => '#da2626',
            ],
            [
                'name' => 'Research & Development',
                'abbreviation' => 'RD',
                'color' => '#000000',
            ],
            [
                'name' => 'DB Administrator',
                'abbreviation' => 'DB',
                'color' => '#0890b1',
            ],
            [
                'name' => 'Legacy Database Migration',
                'abbreviation' => 'LM',
                'color' => '#be26d1',
            ],
            [
                'name' => 'Mobile Developer',
                'abbreviation' => 'MD',
                'color' => '#0283c5',
            ]
        ];

        foreach ($professions as ['name' => $name, 'abbreviation' => $abbreviation, 'color' => $color]) {
            Profession::updateOrCreate([
                'abbreviation' => $abbreviation,
            ],[
                'name' => $name,
                'color' => $color,
            ]);
        }
    }
}
