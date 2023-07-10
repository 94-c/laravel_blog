<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    protected function authenticate()
    {
        $login = [
            'email' => 'admin@gmail.com',
            'password' => '1234',
            'provider' => 'mailTrap'
        ];

        $token = Auth::guard('api')->attempt($login);

        return $token;
    }

    public function test_find_all_comments()
    {
        $token = $this->authenticate();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/comments')->assertStatus(200);
    }

    public function test_find_by_comments()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/comments/1');

        return $response->assertStatus(200);
    }

    public function test_disable_comments()
    {
        $token = $this->authenticate();

        $commentsId = 1;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('PUT', 'api/admin/comments/' . $commentsId . '/disable');

        return $response->assertStatus(200);
    }

    public function test_enable_comments()
    {
        $token = $this->authenticate();

        $commentsId = 31;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('PUT', 'api/admin/comments/' . $commentsId . '/disable');

        return $response->assertStatus(200);
    }
}
