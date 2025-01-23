<?php

namespace Tests\Feature\Auth;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BackendUserControllerTest extends TestCase
{
    use RefreshDatabase;



    public function test_displays_the_backend_index()
    {
        // make an admin user
        $user = User::factory()->create();
        $user->attachRole('administrator');
        $this->actingAs($user);

        $users = User::factory()->count(15)->create();

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('backend.users.index');
        $response->assertViewHas('users', function ($viewUsers) use ($users) {
            return $viewUsers->count() >0; // Paginated users
        });
    }


    public function test_displays_the_create_user_form()
    {
        // make an admin user
        $user = User::factory()->create();
        $user->attachRole('administrator');
        $this->actingAs($user);

        $roles = Role::factory()->count(3)->create();
     //   dd("there");
        $teams = Team::factory()->count(2)->create();


        $response = $this->get(route('admin.users.new'));

        $response->assertStatus(200);
        $response->assertViewIs('backend.users.create');
        $response->assertViewHasAll(['roles', 'teams']);
    }


    // public function it_creates_a_new_user_from_the_backend()
    // {
    //     $role = Role::factory()->create();
    //     $team = Team::factory()->create();

    //     $userData = [
    //         'name' => 'John Doe',
    //         'email' => 'john@example.com',
    //         'password' => 'password123',
    //         'roles' => [$role->id],
    //         'teams' => [$team->id],
    //     ];

    //     $response = $this->post(route('admin.users.store'), $userData);

    //     $response->assertRedirect(route('admin.users.index'));
    //     $this->assertDatabaseHas('users', [
    //         'email' => 'john@example.com',
    //     ]);
    //     $this->assertTrue(Hash::check('password123', User::first()->password));
    // }


    // public function it_prevents_duplicate_user_creation()
    // {
    //     $existingUser = User::factory()->create(['email' => 'john@example.com']);

    //     $response = $this->post(route('admin.users.store'), [
    //         'name' => 'John Doe',
    //         'email' => 'john@example.com',
    //         'password' => 'password123',
    //     ]);

    //     $response->assertRedirect(route('admin.users.create'));
    //     $response->assertSessionHas('flash_danger', 'User already exists');
    // }


    // public function it_displays_the_backend_edit_user_form()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->get(route('admin.users.edit', ['id' => $user->id]));

    //     $response->assertStatus(200);
    //     $response->assertViewIs('admin.users.edit');
    //     $response->assertViewHas('user', $user);
    // }


    // public function it_updates_a_user_from_the_backend()
    // {
    //     $user = User::factory()->create();
    //     $role = Role::factory()->create();
    //     $team = Team::factory()->create();

    //     $updateData = [
    //         'id' => $user->id,
    //         'name' => 'Updated Name',
    //         'email' => 'updated@example.com',
    //         'roles' => [$role->id],
    //         'teams' => [$team->id],
    //         'password' => 'newpassword',
    //     ];

    //     $response = $this->put(route('admin.users.update', $user), $updateData);

    //     $response->assertRedirect(route('admin.users.index'));
    //     $this->assertDatabaseHas('users', [
    //         'email' => 'updated@example.com',
    //     ]);
    //     $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
    // }


    // public function it_deletes_a_user_from_the_backend()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->delete(route('admin.users.destroy', $user));

    //     $response->assertRedirect(route('admin.users.index'));
    //     $this->assertDatabaseMissing('users', [
    //         'id' => $user->id,
    //     ]);
    // }


    // public function test_checks_if_email_exists()
    // {
    //     $user = User::factory()->create(['email' => 'john@example.com']);

    //     $response = $this->post(route('admin.users.checkemail'), ['email' => 'john@example.com']);

    //     $response->assertJson([
    //         'status' => 'error',
    //         'message' => 'User already exists',
    //     ]);

    //     $response = $this->post(route('admin.users.checkEmail'), ['email' => 'new@example.com']);

    //     $response->assertJson([
    //         'status' => 'success',
    //         'message' => 'User does not exist',
    //     ]);
    // }
}
