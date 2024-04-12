<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Create 5 users.
        User::factory(5)->create();
    
        // Create 3 events.
        \App\Models\Event::factory(3)->create()->each(function ($event) {
            // For each event, create 10 tickets.
            // The TicketFactory's 'event_id' attribute will be set automatically by the relationship.
            $event->tickets()->saveMany(\App\Models\Ticket::factory(10)->make());
        });
    }
}
