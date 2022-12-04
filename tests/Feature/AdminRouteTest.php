<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminRouteTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Passport::actingAs($this->user = User::factory()->create());
        $role_admin = Role::factory()->create(["name"=>"admin", 'description' => 'manage users']);
        $this->user->roles()->attach($role_admin);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_list_users()
    {
        $users = User::all()->toArray();
        $this->json('GET', 'api/admin/users')
        ->assertStatus(200)
        ->assertJson($users);
    }

    /**
     * Admin can see roles in the database
     *
     * @return void
     */
    public function test_admin_can_list_roles()
    {
        $roles = Role::all()->toArray();
        $this->json('GET', 'api/admin/roles')
        ->assertStatus(200)
        ->assertJson($roles);
    }

    /**
     * Admin can edit/add roles on users
     *
     * @return void
     */
    public function test_admin_can_edit_user_roles()
    {
        Role::factory()->create(["name"=>"creator", 'description' => 'can modify user accounts']);

        $roles = ['admin','creator'];

        $this->json('POST', 'api/admin/users', [
            'email' => $this->user->email,
            'roles' => $roles
        ])
        ->assertStatus(200)
        ->assertJson([
            'id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'role' => $roles
        ]);
    }

    /**
     * test whether admin can create a role
     *
     * @return void
     */
    public function test_admin_can_create_role()
    {
        $this->json('POST', 'api/admin/roles', [
            "name"=>"creator",
            'description' => 'can modify user accounts'
        ])
        ->assertStatus(201)
        ->assertJson([
            "name"=>"creator",
            'description' => 'can modify user accounts'
        ]);
    }


    /**
     * test whether admin can update a role
     *
     * @return void
     */
    public function test_admin_can_update_role()
    {
        $role =[
            'name' => 'admin',
            'description' => 'new description for admin'
        ];
        $this->json('PUT', 'api/admin/roles/1', [
            'name' => 'admin',
            'description' => 'new description for admin'
        ])
        ->assertStatus(200)
        ->assertJson($role);
    }

    /**
     * admin can remove a role
     *
     * @return void
     */
    public function test_admin_can_remove_role()
    {
        $role = Role::factory()->create(["name"=>"creator", 'description' => 'can modify user accounts']);
        $this->json('DELETE', "api/admin/roles/{$role->id}")
        ->assertStatus(200)
        ->assertJson([]);
    }
}
