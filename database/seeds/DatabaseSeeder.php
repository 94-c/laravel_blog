<?php

use App\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'admin'
        ]);

        DB::table('roles')->insert([
            'id' => 2,
            'name' => 'manager'
        ]);

        DB::table('roles')->insert([
            'id' => 3,
            'name' => 'member'
        ]);

        DB::table('users')->insert([
            'email' => 'admin@gmail.com',
            'name' => 'admin',
            'password' => md5('1234'),
            'state' => 1,
            'provider' => 'mailTrap',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('posts')->insert([
            'user_id' => 1,
            'title' => 'Admin First Posts',
            'body' => 'Welcome to Laravel',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('comments')->insert([
            'user_id' => 1,
            'post_id' => 1,
            'body' => 'first comment',
            'parent_id' => 0,
            'user_ip' => '127.0.0.1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('comments')->insert([
            'user_id' => 1,
            'post_id' => 1,
            'body' => 'sub comment',
            'parent_id' => 1,
            'user_ip' => '127.0.0.1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('likes')->insert([
            'user_id' => '1',
            'like' => 1,
            'likeable_type' => Comment::class,
            'likeable_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            'user_id' => 1,
            'role_id' => 1,
        ]);
    }
}
