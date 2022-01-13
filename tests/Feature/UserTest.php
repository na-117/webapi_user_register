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
    public function test_ユーザーの追加ができるか()
    {
        $params = ['name' => 'hirai'];
        $response = $this->post('/api/users', $params);
        $response->assertStatus(201)
                ->assertJson([
                    'name' => $params['name'],
                ]);
    }

    public function test_空配列でのユーザーの追加エラー時400が返ってくるか()
    {
        $params = [];
        $response = $this->post('/api/users', $params);
        $response->assertStatus(400)
                ->assertJson([
                    'result' => false,
                    'error' => [
                        'messages' => 'Validation error'
                    ]
                ]);
    }

    public function test_int型でのユーザーの追加エラー時400が返ってくるか()
    {
        $params = ["name" => 1];
        $response = $this->post('/api/users', $params);
        $response->assertStatus(400)
                ->assertJson([
                    'result' => false,
                    'error' => [
                        'messages' => 'Validation error'
                    ]
                ]);
    }

    public function test_全てのユーザーが取得できるか()
    {
        $users = User::factory()->count(10)->create();
        $response = $this->get('/api/users');
        $response->assertOk()
                ->assertJsonCount(10);
    }

    public function test_ID指定ユーザー取得ができるか()
    {
        $users = User::factory()->count(5)->create();
        $response = $this->get('/api/users/' . $users->toArray()[0]['id']);
        $response->assertOk()
                ->assertJson([
                    'id' => $users->toArray()[0]['id'],
                    'name' => $users->toArray()[0]['name'],
                ]);
    }

    public function test_ID指定ユーザー取得エラー時に404が返ってくるか()
    {
        $response = $this->get('/api/users/' . 999);
        $response->assertStatus(404)
                ->assertJson([
                    'result' => false,
                    'error' => [
                        'messages' => 'user not found'
                    ]
                ]);
    }

    public function test_IDが文字列での指定ユーザー取得エラー時に400が返ってくるか()
    {
        $response = $this->get('/api/users/' . 'abc');
        $response->assertStatus(400)
                ->assertJson([
                    'result' => false,
                    'error' => [
                        'messages' => 'Validation error'
                    ]
                ]);
    }

    public function test_IDが0指定ユーザー取得エラー時に400が返ってくるか()
    {
        $response = $this->get('/api/users/' . 0);
        $response->assertStatus(400)
                ->assertJson([
                    'result' => false,
                    'error' => [
                        'messages' => 'Validation error'
                    ]
                ]);
    }
}
