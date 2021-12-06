<?php

namespace Tests\Feature;

use App\User;
use UserSeeder;
use RoleSeeder;
use CategorySeeder;
use CategoryProductSeeder;
use ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryTest extends TestCase {
    use RefreshDatabase;
    private int $roles_max_seq;
    private int $users_max_seq;
    private int $categories_max_seq;
    private int $products_max_seq;
    private int $category_products_max_seq;

    public function setUp(): void {
        parent::setUp();

        DB::statement("ALTER SEQUENCE roles_id_seq RESTART WITH 1;");
        DB::statement("ALTER SEQUENCE users_id_seq RESTART WITH 1;");
        DB::statement("ALTER SEQUENCE categories_id_seq RESTART WITH 1;");
        DB::statement("ALTER SEQUENCE products_id_seq RESTART WITH 1;");
        DB::statement("ALTER SEQUENCE category_products_id_seq RESTART WITH 1;");

        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);
        $this->seed(CategoryProductSeeder::class);

        $this->roles_max_seq = DB::table('roles')->max('id') + 1;
        $this->users_max_seq = DB::table('users')->max('id') + 1;
        $this->categories_max_seq = DB::table('categories')->max('id') + 1;
        $this->products_max_seq = DB::table('products')->max('id') + 1;
        $this->category_products_max_seq = DB::table('category_product')->max('id') + 1;
    }
    public function tearDown(): void {
        DB::statement("ALTER SEQUENCE roles_id_seq RESTART WITH $this->roles_max_seq;");
        DB::statement("ALTER SEQUENCE users_id_seq RESTART WITH $this->users_max_seq;");
        DB::statement("ALTER SEQUENCE categories_id_seq RESTART WITH $this->categories_max_seq;");
        DB::statement("ALTER SEQUENCE products_id_seq RESTART WITH $this->products_max_seq;");
        DB::statement("ALTER SEQUENCE category_products_id_seq RESTART WITH $this->category_products_max_seq;");

        parent::tearDown();
    }
    private function getCustomerToken() {
        return JWTAuth::fromUser(User::where('role_id', 2)->first());
    }
    private function getAdminToken() {
        return JWTAuth::fromUser(User::where('role_id', 1)->first());
    }

    public function testGetAllCategories() {
        $resp = $this->json(
            'GET',
            '/api/v1/category',
            [],
            ['Accept' => 'application/json', 'Content-Type' => 'application/json']
        );
        $resp
            ->assertStatus(200)
            ->assertJsonStructure([['id', 'name']]);
    }

    public function testGetCategoryWithProductDetails() {
        $id = 1;
        $resp = $this->json(
            'GET',
            '/api/v1/category/' . $id,
            [],
            ['Accept' => 'application/json', 'Content-Type' => 'application/json']
        );
        $resp
            ->assertStatus(200)
            ->assertJsonStructure([['id', 'name', 'product']]);
    }

    public function testAddCategoryWithCustomerToken() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getCustomerToken()
            )
            ->json(
                'POST',
                'api/v1/category',
                [],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp
            ->assertStatus(401)
            ->assertExactJson(['status' => 'unauthorized']);
    }

    public function testUpdateCategoryWithCustomerToken() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getCustomerToken()
            )
            ->json(
                'PATCH',
                'api/v1/category/1',
                ['name' => 'Households'],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp
            ->assertStatus(401)
            ->assertExactJson(['status' => 'unauthorized']);
    }

    public function testDeleteCategoryWithCustomerToken() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getCustomerToken()
            )
            ->json(
                'DELETE',
                'api/v1/category/1',
                [],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp->assertStatus(405);
    }

    public function testAddCategoryWithAdminToken() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getAdminToken()
            )
            ->json(
                'POST',
                'api/v1/category',
                ['name' => 'Households'],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp
            ->assertStatus(201)
            ->assertJsonPath('name', 'Households');
        $this->assertDatabaseHas('categories', ['name' => 'Households']);
    }

    public function testUpdateCategoryWithAdminToken() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getAdminToken()
            )
            ->json(
                'PATCH',
                'api/v1/category/4',
                ['name' => 'Home Furnishings'],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp
            ->assertStatus(200)
            ->assertJsonPath('name', 'Home Furnishings');
        $this->assertDatabaseHas('categories', ['name' => 'Home Furnishings']);
    }

    public function testDeleteCategoryWithAdminToken() {
        $this->withoutExceptionHandling();
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getAdminToken()
            )
            ->json(
                'DELETE',
                'api/v1/category/4',
                [],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp->assertStatus(405);
    }
}
