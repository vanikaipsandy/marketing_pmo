<?php

namespace Database\Seeders;

use App\Models\DataSource;
use Illuminate\Database\Seeder;

class DataSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Pertamina Digital Transformation DT Workstream Compendium',
                'month' => 'May',
                'year' => 2024,
            ],
            [
                'name' => 'Appendix 7 - List of Scope Charter for all digital initiatives identified v2',
                'month' => 'June',
                'year' => 2024,
            ],
        ];

        foreach ($data as $item) {
            DataSource::updateOrCreate($item);
        }
    }
}
