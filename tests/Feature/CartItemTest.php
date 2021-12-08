<?php

namespace Tests\Feature;

use App\User;
use UserSeeder;
use RoleSeeder;
use CategorySeeder;
use CategoryProductSeeder;
use ProductSeeder;
use CartItemSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CartItemTest extends TestCase {
    use RefreshDatabase;
    private int $roles_max_seq;
    private int $users_max_seq;
    private int $categories_max_seq;
    private int $products_max_seq;
    private int $category_products_max_seq;
    private int $cart_items_max_seq;

    public function setUp(): void {
        parent::setUp();

        DB::statement("ALTER SEQUENCE roles_id_seq RESTART WITH 1;");
        DB::statement("ALTER SEQUENCE users_id_seq RESTART WITH 1;");
        DB::statement("ALTER SEQUENCE categories_id_seq RESTART WITH 1;");
        DB::statement("ALTER SEQUENCE products_id_seq RESTART WITH 1;");
        DB::statement("ALTER SEQUENCE category_products_id_seq RESTART WITH 1;");
        DB::statement("ALTER SEQUENCE cart_items_id_seq RESTART WITH 1;");

        $this->seed(RoleSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);
        $this->seed(CategoryProductSeeder::class);
        $this->seed(CartItemSeeder::class);

        $this->roles_max_seq = DB::table('roles')->max('id') + 1;
        $this->users_max_seq = DB::table('users')->max('id') + 1;
        $this->categories_max_seq = DB::table('categories')->max('id') + 1;
        $this->products_max_seq = DB::table('products')->max('id') + 1;
        $this->category_products_max_seq = DB::table('category_product')->max('id') + 1;
        $this->cart_items_max_seq = DB::table('cart_items')->max('id') + 1;
    }
    public function tearDown(): void {
        DB::statement("ALTER SEQUENCE roles_id_seq RESTART WITH $this->roles_max_seq;");
        DB::statement("ALTER SEQUENCE users_id_seq RESTART WITH $this->users_max_seq;");
        DB::statement("ALTER SEQUENCE categories_id_seq RESTART WITH $this->categories_max_seq;");
        DB::statement("ALTER SEQUENCE products_id_seq RESTART WITH $this->products_max_seq;");
        DB::statement("ALTER SEQUENCE category_products_id_seq RESTART WITH $this->category_products_max_seq;");
        DB::statement("ALTER SEQUENCE cart_items_id_seq RESTART WITH $this->cart_items_max_seq;");

        parent::tearDown();
    }

    private function getCustomerToken() {
        return JWTAuth::fromUser(User::where('role_id', 2)->first());
    }
    private function getAdminToken() {
        return JWTAuth::fromUser(User::where('role_id', 1)->first());
    }
    public function testGetAllCartsUsingCustomerToken() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getCustomerToken()
            )
            ->json(
                'GET',
                '/api/v1/cart',
                [],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp
            ->assertUnauthorized();
    }
    public function testGetAllCartsUsingAdminToken() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getAdminToken()
            )
            ->json(
                'GET',
                '/api/v1/cart',
                [],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp
            ->assertStatus(200)
            ->assertJsonStructure([['id', 'product_id', 'user_id', 'quantity',]]);
    }
    public function testGetProductInUserCartUsingAdminToken() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getAdminToken()
            )
            ->json(
                'GET',
                '/api/v1/user/2',
                [],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
            $resp
                ->assertStatus(200)
                ->assertJsonStructure(['id', 'name', 'email', 'deleted_at', 'cart']);
    }
    public function testGetProductInDifferentUserCartUsingCustomerToken() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getCustomerToken()
            )
            ->json(
                'GET',
                '/api/v1/user/3',
                [],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp->assertUnauthorized();
    }
    public function testGetProductInOwnCartUsingCustomerToken() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getCustomerToken()
            )
            ->json(
                'GET',
                '/api/v1/user/2',
                [],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp
            ->assertStatus(200)
            ->assertJsonStructure(['id', 'name', 'email', 'deleted_at', 'cart']);
    }
    public function testAddItemToCart() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getCustomerToken()
            )
            ->json(
                'POST',
                '/api/v1/cart',
                ['product_id' => 3, 'quantity' => 1],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp->assertCreated();
        $this->assertDatabaseHas('cart_items', ['product_id' => 3, 'quantity' => 1, 'user_id' => 2]);
    }
    
    public function testAddItemToCartUsingNonUniqueProductIdAndUserId() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getCustomerToken()
            )
            ->json(
                'POST',
                '/api/v1/cart',
                ['product_id' => 1, 'quantity' => 1],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp->assertStatus(422);;
    }
    public function testUpdateItemInOwnCart() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getCustomerToken()
            )
            ->json(
                'PATCH',
                '/api/v1/cart/1',
                ['quantity' => 2],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp->assertStatus(200);
        $this->assertDatabaseHas('cart_items', ['product_id' => 1, 'quantity' => 2, 'user_id' => 2]);
    }
    public function testUpdateItemInOtherCart() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getCustomerToken()
            )
            ->json(
                'PATCH',
                '/api/v1/cart/4',
                ['quantity' => 2],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp->assertUnauthorized();
    }
    public function testRemoveItemInOwnCart() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getCustomerToken()
            )
            ->json(
                'DELETE',
                '/api/v1/cart/1',
                [],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp->assertStatus(200);
        $this->assertDatabaseMissing('cart_items', ['id' => 1]);
    }
    public function testRemoveItemInOtherCart() {
        $resp = $this
            ->withHeader(
                'Authorization',
                'Bearer ' . $this->getCustomerToken()
            )
            ->json(
                'DELETE',
                '/api/v1/cart/4',
                [],
                ['Accept' => 'application/json', 'Content-Type' => 'application/json']
            );
        $resp->assertUnauthorized();
    }
}
