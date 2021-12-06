<?php

namespace Tests\Feature;

use CategorySeeder;
use CategoryProductSeeder;
use ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductTest extends TestCase {
    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();
        //DB::statement("ALTER SEQUENCE products_id_seq RESTART WITH 1;");
        $this->seed(CategorySeeder::class);
        $this->seed(ProductSeeder::class);
        $this->seed(CategoryProductSeeder::class);
    }
    public function tearDown(): void {
        $max = DB::table('users')->max('id') + 1;
        DB::statement("ALTER SEQUENCE products_id_seq RESTART WITH $max;");
        parent::tearDown();
    }
    public function __construct() {
        parent::__construct();;
    }

    public function testGetAllProducts() {
        $resp = $this->json(
            'GET',
            '/api/v1/product',
            [],
            ['Accept' => 'application/json', 'Content-Type' => 'application/json']
        );
        $resp
            ->assertStatus(200)
            ->assertJsonStructure([['id', 'name']]);
    }
    public function testGetProductDetails() {
        $id = 1;
        $resp = $this->json(
            'GET',
            '/api/v1/product/' . $id,
            [],
            ['Accept' => 'application/json', 'Content-Type' => 'application/json']
        );
        $resp
            ->assertStatus(200)
            ->assertJsonStructure([['id', 'name', 'price', 'created_at', 'updated_at', 'categories']]);
    }
}
