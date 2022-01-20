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
        $user = User::factory()->create();
        $response = $this->post('/api/login', ['name' => $user->name, 'password' => 'password']);
        $response->assertOk();
        $this->token = $response->json('token');
        $this->id = $user->id;
        $this->name = $user->name;
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_ユーザーの追加ができるか()
    {
        $params = ['name' => 'hirai', 'password' => 'password'];
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
                ->assertSeeText('Validation error');
    }

    public function test_int型でのユーザーの追加エラー時400が返ってくるか()
    {
        $params = ["name" => 1, "password" => 1];
        $response = $this->post('/api/users', $params);
        $response->assertStatus(400)
                ->assertSeeText('Validation error');
    }

    public function test_全てのユーザーが取得できるか()
    {
        User::factory()->count(10)->create();
        $response = $this->get('/api/users', [
            'Authorization' => 'Bearer '.$this->token
        ]);
        $response->assertOk()
                ->assertJsonCount(11);//トークン取得のために１件既にあるので
    }

    // public function test_トークンがない場合401が返ってくるか()
    // {
    //     User::factory()->count(10)->create();
    //     $response = $this->get('/api/users', []);
    //     $response->assertStatus(401)
    //             ->assertSeeText('このページへのアクセスにはログインが必要です');
    // }

    public function test_ID指定ユーザー取得ができるか()
    {
        $response = $this->get('/api/users/'. $this->id, [
            'Authorization' => 'Bearer '.$this->token
        ]);
        $response->assertOk()
                ->assertJson([
                    'id' => $this->id,
                    'name' => $this->name,
                ]);
    }

    public function test_ID指定ユーザー取得エラー時に404が返ってくるか()
    {
        $response = $this->get('/api/users/' . 999, [
            'Authorization' => 'Bearer '.$this->token
        ]);
        $response->assertStatus(404)
                ->assertSeeText('User Not Found');
    }

    public function test_IDが文字列での指定ユーザー取得エラー時に400が返ってくるか()
    {
        $response = $this->get('/api/users/' . 'abc', [
            'Authorization' => 'Bearer '.$this->token
        ]);
        $response->assertStatus(400)
                ->assertSeeText('Validation error');
    }

    public function test_IDが0指定ユーザー取得エラー時に400が返ってくるか()
    {
        $response = $this->get('/api/users/' . 0);
        $response->assertStatus(400)
                ->assertSeeText('Validation error');
    }
}
