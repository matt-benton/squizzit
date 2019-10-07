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
        $defaultUser = App\User::findOrFail(1);

        // create a quiz with a bunch of questions
        $longQuiz = factory(App\Quiz::class)->create([
            'name' => 'Long Quiz',
            'description' => 'Long quiz containing 75 questions.'
        ]);
        $longQuiz->questions()->createMany(
            factory(App\Question::class, 75)->make([
                'quiz_id' => $longQuiz->id
            ])->toArray()
        );
        $longQuiz->users()->attach($defaultUser, ['role' => 'maker']);

        // create quizzes and attach them to the default user
        $quizzes = factory(App\Quiz::class, 50)->create();
        
        foreach ($quizzes as $quiz) {
            $quiz->users()->attach($defaultUser, ['role' => 'maker']);
        }
    }
}
