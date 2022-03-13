<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\VacancyLevel;

class VacancyLevelSlugTest extends TestCase
{
    /**
     * @param int $remainingCount
     * @param string $expectedMark
     * @dataProvider dataSlug
     */
    public function testSlug(int $remainingCount, string $expectedMark)
    {
        $level = new VacancyLevel($remainingCount);
        $this->assertSame($expectedMark, $level->slug());
    }

    public function dataSlug()
    {
        return [
            '空きなし' => [
                'remainingCount' => 0,
                'expectedSlug' => 'empty',
            ],
            '残りわずか' => [
                'remainingCount' => 4,
                'expectedSlug' => 'few',
            ],
            '空き十分' => [
                'remainingCount' => 5,
                'expectedSlug' => 'enough',
            ],
        ];
    }
}