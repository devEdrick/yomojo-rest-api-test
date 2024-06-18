<?php

namespace App\Services;

use App\Helpers\RequestManager;
use App\Helpers\TokenManager;
use Illuminate\Http\Client\Response;

/**
 * Class CustomerService
 * @package App\Services
 */
class CustomerService
{
    protected RequestManager $requestManager;

    /**
     * CustomerService constructor.
     * @param RequestManager $requestManager
     */
    public function __construct(RequestManager $requestManager)
    {
        $this->requestManager = $requestManager;
    }

    /**
     * Get all customers.
     *
     * @return Response
     */
    public function get(): Response
    {
        return $this->requestManager->get('/api/customers');
    }

    /**
     * Find a customer by ID.
     *
     * @param int $id
     * @return Response
     */
    public function find(int $id): Response
    {
        return $this->requestManager->get("/api/customers/{$id}");
    }

    /**
     * Create a new customer.
     *
     * @param array $data
     * @return Response
     */
    public function create(array $data): Response
    {
        return $this->requestManager->post('/api/customers', $data);
    }

    /**
     * Update a customer.
     *
     * @param int $id
     * @param array $data
     * @return Response
     */
    public function update(int $id, array $data): Response
    {
        return $this->requestManager->put("/api/customers/{$id}", $data);
    }

    /**
     * Delete a customer.
     *
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        return $this->requestManager->delete("/api/customers/{$id}");
    }
}
