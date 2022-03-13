<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Lesson;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class LessonController2Test extends TestCase
{
    use RefreshDatabase;

    /**
     * @param int $capacity
     * @param int $reservationCount
     * @param string $expectedVacancyLevelMark
     * @dataProvider dataShow
     */
    public function testShow(string $name, int $capacity, int $reservationCount, string $expectedVacancyLevelMark)
    {
        $lesson = factory(Lesson::class)->create(['name' => $name, 'capacity' => $capacity]);
        for ($i = 0; $i < $reservationCount; $i++) {
            $user = factory(User::class)->create();
            factory(Reservation::class)->create(['lesson_id' => $lesson->id, 'user_id' => $user->id]);
        }
        $response = $this->get("/lessons/{$lesson->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($lesson->name);
        $response->assertSee("空き状況: {$expectedVacancyLevelMark}");
    }

    public function dataShow()
    {
        return [
            '空き十分' => [
                'name' => '楽しいヨガレッスン',
                'capacity' => 6,
                'reservationCount' => 1,
                'expectedVacancyLevelMark' => '◎',
            ],
            '空きわずか' => [
                'name' => '楽しいヨガレッスン',
                'capacity' => 6,
                'reservationCount' => 2,
                'expectedVacancyLevelMark' => '△',
            ],
            '空きなし' => [
                'name' => '楽しいヨガレッスン',
                'capacity' => 1,
                'reservationCount' => 1,
                'expectedVacancyLevelMark' => '×',
            ],
        ];
    }
}