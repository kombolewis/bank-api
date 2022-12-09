<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthRouteTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test auth register route works
     *
     * @return void
     */
    public function test_user_can_register()
    {
        $this->json('POST', 'api/auth/register', [
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ])
        ->assertStatus(201)
        ->assertJson([
            'name' => 'admin',
            'email' => 'admin@example.com',
        ])
        ->assertJsonStructure(['id','role']);
    }

    /**
     * Test auth login route works
     *
     * @return void
     */
    public function test_user_can_login()
    {
        Artisan::call('passport:install');
        $client = DB::table('oauth_clients')->where('id', 2)->first();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        $this->json('POST', 'oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => 'admin@example.com',
            'password' => 'password',
        ])
        ->assertStatus(200)
        ->assertJsonStructure(['token_type','access_token','refresh_token']);
    }

    /**
     * test user can logout
     *
     * @return void
     */
    public function test_user_can_logout()
    {
        Passport::actingAs($user = User::factory()->create());
        $this->json('POST', 'api/auth/logout')
        ->assertStatus(200)
        ->assertJson(['message' => 'logged out successfully']);
    }
}
