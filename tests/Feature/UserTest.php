<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // データベースマイグレーション
        $this->artisan('migrate');
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_add()
    {
        //ユーザーの追加ができるか
            //ユーザーがDBに保存されるか
            //返り値に保存されたユーザー情報が返ってくるか
            //保存されるデータがリクエストと同じものか
        //エラー時
            //503がかえってくるか
            //resultがfalseになっているか
            //エラーmessage

        //$user = User::factory()->create();
        //$users = User::factory()->count(3)->create();
        // $this->seed();
        // $response = $this->get('/api/users');
        //正常時のテスト
        $params = ['name' => 'hirai'];
        $response = $this->post('/api/users', $params);
        $response->assertStatus(201)
                ->assertJson([
                    'name' => $params['name'],
                ]);

        //エラー時のテスト
        $params = [];
        $response = $this->post('/api/users', $params);
        $response->assertStatus(503)
                ->assertJson([
                    'result' => false,
                    'error' => [
                        'messages' => 'error!'
                    ]
                ]);
    }

    public function test_fetchAll()
    {
        //正常時のテスト
        $users = User::factory()->count(10)->create();
        $response = $this->get('/api/users');
        $response->assertOk()
                ->assertJsonCount(10);
    }

    public function test_fetchById()
    {
        //正常時のテスト
        $users = User::factory()->count(5)->create();
        $response = $this->get('/api/users/' . $users->toArray()[0]['id']);
        $response->assertOk()
                ->assertJson([
                    'id' => $users->toArray()[0]['id'],
                    'name' => $users->toArray()[0]['name'],
                ]);

        //エラー時のテスト
        $response = $this->get('/api/users/' . 6);
        $response->assertStatus(404)
                ->assertJson([
                    'result' => false,
                    'error' => [
                        'messages' => 'not_exit_data'
                    ]
                ]);

    }
}
