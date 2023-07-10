<?php

namespace Tests\Feature;

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

    public function test_create_comment_success()
    {
        $token = $this->authenticate();

        $comments = [
            'body' => '테스트 코멘트',
            'parentId' => 0
        ];

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('POST', 'api/posts/1/comments', $comments)->assertStatus(201);
    }

    public function test_create_sub_comment_success()
    {
        $token = $this->authenticate();

        $comments = [
            'body' => '테스트 코멘트',
            'parentId' => 2
        ];

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('POST', 'api/posts/1/comments', $comments)->assertStatus(201);
    }

    public function test_find_comment_success()
    {
        $token = $this->authenticate();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/posts/1/comments')->assertStatus(200);
    }

    public function test_find_comment_fail()
    {
        $token = $this->authenticate();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/posts/10/comments')->assertStatus(200);
    }

    public function test_update_comment_success()
    {
        $token = $this->authenticate();

        $comments = [
            'body' => '테스트 코멘트 진행중입니다.',
            'parentId' => 0,
        ];

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('PUT', 'api/posts/1/comments/1', $comments)->assertStatus(201);
    }

    public function test_delete_comment_success()
    {
        $token = $this->authenticate();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('DELETE', 'api/posts/1/comments/7')->assertStatus(204);
    }


}
