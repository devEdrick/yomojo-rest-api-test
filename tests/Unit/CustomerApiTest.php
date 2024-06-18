<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class CustomerApiTest
 *
 * Unit tests for the Customer API endpoints.
 */
class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and get a token
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('TestToken')->accessToken;
    }

    /**
     * Test if a customer can be created successfully.
     *
     * @return void
     */
    public function test_it_can_create_a_customer()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 30,
            'dob' => '1991-01-01',
            'email' => 'john.doe@example.com',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/customers', $data);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Customer created successfully.',
                'data' => $data,
            ]);
    }

    /**
     * Test if all customers can be retrieved successfully.
     *
     * @return void
     */
    public function test_it_can_get_all_customers()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/customers');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'first_name', 'last_name', 'age', 'dob', 'email', 'creation_date']
                ]
            ]);
    }

    /**
     * Test if a single customer can be retrieved successfully.
     *
     * @return void
     */
    public function test_it_can_get_a_customer()
    {
        $customer = \App\Models\Customer::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $customer->id,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'age' => $customer->age,
                    'dob' => $customer->dob,
                    'email' => $customer->email,
                ]
            ]);
    }

    /**
     * Test if a customer can be updated successfully.
     *
     * @return void
     */
    public function test_it_can_update_a_customer()
    {
        $customer = \App\Models\Customer::factory()->create();

        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'age' => 25,
            'dob' => '1996-02-02',
            'email' => 'jane.doe@example.com',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/customers/{$customer->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Customer updated successfully.',
                'data' => $data,
            ]);
    }

    /**
     * Test if a customer can be deleted successfully.
     *
     * @return void
     */
    public function test_it_can_delete_a_customer()
    {
        $customer = \App\Models\Customer::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Customer deleted successfully.',
                'data' => []
            ]);
    }
}
