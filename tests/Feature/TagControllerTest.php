<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TagControllerTest extends TestCase
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


    public function test_findPostsByTagName_success()
    {
        $token = $this->authenticate();

        $tagName = '테스트 태그';

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token])->json('GET', 'api/tags/posts', [
            'tagName' => $tagName,
        ])->assertStatus(200);
    }

    public function test_findPostsByTagName_fail()
    {
        $token = $this->authenticate();

        $tagName = '123124';

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token])->json('GET', 'api/tags/posts', [
            'tagName' => $tagName,
        ])->assertStatus(200);
    }

    public function test_findPostsByTag_success()
    {
        $token = $this->authenticate();

        $tag = 1;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/tags/'.$tag.'/posts')->assertStatus(200);

        $this->assertEquals(1, count($response->json()));
    }

    public function test_findPostsByTag_fail()
    {
        $token = $this->authenticate();

        $tag = 2;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/tags/'.$tag.'/posts')->assertStatus(200);

        $this->assertEquals(1, count($response->json()));
    }

    public function test_delete_all_tag_success()
    {
        $token = $this->authenticate();

        $post = 1;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('DELETE', 'api/posts/'.$post.'/tags')->assertStatus(200);
    }

    public function test_delete_tag_success()
    {
        $token = $this->authenticate();

        $post = 1;

        $tag = 1;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('DELETE', 'api/posts/'.$post.'/tags/'.$tag)->assertStatus(200);
    }
}
