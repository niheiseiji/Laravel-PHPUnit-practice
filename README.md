## 概要
### ユニットテストの概要
ユニットテスト（単体テスト）は、各クラスが持つメソッドが意図通りに動作するかの検証するために実施する。ブラウザ上でのテストと違い、テストをコード化するため自動化が可能になる。テストを自動化することでコードの品質を高めることができる。多くのテストパターンが必要な項目についてはブラウザ上でのテストと比較してテスト工数を削減できる可能性がある。

### PHPUnitの概要
PHPUnitは、「開発者は、新しくコミットされたコードの誤りをすばやく見つけ、コードの他の部分で不具合やバグが発生していないと明言できる必要がある」という考えに基づいたPHPテスト用フレームワークである。  
公式マニュアル:https://phpunit.readthedocs.io/ja/latest/

### PHPUnitとLaravelの関係性
LaravelはPHPUnitをサポートしており標準で搭載されている。Laravelをインストールしていれば追加インストールなくPHPUnitによるテストが実行できる。Laravelをインストールしたディレクトリにあるphpunit.xmlがPHPUnitの設定ファイルとなる。  
↓インストール直後のテスト実行例
```
# php artisan test

   PASS  Tests\Unit\ExampleTest
  ✓ example

   PASS  Tests\Feature\ExampleTest
  ✓ example

  Tests:  2 passed
  Time:   4.64s
```

### テスト駆動開発について
テスト駆動開発には以下3つのフェーズがある。
#### 1. レッドフェーズ
テストが失敗するフェーズ。クラス名、インターフェース、テストパターンを決めるという意味で設計のフェーズ。
#### 2. グリーンフェーズ
テストが成功するフェーズ。最短でテストが通る実装を行う。
#### 3. リファクタリングフェーズ
グリーンフェーズでの実装を正しく・きれいにしていくフェーズ。

### PHPUnit関連用語
#### ユニットテスト
Service、Model、Middleware、Policyなどで作ったクラスのメソッド単位でテストする。最も粒度の細かいテスト。```/tests/Unit```配下にテストを配置する。
#### フィーチャーテスト
１つのHTTPリクエスト単位の動きをテストする。コントローラのアクションメソッド単位でのテスト。```/tests/Feature```配下にテストを配置する。
#### アサーション
あるコードが実行される時に満たされるべき条件を記述して実行時にチェックする仕組みのこと。例えばassertEquals()は引数1と引数2が等しくない場合にエラーを返す。以下のような使い方をする。
```
    public function testFailure()
    {
        // 等しくないのでエラーを返す
        $this->assertEquals(1, 0);
    }
```

#### アノテーション
メタデータを表す特別な構文のこと(コメント内にあるがPHPUnit的には処理に影響する)。PHPUnitでは各メソッドの前にアノテーションを付与することで、実行時の振る舞いを設定できる。以下のような使い方をする。 例えばあるメソッドがテストメソッドであることを明示的に指定するには```@test```を使う。
```
/**
 * @test
 */
public function initialBalanceShouldBe0()
{
    $this->assertSame(0, $this->ba->getBalance());
}
```
（順次追加）

## ハンズオン
### 目的
- テスト駆動開発の基本的な流れの修得
- ユニットテスト（単体テスト）とフィーチャーテスト（機能テスト）の修得
- Laravelでの自動テスト手法の修得

### 参考資料
基本的な流れは下記リンクの教材に従う。適宜追加で学習していく。  
>「Laravelでテスト駆動開発を学ぼう！」https://www.techpit.jp/courses/92

### 動作環境
- Laravel v8.62.0
- PHP 8.0.11
- MySQL 8.0.26

### ユニットテストとフィーチャーテストの作り方
以下、テスト作成の時系列順でテストクラスの作り方と実行結果を記載する。なお詳細な開発内容はテストケースごとに作った各ブランチを参照されたい。各ブランチでの開発内容を差分を分かりやすく表示するためにプルリクを追加しているのでそちらでも確認可能。  
（※README.mdはmainブランチでのみ最新化している）

基本的な開発の流れとしてテスト駆動開発の流儀に則り、テストクラス作成→テスト実行（失敗）→テストが通るように開発→テスト実行（成功）となる。
#### ユニットテストcase1：予約枠の残数に対して表示される記号が正しいかテストする
>ブランチ名:```unit-test-1```  
>差分：https://github.com/niheiseiji/Laravel-PHPUnit-practice/pull/2/files
##### テストクラスを作成する
```
/app #  php artisan make:test --unit Models/VacancyLevelTest
Test created successfully.
```
##### テストを実行してみる。（この段階では失敗する）
```
/app #  php artisan make:test --unit Models/VacancyLevelTest
Test created successfully.
/app # php artisan test tests/Unit/Models/VacancyLevelTest.php

   FAIL  Tests\Unit\Models\VacancyLevelTest
  ✕ mark

  Tests:  1 failed

   Error 

  Class 'App\Models\VacancyLevel' not found

  at tests/Unit/Models/VacancyLevelTest.php:12
```

##### テストを通す為に実装を行い、再度テストする
```
/app # php artisan make:model Models/VacancyLevel
Model created successfully.

/app #  php artisan test tests/Unit/Models/VacancyLevelTest.php

   PASS  Tests\Unit\Models\VacancyLevelTest
  ✓ mark

  Tests:  1 passed
  Time:   0.67s
```
#### ユニットテストcase2：予約枠の残数に対して使用するCSSクラスの文字列が正しいかテストする
>ブランチ名:```unit-test-2```  
>差分：https://github.com/niheiseiji/Laravel-PHPUnit-practice/pull/3/files
##### テストクラスを作成する
```
/app # php artisan make:test --unit Models/VacancyLevelSlugTest
Test created successfully.
```
##### テストを実行してみる。（この段階では失敗する）
```
/app #  php artisan test tests/Unit/Models/VacancyLevelSlugTest.php

   FAIL  PHPUnit\Framework\ErrorTestCase
  ✕ error

  Tests:  1 failed, 1 pending
```
##### テストを通す為に実装を行い、再度テストする
```
/app #  php artisan test tests/Unit/Models/VacancyLevelSlugTest.php

   PASS  Tests\Unit\Models\VacancyLevelSlugTest
  ✓ slug with data set "空きなし"
  ✓ slug with data set "残りわずか"
  ✓ slug with data set "空き十分"

  Tests:  3 passed
  Time:   0.63s
```

#### フィーチャーテストcase1：レッスンの詳細ページを開き、レッスン名と空き状況マークが表示されているかテストする
>ブランチ名:```feature-test-1```  
>差分：https://github.com/niheiseiji/Laravel-PHPUnit-practice/pull/5/files
##### テストクラスを作成する
```
/app # php artisan make:test Http/Controllers/LessonControllerTest
Test created successfully.
```
##### テストを実行してみる。（この段階では失敗する）
```
/app # php artisan test tests/Feature/Http/Controllers/LessonControllerTest.php

   FAIL  Tests\Feature\Http\Controllers\LessonControllerTest
  ✕ show

  Tests:  1 failed

  Expected status code 200 but received 404. Failed asserting that 200 is identical to 404.
```
##### テストを通す為に実装を行い、再度テストする
```
/app # php artisan test tests/Feature/Http/Controllers/LessonControllerTest.php

   PASS  Tests\Feature\Http\Controllers\LessonControllerTest
  ✓ show

  Tests:  1 passed
  Time:   8.74s
```

#### フィーチャーテストcase2：レッスンの詳細ページを開いたときのレッスン空き状況を、テストデータをfakerで作ってテストする
>ブランチ名:```feature-test-2```  
>差分：https://github.com/niheiseiji/Laravel-PHPUnit-practice/pull/6/files
##### テストクラスを作成する
```
/app # php artisan make:test Http/Controllers/LessonController2Test
Test created successfully.
```
##### テストを実行してみる。（この段階では失敗する）
```
/app # php artisan test tests/Feature/Http/Controllers/LessonController2Test.php

   FAIL  Tests\Feature\Http\Controllers\LessonController2Test
  ✕ show with data set "空き十分"

  Tests:  1 failed, 2 pending

   Error 

  Class 'Tests\Feature\Http\Controllers\User' not found
```
##### テストを通す為に実装を行い、再度テストする
```
/app # php artisan test tests/Feature/Http/Controllers/LessonController2Test.php

   PASS  Tests\Feature\Http\Controllers\LessonController2Test
  ✓ show with data set "空き十分"
  ✓ show with data set "空きわずか"
  ✓ show with data set "空きなし"

  Tests:  3 passed
  Time:   8.26s
```

#### ユニットテストcase3：ユーザーが加入するプランに応じてその月の予約可否をテストする
>ブランチ名:```unit-test-3```  
>差分：https://github.com/niheiseiji/Laravel-PHPUnit-practice/pull/4/files
##### テストクラスを作成する
```
/app # php artisan make:test --unit Models/UserTest
Test created successfully.
```
##### テストを実行してみる。（この段階では失敗する）
```
/app # php artisan test tests/Unit/Models/UserTest.php

   FAIL  Tests\Unit\Models\UserTest
  ✕ can reserve with data set "予約可:レギュラー,空きあり,月の上限以下"

  Tests:  1 failed, 1 pending

   Error 

  Call to a member function connection() on null
```
##### テストを通す為に実装を行い、再度テストする
```
/app # php artisan test tests/Unit/Models/UserTest.php

   PASS  Tests\Unit\Models\UserTest
  ✓ can reserve with data set "予約可:レギュラー,空きあり,月の上限以下"
  ✓ can reserve with data set "予約不可:レギュラー,空きあり,月の上限"
  ✓ can reserve with data set "予約不可:レギュラー,空きなし,月の上限以下"
  ✓ can reserve with data set "予約可:ゴールド,空きあり"
  ✓ can reserve with data set "予約不可:ゴールド,空きなし"

  Tests:  5 passed
  Time:   1.22s
```

### appendix
#### どういうときにユニットテストがあると良いか
テスト戦略はプロジェクトの置かれた状況を踏まえて建てるべきで、現実的には全てのコードに対してテストを作成するのは難しい場合もある。そういった場合に優先的にテストを行うべき項目を決める観点として以下がある。
- プロダクトのコアな機能（金額計算、コンバージョンに至る処理、などミスれないもの）
- 変更頻度の高いもの（割引ロジック、サービスに付帯するオプション、などデグレがこわいもの）
- テストパターンの多いもの（プラン別の制御、ユーザーのアクションに応じた通知、など時間がかかるもの）