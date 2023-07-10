<?php

namespace Tests\Feature\Admin;

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

    public function test_findAllUsers()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/users')->assertStatus(200);

        $this->assertEquals(1, count($response->json()));
    }

    public function test_findByUser()
    {
        $token = $this->authenticate();

        $userId = 1;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/users/' . $userId)->assertStatus(200);

    }

    public function test_findPostsByUsers()
    {
        $token = $this->authenticate();

        $userId = 1;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/users/' . $userId . '/posts')->assertStatus(200);

    }

    public function test_findCommentsByUsers()
    {
        $token = $this->authenticate();

        $userId = 1;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('GET', 'api/admin/users/' . $userId . '/comments')->assertStatus(200);

    }

    public function test_disableUser()
    {
        $token = $this->authenticate();

        $userId = 1;

        $password = 1234;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('PUT', 'api/admin/users/' . $userId . '/disable?password=' . $password)->assertStatus(200);

    }

    public function test_enableUser()
    {
        $token = $this->authenticate();

        $userId = 1;

        $password = 1234;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('PUT', 'api/admin/users/' . $userId . '/enable?password=' . $password)->assertStatus(200);

    }

    public function test_updateUserRole()
    {
        $token = $this->authenticate();

        $userId = 1;

        $admin = 1;
        $manager = 2;
        $member = 3;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,])->json('PUT', 'api/admin/users/' . $userId . '/roles?roleId=' . $manager)->assertStatus(200);

    }


}
