<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;
use Illuminate\Support\Facades\DB;
use UserSeeder;
use RoleSeeder;

class UserTest extends TestCase {
    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();
        DB::statement("ALTER SEQUENCE users_id_seq RESTART WITH 1;");
        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
    }
    public function tearDown(): void {
        $max = DB::table('users')->max('id') + 1;
        DB::statement("ALTER SEQUENCE users_id_seq RESTART WITH $max;");
        parent::tearDown();
    }
    public function __construct() {
        parent::__construct();;
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
            ->assertJson([['id' => 1],['id' => 2]]);
    }
}
