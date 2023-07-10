<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserControllerTest extends TestCase
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

    protected function notAuthenticate()
    {
        $login = [
            'email' => 'test@test.com',
            'password' => '1234',
            'provider' => 'mailTrap'
        ];

        $token = Auth::guard('api')->attempt($login);

        return $token;
    }

    public function test_findByUser_success()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/users')->assertStatus(200);

        $this->assertEquals(1, count($response->json()));
    }

    public function test_findByUser_fail()
    {
        $token = $this->notAuthenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/users')->assertStatus(200);

        $this->assertEquals(1, count($response->json()));
    }


    public function test_updateUser_success()
    {
        $token = $this->authenticate();

        $users = [
            'name' => '수정 테스트 포스트',
        ];

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('PUT', 'api/users/1`', $users)->assertStatus(200);
    }

    public function test_disableUser_success()
    {
        $token = $this->authenticate();

        $users = [
            'password' => '1234',
        ];

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('POST', 'api/users/disable', $users)->assertStatus(200);
    }

    public function test_enableUser_success()
    {
        $token = $this->authenticate();

        $users = [
            'password' => '1234',
        ];

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('POST', 'api/users/enable', $users)->assertStatus(200);
    }



}
