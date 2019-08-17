<?php

use Illuminate\Database\Seeder;

class QuizzesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create quizzes and attach them to the default user
        $quizzes = factory(App\Quiz::class, 50)->create();

        $defaultUser = App\User::findOrFail(1);
        
        foreach ($quizzes as $quiz) {
            $quiz->users()->attach($defaultUser, ['role' => 'maker']);
        }
    }
}
