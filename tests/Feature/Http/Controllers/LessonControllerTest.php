<?php

/**
 * フィーチャーテストでは複数のクラスが連動するような処理の実行後の状態を確認する
 * 一般にControllerを対象とし、HTTPリクエストを入力値、レスポンスを出力値として検査する
 */

namespace Tests\Feature\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class LessonControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShow()
    {
        // モデルファクトリを使ってデータベースにテストで使用する適当なデータを投入する
        $lesson = factory(Lesson::class)->create(['name' => '楽しいヨガレッスン']);

        // テストしたいURLにアクセスする
        $response = $this->get("/lessons/{$lesson->id}");

        // レスポンスコードをチェックする
        $response->assertStatus(Response::HTTP_OK);

        // レスポンス（HTMLタグ含む）に含まれてなければならないデータが存在するかチェックする
        $response->assertSee($lesson->name);
        $response->assertSee('空き状況: ×');

        // 純粋にテキストだけチェックしたい時はassertSeeText()を使うこともできる
        $response->assertSeeText($lesson->name);
        $response->assertSeeText('空き状況: ×');
    }
}