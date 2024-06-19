<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * Class CustomerApiTest
 *
 * Unit tests for the Customer API endpoints.
 */
class CustomerApiTest extends TestCase
{
    use DatabaseTransactions; // Use DatabaseTransactions trait to handle transactions
    use WithFaker; // Use WithFaker trait to generate fake data

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Disalbe API Middleware
        $this->withoutMiddleware();
    }

    /** @test */
    public function it_can_list_customers()
    {
        // Create a mock customer using factory or model creation
        Customer::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 30,
            'dob' => '1991-01-01',
            'email' => 'john.doe@example.com',
        ]);

        // Send GET request to /api/customers endpoint
        $response = $this->getJson('/api/customers');

        // Assert HTTP OK status
        $response->assertStatus(Response::HTTP_OK);

        // Assert response JSON structure
        $response->assertJsonStructure([
            'data' => [
                [
                    'id', 
                    'first_name', 
                    'last_name', 
                    'age', 
                    'dob', 
                    'email', 
                ]
            ],
            'status'
        ]);

        // Assert specific data is present in response
        $response->assertJsonFragment(['first_name' => 'John', 'email' => 'john.doe@example.com']);
    }

    /** @test */
    public function it_can_find_a_customer_by_id()
    {
        // Create a mock customer using factory or model creation
        $customer = Customer::factory()->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'age' => 25,
            'dob' => '1996-02-02',
            'email' => 'jane.doe@example.com',
        ]);

        // Send GET request to /api/customers/{id} endpoint
        $response = $this->getJson("/api/customers/{$customer->id}");

        // Assert HTTP OK status
        $response->assertStatus(Response::HTTP_OK);

        // Assert response JSON structure
        $response->assertJsonStructure([
            'data' => [
                'id', 
                'first_name', 
                'last_name', 
                'age', 
                'dob', 
                'email', 
            ],
            'status'
        ]);

        
        // Assert specific data is retrieved
        $response->assertJsonFragment([
            
            
        ]);
    }

    /** @test */
    public function it_can_create_a_customer()
    {
        // Generate fake customer data
        $customerData = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'age' => $this->faker->numberBetween(20, 60),
            'dob' => $this->faker->date('Y-m-d', '-20 years'),
            'email' => $this->faker->unique()->safeEmail,
        ];

        // Send POST request to /api/customers endpoint with fake data
        $response = $this->postJson('/api/customers', $customerData);

        // Assert HTTP CREATED status
        $response->assertStatus(Response::HTTP_CREATED);

        // Assert response JSON structure
        $response->assertJsonStructure([
            'message', 
            'data' => [
                'id', 
                'first_name', 
                'last_name', 
                'age', 
                'dob', 
                'email', 
            ],
            'status'
        ]);

        // Assert specific data is present in response
        $response->assertJsonFragment(['email' => $customerData['email']]);
    }

    /** @test */
    public function it_can_update_a_customer()
    {
        // Create a mock customer using factory or model creation
        $customer = Customer::factory()->create();

        // Generate updated data
        $updatedData = [
            'first_name' => 'UpdatedFirstName',
            'last_name' => 'Doe',
            'age' => 35,
            'dob' => '1989-01-01',
            'email' => $customer->email, // Use existing email or generate a new one
        ];

        // Send PUT request to /api/customers/{id} endpoint with updated data
        $response = $this->putJson("/api/customers/{$customer->id}", $updatedData);

        // Assert HTTP OK status
        $response->assertStatus(Response::HTTP_OK);

        // Assert response JSON structure
        $response->assertJsonStructure([
            'message', 
            'data' => [
                'id', 
                'first_name', 
                'last_name', 
                'age', 
                'dob', 
                'email', 
            ],
            'status'
        ]);

        // Assert specific data is updated in response
        $response->assertJsonFragment(['first_name' => $updatedData['first_name'], 'age' => $updatedData['age']]);
    }

    /** @test */
    public function it_can_delete_a_customer()
    {
        // Create a mock customer using factory or model creation
        $customer = Customer::factory()->create();

        // Send DELETE request to /api/customers/{id} endpoint
        $response = $this->deleteJson("/api/customers/{$customer->id}");

        // Assert HTTP NO CONTENT status
        $response->assertStatus(Response::HTTP_OK);

        // Assert database record is deleted
        $this->assertDatabaseMissing('customer', ['id' => $customer->id]);
    }
}
