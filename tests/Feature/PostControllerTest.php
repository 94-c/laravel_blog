<?php

namespace Tests\Feature;

use App\Traits\ConnectionWithTag;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use ConnectionWithTag;

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

    public function test_findAllPosts_success()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/posts')->assertStatus(200);

        $this->assertEquals(1, count($response->json()));
    }

    public function test_create_post_success()
    {
        $token = $this->authenticate();

        $posts = [
            'title' => '테스트 포스트',
            'body' => '12334',
            'image' => 'null',
            'file' => 'null',
        ];

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('POST', 'api/posts', $posts)->assertStatus(201);
    }

    public function test_find_post_success()
    {
        $token = $this->authenticate();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/posts/1')->assertStatus(200);
    }


    public function test_update_post_success()
    {
        $token = $this->authenticate();

        $posts = [
            'title' => '수정 테스트 포스트',
            'body' => '123123123121',
            'image' => 'null',
            'file' => 'null',
        ];

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('PUT', 'api/posts/2', $posts)->assertStatus(200);
    }

    public function test_delete_post_success()
    {
        $token = $this->authenticate();

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('DELETE', 'api/posts/2')->assertStatus(200);
    }





}
