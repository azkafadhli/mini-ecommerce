<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;
use Illuminate\Support\Facades\DB;
use UserSeeder;
use RoleSeeder;

class UserTest extends TestCase {
    use RefreshDatabase;
    private int $users_max_seq;
    private int $roles_max_seq;

    public function setUp(): void {
        parent::setUp();
        DB::statement("ALTER SEQUENCE users_id_seq RESTART WITH 1;");
        DB::statement("ALTER SEQUENCE roles_id_seq RESTART WITH 1;");
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->users_max_seq = DB::table('users')->max('id') + 1;
        $this->roles_max_seq = DB::table('roles')->max('id') + 1;
    }
    public function tearDown(): void {
        DB::statement("ALTER SEQUENCE users_id_seq RESTART WITH $this->users_max_seq;");
        DB::statement("ALTER SEQUENCE roles_id_seq RESTART WITH $this->roles_max_seq;");
        parent::tearDown();
    }

    public function testGetAllUsersWithoutToken() {
        $resp = $this->json(
            'GET',
            '/api/v1/user',
            [],
            ['Accept' => 'application/json', 'Content-Type' => 'application/json']
        );
        $resp
            ->assertStatus(401)
            ->assertExactJson(['status' => 'Authorization Token not found']);
    }
    public function testGetAllUserWithCustomerToken() {
        $user = User::where('role_id', 2)->first();
        $token = JWTAuth::fromUser($user);
        $resp = $this
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->json(
                'GET',
                '/api/v1/user',
                [],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp
            ->assertStatus(401)
            ->assertExactJson(['status' => 'unauthorized']);
    }
    public function testGetAllUserWithAdminToken() {
        $user = User::where('role_id', 1)->first();
        $token = JWTAuth::fromUser($user);
        $resp = $this
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->json(
                'GET',
                '/api/v1/user',
                [],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp
            ->assertStatus(200)
            ->assertJson([['id' => 1]]);
    }
    public function testRegisterUserWithInvalidPayload() {
        $resp = $this
            ->json(
                'POST',
                '/api/v1/user',
                ['role_id' => 1, 'email' => '123@example', 'name' => 123, 'password' => 'mypassword'],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp->assertStatus(422)->assertJsonStructure(['message', 'errors']);
    }
    public function testRegisterUserWithValidPayload() {
        $resp = $this
            ->json(
                'POST',
                '/api/v1/user',
                ['role_id' => 2, 'email' => '123@gmail.com', 'name' => 123, 'password' => 'mypassword'],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp
            ->assertStatus(201)
            ->assertJsonPath('name', 123)
            ->assertJsonPath('email', '123@gmail.com');
        $this->assertDatabaseHas('users', ['name' => 123, 'email' => '123@gmail.com']);
    }
    public function testUpdateUserWithInvalidToken() {
        /*
        Invalid token = use token from one user to updating another user
        */
        $token = JWTAuth::fromUser(User::where('id', 1)->first());
        $resp = $this
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->json(
                'PATCH',
                'api/v1/user/2',
                ['email' => 'karolann.windler@gmail.com'],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp->assertUnauthorized();
    }
    public function testUpdateUserWithValidToken() {
        /*
        Valid token = use token from one user to updating own details
        */
        $token = JWTAuth::fromUser(User::where('id', 2)->first());
        $resp = $this
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->json(
                'PATCH',
                'api/v1/user/2',
                ['email' => 'karolann.windler@gmail.com'],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'karolann.windler@gmail.com']);
    }
}
