<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting to seed banks data...');

        $banks = [
            [
                'name' => 'Vietcombank',
                'code' => 'VCB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Techcombank',
                'code' => 'TCB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BIDV',
                'code' => 'BIDV',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Agribank',
                'code' => 'AGB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'VietinBank',
                'code' => 'CTG',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $this->command->info('Inserting ' . count($banks) . ' banks...');

        // Insert banks data
        DB::table('banks')->insert($banks);

        $this->command->info('✅ Banks seeded successfully! Total: ' . count($banks) . ' banks');
        $this->command->info('📊 Banks include:');
        $this->command->info('   - Vietcombank (VCB)');
        $this->command->info('   - Techcombank (TCB)');
        $this->command->info('   - BIDV (BIDV)');
        $this->command->info('   - Agribank (AGB)');
        $this->command->info('   - VietinBank (CTG)');
    }
}
