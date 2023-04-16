<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $user = User::first();
        $this->actingAs($user);
    }

    // protected function tearDown(): void
    // {
    //     parent::tearDown();
    //     var_dump('akhir');
    // }
    /**
     * A basic feature test example.
     * @group test1
     * @return void
     */
    public function test_all_get_method()
    {
        $response = $this->get(route('user.data'));

        $response->assertStatus(200);
        // $response->assertViewHas('user.index');
    }
}
