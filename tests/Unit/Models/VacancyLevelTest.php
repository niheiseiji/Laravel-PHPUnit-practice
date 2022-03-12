<?php

namespace Tests\Unit\Models;

/**
 * テストクラスにはPHPUnit\Framework\TestCaseを継承する
 * クラス名は対象名Testとする
 * テストは上から順に実行される
 */

use PHPUnit\Framework\TestCase;
use App\Models\VacancyLevel;

class VacancyLevelTest extends TestCase
{

    /**
     * テストはtest*という名前のパブリックメソッドで定義する
     * 
     * PHPUnitではデータプロバイダという仕組みを使って、テストパターンを配列で定義し、テストメソッドに順に渡してテストを行うことができる。
     * アノテーション@dataProviderを使用しメソッド名を指定する。
     */

    /**
     * @param int $remainingCount
     * @param string $expectedMark
     * @dataProvider dataMark
     */
    public function testMark(int $remainingCount, string $expectedMark)
    {
        $level = new VacancyLevel($remainingCount);
        $this->assertSame($expectedMark, $level->mark());
    }

    public function dataMark()
    {
        return [
            '空きなし' => [
                'remainingCount' => 0,
                'expectedMark' => '×',
            ],
            '残りわずか' => [
                'remainingCount' => 4,
                'expectedMark' => '△',
            ],
            '空き十分' => [
                'remainingCount' => 5,
                'expectedMark' => '◎',
            ],
        ];
    }

    /**
     * テストメソッドは@testアノテーションでも指定することもできる
     */

    /**
     * @test
     */
    public function mark()
    {
        $level = new VacancyLevel(0);
        $this->assertSame('×', $level->mark());
    }

}