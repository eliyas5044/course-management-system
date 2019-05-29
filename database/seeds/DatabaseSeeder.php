<?php

use App\Course;
use App\Lesson;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory(User::class, 20)->create()->each(function ($user) {
            $user->courses()->save(factory(Course::class)->make())->each(function ($course) {
                $course->lessons()->save(factory(Lesson::class)->make());
            });
        });
    }
}
