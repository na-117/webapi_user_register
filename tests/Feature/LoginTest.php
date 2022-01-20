<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        // データベースマイグレーション
        $this->artisan('migrate');
        // $user = User::factory()->create();
        // $response = $this->post('/api/login', ['name' => $user->name, 'password' => 'password']);
        // $response->assertOk();
        // $this->token = $response->json('token');
        // $this->id = $user->id;
        // $this->name = $user->name;
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_ログインができるか()
    {
        $user = User::factory()->create();
        $response = $this->post('/api/login', ['name' => $user->name, 'password' => 'password']);
        $response->assertOk()
                ->assertJson([
                    'token' => $response->json('token'),
                ]);
    }

    public function test_空配列でのログインエラー時400が返ってくるか()
    {
        $response = $this->post('/api/login', []);
        $response->assertStatus(400)
                ->assertSeeText('Validation error');
    }

    public function test_名前がint型でのログインエラー時400が返ってくるか()
    {
        $params = ["name" => 1, "password" => 'password'];
        $response = $this->post('/api/login', $params);
        $response->assertStatus(400)
                ->assertSeeText('Validation error');
    }

    public function test_パスワードがint型でのログインエラー時400が返ってくるか()
    {
        $params = ["name" => "test", "password" => 1];
        $response = $this->post('/api/login', $params);
        $response->assertStatus(400)
                ->assertSeeText('Validation error');
    }

    public function test_存在しない名前でログインエラー時500が返ってくるか()
    {
        User::factory()->create();
        $response = $this->post('/api/login', ['name' => 'hogehoge', 'password' => 'password']);
        $response->assertStatus(404)
                ->assertSeeText('User Not Found');

    }

    public function test_パスワード違いのログインエラー時404が返ってくるか()
    {
        $user = User::factory()->create();
        $response = $this->post('/api/login', ['name' => $user->name, 'password' => 'hogehoge']);
        $response->assertStatus(404)
                ->assertSeeText('User Not Found');
    }
}
