<?php

use Illuminate\Database\Seeder;

class QuizInvitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\QuizInvite::class, 5)->create([
            'email' => 'test@demo.com'
        ]);
    }
}
