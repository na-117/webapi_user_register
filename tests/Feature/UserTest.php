<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
        $params = ['name' => 'hirai'];
        $response = $this->post('/api/users', $params);
        dd(json_decode($response->content())->name);

        $response->assertStatus(201);
    }
}
