<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\AccountUser;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatorRouteTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Passport::actingAs($this->user = User::factory()->create());
        $role_creator = Role::factory()->create(["name"=>"creator", 'description' => 'can create accounts and initiate transfers']);
        $this->user->roles()->attach($role_creator);
    }
    /**
     * Test creator user can create an account
     *
     * @return void
     */
    public function test_creator_can_create_account()
    {
        $this->json('POST', 'api/user/actions', [
            'name' => 'exampleuser',
            'email' => 'exampleuser@example.com',
            'deposit' => '5000'
        ])
        ->assertStatus(201)
        ->assertJson([
            'name' => 'exampleuser',
            'email' => 'exampleuser@example.com',
            'balance' => '5000'
        ]);
    }

    /**
     * Test creator user can make a transfer
     *
     * @return void
     */
    public function test_creator_can_make_transfer()
    {
        $accountA = AccountUser::firstOrCreate(
            ['email' => 'exampleusera@example.com'],
            ['name' => 'exampleusera']
        )->accounts()->create(
            [
                'balance' => 5000
            ]
        );
        $accountB = AccountUser::firstOrCreate(
            ['email' => 'exampleuserb@example.com'],
            ['name' => 'exampleuserb']
        )->accounts()->create(
            [
                'balance' => 5000
            ]
        );
        $this->json('POST', 'api/user/transfer', [
            'from_account_id' => $accountA->id,
            'to_account_id' => $accountB->id,
            'amount' => 500.55
        ])
        ->assertStatus(201)
        ->assertJson([
            'message' => 'transfer executed successfully'
        ]);
    }
}
