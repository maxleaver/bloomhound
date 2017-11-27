<?php

use Illuminate\Database\Seeder;

class MarkupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'no_charge',
                'title' => 'No Charge',
                'description' => 'There\'s no additional markup',
                'allow_entry' => false,
                'field_label' => '',
            ],
            [
                'name' => 'cost',
                'title' => 'Your Cost',
                'description' => 'Pass along your costs with no markup',
                'allow_entry' => false,
                'field_label' => '',
            ],
            [
                'name' => 'cost_plus_percent',
                'title' => 'Cost + %',
                'description' => 'Mark up your cost by a set percentage',
                'allow_entry' => true,
                'field_label' => '%',
            ],
            [
                'name' => 'cost_plus_amount',
                'title' => 'Cost + $',
                'description' => 'Mark up your cost by a set amount',
                'allow_entry' => true,
                'field_label' => '$',
            ],
            [
                'name' => 'fixed_price',
                'title' => 'Fixed Price',
                'description' => 'Set a fixed dollar amount for each item',
                'allow_entry' => true,
                'field_label' => '$',
            ],
            [
                'name' => 'amount_times_event_days',
                'title' => 'Rental',
                'description' => 'Charge a set rate per event day',
                'allow_entry' => true,
                'field_label' => '$ per day',
            ],
        ];

        DB::table('markups')->insert($data);
    }
}
