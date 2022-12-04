<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Transfer;
use App\Models\AccountUser;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewerRouteTest extends TestCase
{
    use withFaker;
    use RefreshDatabase;
    
    /**
     * logged in user
     *
     * @var User
     */
    protected User $user;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        Passport::actingAs($this->user = User::factory()->create());
        $role_viewer = Role::factory()->create(["name"=>"viewer", 'description' => 'can view transactions']);
        $this->user->roles()->attach($role_viewer);
    }
    /**
     * test viewer user can view account balance
     *
     * @return void
     */
    public function test_viewer_can_view_account_balance()
    {
        $account = AccountUser::firstOrCreate(
            ['email' => 'exampleuser@example.com'],
            ['name' => 'exampleuser']
        )->accounts()->create(
            [
                'balance' => 5000
            ]
        );
        $this->json('GET', "api/user/actions/{$account->id}")
        ->assertStatus(200)
        ->assertJson([
           'name' => 'exampleuser',
           'currency' => 'usd',
           'balance' => 5000
        ]);

    }

    /**
     * test viewer user can view account balance
     *
     * @return void
     */
    public function test_viewer_can_view_account_history()
    {
        $account_a = AccountUser::firstOrCreate(
            ['email' => 'exampleuser@example.com'],
            ['name' => 'exampleuser']
        )->accounts()->create(
            [
                'balance' => 5000
            ]
        );
        $account_b = AccountUser::firstOrCreate(
            ['email' => 'exampleuser@example.com'],
            ['name' => 'exampleuser']
        )->accounts()->create(
            [
                'balance' => 5000
            ]
        );
        (new Transfer())->executeTransfer([
            'from_account_id' => $account_a->id,
            'to_account_id' => $account_b->id,
            'amount' => 500
        ]);

        $this->json('GET', "api/user/transfer/{$account_a->id}")
        ->assertStatus(200)
        ->assertJson([
            [
                'trans_type' => 'Send',
                'to_account_id' => $account_b->id,
                'to_account_name' => $account_b->name,
                'amount' => 500
            ]
        ]);
        $this->json('GET', "api/user/transfer/{$account_b->id}")
        ->assertStatus(200)
        ->assertJson([
            [
                'trans_type' => 'Receive',
                'from_account_id' => $account_a->id,
                'from_account_name' => $account_a->name,
                'amount' => 500
            ]
        ]);
    }
}
